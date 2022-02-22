<?php


class BasketProduct
{
    public $product;
    public $quantity;
    public $baseTotalPrice;
    public $discountedProducts;
    public $promotionsHistory;
    public $discountValue;

    public function __construct(Product $product, int $quantity = 0)
    {
        $this->product = $product;
        $this->quantity = $quantity ?? 0;
        $this->baseTotalPrice = $this->calculateBasePriceTotal();
        $this->discountedProducts = 0;
        $this->promotionsHistory = [];
        $this->discountValue = 0;
    }

    public function calculateBasePriceTotal(): int
    {
        return $this->quantity * $this->product->unitPrice;
    }

    /**
     * @param $addQuantity
     * @return $this
     */
    public function increaseQuantity($addQuantity): self
    {
        $this->quantity = $this->quantity + $addQuantity;
        $this->baseTotalPrice = $this->calculateBasePriceTotal();
        return $this;
    }

    public function getUndiscountedProductsQuantity()
    {
        return $this->quantity - $this->discountedProducts;
    }

    /**
     * @param ProductPromotion $productPromotion
     * @return mixed
     */
    public function processProductPromotion(ProductPromotion $productPromotion, BasketProduct $bundledProduct = null): self
    {
        switch ($productPromotion->type) {
            case ProductPromotion::$bundle:
                if ($bundledProduct instanceof BasketProduct) {
                    $noOfItemsToDiscount = min($this->quantity, $bundledProduct->quantity);
                    $this->discountValue = $noOfItemsToDiscount * ($this->product->unitPrice - $productPromotion->price);
                    $this->discountedProducts = $noOfItemsToDiscount;
                    $this->promotionsHistory[] = [
                        'promotion_id' => $productPromotion->id,
                        'items_discounted' => $noOfItemsToDiscount,
                        'discount_value' => $this->discountValue,
                    ];
                }
                break;
            case ProductPromotion::$stockup:
                $undiscountedProducts = $this->getUndiscountedProductsQuantity();
                if ($undiscountedProducts >= $productPromotion->productQuantity) {
                    $itemsReminder = $undiscountedProducts % $productPromotion->productQuantity;
                    $noOfItemsToDiscount = $undiscountedProducts - $itemsReminder;
                    $this->discountValue = ($this->product->unitPrice * $noOfItemsToDiscount) - ($noOfItemsToDiscount / $productPromotion->productQuantity * $productPromotion->price);
                    $this->discountedProducts = $noOfItemsToDiscount;
                    $this->promotionsHistory[] = [
                        'promotion_id' => $productPromotion->id,
                        'items_discounted' => $noOfItemsToDiscount,
                        'discount_value' => $this->discountValue,
                    ];
                }
                break;
        }

        return $this;
    }
}