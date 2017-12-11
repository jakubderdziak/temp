<?php

namespace Derdziak\ProductBundle\Rates;

class Downloader
{
    /**
     * @var \Iterator
     */
    private $reader;

    /**
     * @var WriterInterface
     */
    private $writer;

    public function __construct(
        \Iterator $iterator,
        WriterInterface $writer
    ) {
        $this->reader = $iterator;
        $this->writer = $writer;
    }

    public function download()
    {
        $this->reader->rewind();

        foreach ($this->reader as $exchangeRate) {
            //TODO - need to prevent duplicates!
            $this->writer->write($exchangeRate);
        }
    }
}