<?php

namespace Derdziak\ProductBundle\Rates;

use Derdziak\ProductBundle\Entity\ExchangeRate;
use Doctrine\ORM\EntityManagerInterface;

class Writer implements WriterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Writer constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param ExchangeRate $exchangeRate
     * This is very simple. Correct version should work in batches
     */
    public function write(ExchangeRate $exchangeRate)
    {
        $this->entityManager->persist($exchangeRate);
        $this->entityManager->flush();
    }
}