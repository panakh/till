<?php

namespace App\Domain;

class Product
{
    private $name;
    private $price;

    public function __construct($name, $price)
    {
        $name = trim((string)$name);

        if ('' == $name
            || null == $name
        ) {
            throw new InvalidProductNameException('Product name invalid');
        }

        if (!is_numeric($price)) {
            throw new InvalidProductPriceException('Product price should be a float');
        }

        $price = (float)$price;

        if ($price < 0) {
            throw new InvalidProductPriceException('Product price negative');
        }

        $this->name = $name;
        $this->price = $price;

    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

}