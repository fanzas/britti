<?php

use PHPUnit\Framework\TestCase;
require_once('app/Models/Product.php');
require_once('app/Models/ProductPromotion.php');
require_once('app/Models/Basket.php');
require_once('app/Seeders/TestSeeder.php');
require_once('app/Helper.php');

final class BasketTest extends TestCase
{
    protected function setUp(): void
    {
        $this->products = TestSeeder::seedProducts();
        $productPromotions = TestSeeder::seedProductPromotions();
        $this->productPromotions = Helper::sortPromotions($productPromotions, $this->products);
    }

    public function testAssertItemsAddedToBasketCount() {
        $basket = new Basket();
        $basket->addProduct($this->products['1A'])->addProduct($this->products['2B'])->addProduct($this->products['3C'])->addProduct($this->products['4D']);

        $this->assertCount(4, $basket->products);
    }

    public function testBasketPriceAfterDiscount()
    {
        $basket = new Basket();
        $basket->addProduct($this->products['1A'], 11);
        $basket->processBasket($this->productPromotions);
        $this->assertEquals(490, $basket->total);

        $basket = new Basket();
        $basket->addProduct($this->products['1A'], 2);
        $basket->processBasket($this->productPromotions);
        $this->assertEquals(100, $basket->total);
    }

    public function testBasketForAddingTwoDiscountsOnOneProduct()
    {
        $basket = new Basket();
        $basket->addProduct($this->products['3C'], 11);
        $basket->processBasket($this->productPromotions);
        $this->assertEquals(188, $basket->total);
    }

    public function testBasketForAddingBundledItemsAndStockupItems()
    {
        //I am expecting both discounts for product A and product D to be added, as the task didn't specify excluding promotions for bundled items
        $basket = new Basket();
        $basket->addProduct($this->products['4D'], 11)->addProduct($this->products['1A'], 5);
        $basket->processBasket($this->productPromotions);
        $this->assertEquals(345, $basket->total);
    }

}