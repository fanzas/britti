<?php

class Product
{
    public $sku;
    public $name;
    public $unitPrice;

    /**
     * @param $sku
     * @param $name
     * @param $unitPrice
     */
    public function __construct($sku, $name, $unitPrice) {
        $this->sku = $sku;
        $this->name = $name;
        $this->unitPrice = $unitPrice;
    }
}