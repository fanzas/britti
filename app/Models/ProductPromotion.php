<?php

class ProductPromotion
{
    public static $stockup = 'stockup'; //used for specifying type of promotion where you get discount for buying the same products
    public static $bundle = 'bundle'; //used for specifying promotion where you get a discount for buying at least two separate products

    /**
     * @var $type map
     * type "stockup" = discount for buying multiple products of the same type
     * type "bundle" = bundle up with different products
     */
    public $id;
    public $type = '';
    public $productSku = '';
    public $productQuantity;
    public $productBundleSku;
    public $price;

    /**
     * @param string $type
     * @param string $productSku
     * @param array $discountDetails
     */
    public function __construct(int $id, string $type, string $productSku, $productQuantity, $productBundleSku, $price) {
        $this->type = $type;
        $this->productSku = $productSku;
        $this->productQuantity = $productQuantity;
        $this->productBundleSku = $productBundleSku;
        $this->price = $price;
    }

    /**
     * note: in real life app passing $product would be obsolete, as We would get it from the database by productSku on ProductPromotion
     * @param Product $product
     * @return null
     */
    public function getSavingOnAUnit(Product $product)
    {
        if ($this->type == self::$stockup) {
            return round((($product->unitPrice * $this->productQuantity - $this->price) / $this->productQuantity), 2);
        }
        if ($this->type == self::$bundle) {
            return round($product->unitPrice - $this->price, 2);
        }

        return null;
    }
}