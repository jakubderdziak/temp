<?php

namespace Derdziak\Context;

use Derdziak\ProductBundle\Entity\Product;

class ProductFormContext
{
    /**
     * @var Product
     */
    private $product;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }
}