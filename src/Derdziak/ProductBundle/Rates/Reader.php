<?php

namespace Derdziak\ProductBundle\Rates;

use Derdziak\ProductBundle\Entity\ExchangeRate;

class Reader implements \Iterator
{
    const URL = 'https://api.fixer.io/latest';

    /**
     * @var \ArrayObject
     */
    private $data;

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var array
     */
    private $keys;

    /**
     * @var \DateTime
     */
    private $date;

    public function __construct()
    {
        $this->initData();
    }

    /**
     * {@inheritdoc}
     */
    function rewind()
    {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    function current()
    {
        $exchangeRate = new ExchangeRate();
        $exchangeRate->setValue($this->data[$this->key()])
            ->setCurrency($this->key())
            ->setDate($this->date);

        return $exchangeRate;
    }

    /**
     * {@inheritdoc}
     */
    function key()
    {
        return $this->keys[$this->position];
    }

    /**
     * {@inheritdoc}
     */
    function next()
    {
        ++$this->position;
    }

    /**
     * {@inheritdoc}
     */
    function valid()
    {
        return isset($this->keys[$this->position]);
    }

    private function initData()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        $data = json_decode($response);

        //TODO add validation if data is valid
        $this->data = (array)$data->rates;
        $this->keys = array_keys($this->data);
        $this->date = new \DateTime($data->date);
        $this->position = 0;
    }
}