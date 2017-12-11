<?php

namespace Derdziak\ProductBundle\Rates;

use Derdziak\ProductBundle\Entity\ExchangeRate;

interface WriterInterface
{
    /**
     * @param ExchangeRate $exchangeRate
     */
    public function write(ExchangeRate $exchangeRate);
}