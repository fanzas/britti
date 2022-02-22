<?php

class Basket
{
    /**
     * @var array
     * In real life environment I'd use a pivot table for table-product relations and quantities,
     * but for the sake of completing this task fast I will
     * create a mock up array of products in a basket and productArray will include quantities of a product
     */
    public $products = []; //array of products
    public $discountTotal;
    public $total;

    /**
     * Only used to set total to 0
     */
    public function __construct() {
        $this->total = 0;
    }

    /**
     * @param Product $product
     * @param int $quantity
     * @return $this
     */
    public function addProduct(Product $product, int $quantity = 1): Basket
    {

        $this->total = $this->total + ($product->unitPrice * $quantity);

        if (array_key_exists($product->sku, $this->products)) {
            $this->products[$product->sku] = [
                'product' => $product,
                'quantity' => $this->products[$product->sku]['quantity'] + $quantity,
                'baseTotalPrice' => $this->products[$product->sku]['quantity'] * $product->unitPrice,
            ];
            return $this;
        }

        $this->products[$product->sku] = [
            'product' => $product,
            'quantity' => $quantity,
            'baseTotalPrice' => $product->unitPrice * $quantity,
            'discountValue' => 0,
            'discountedProducts' => 0,
            'promotionUsed' => null,
        ];
        return $this;

    }

    /**
     * @param $productSku
     * @return int|null
     */
    public function getQuantityByProduct(Product $product): ?int
    {
        return array_key_exists($product->sku, $this->products) ? $this->products[$product->sku]['quantity'] : null;
    }

    public function applyDiscountToProduct(ProductPromotion $productPromotion)
    {
        $basketProduct = $this->products[$productPromotion->productSku];
        switch ($productPromotion->type) {
            case ProductPromotion::$bundle:
                $noOfItemsToDiscount = min($basketProduct['quantity'], $this->getQuantityByProduct($this->products[$productPromotion->productBundleSku]['product']));
                $basketProduct['discountValue'] = $noOfItemsToDiscount * ($basketProduct['product']->unitPrice - $productPromotion->price);
                $basketProduct['discountedProducts'] = $noOfItemsToDiscount;
                $basketProduct['promotionUsed'][] = $productPromotion->id;
                $this->total -= $basketProduct['discountValue'];
                break;
            case ProductPromotion::$stockup:
                if ($basketProduct['quantity'] - $basketProduct['discountedProducts'] >= $productPromotion->productQuantity) {
                    $itemsReminder = $basketProduct['quantity'] % $productPromotion->productQuantity;
                    $noOfItemsToDiscount = $basketProduct['quantity'] - $itemsReminder;
                    $basketProduct['discountValue'] = $noOfItemsToDiscount * $productPromotion->getSavingOnAUnit($basketProduct['product']);
                    $basketProduct['discountedProducts'] = $noOfItemsToDiscount;
                    $basketProduct['promotionUsed'][] = $productPromotion->id;
                    $this->total -= $basketProduct['discountValue'];
                }
                break;
        }
    }

    public function processBasket($productPromotions): Basket
    {
        foreach ($productPromotions as $productPromotion) {
            if (array_key_exists($productPromotion->productSku, $this->products)) {
                $this->applyDiscountToProduct($productPromotion);
            }
        }
        return $this;
    }
}