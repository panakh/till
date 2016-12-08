<?php

namespace App\Domain;

class Receipt
{
    private $products = [];
    private $discount = 0.0;
    private $grandTotal = 0.0;
    private $subTotal = 0.0;
    private $currency;
    private $validCurrencies = [
        '£' => 'en_GB.UTF-8',
        '$' => 'en_US.UTF-8',
        ];

    public function __construct($currency)
    {
        if (!in_array($currency, array_keys($this->validCurrencies))) {
            throw new UnsupportedCurrencyException("Unsupported currency $currency");
        }

        setlocale(LC_MONETARY, $this->validCurrencies[$currency]);
        $this->currency = $currency;
    }

    public function addProduct(Product $product)
    {
        $this->products[] = $product;
    }

    public function calculateTotals()
    {
        foreach ($this->products as $product) {
            $this->subTotal += $product->getPrice();
        }

        $this->grandTotal = $this->subTotal - $this->discount;
    }

    public function addDiscount($discount)
    {
        if (!is_float($discount)) {
            throw new InvalidAmountException("Invalid discount");
        }

        $this->discount = $discount;
    }

    public function getSubtotal()
    {
        return $this->subTotal;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function getGrandTotal()
    {
        return $this->grandTotal;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function getCurrency()
    {
        return $this->currency;
    }
}