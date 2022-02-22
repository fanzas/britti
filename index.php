<?php
require_once('app/Models/Product.php');
require_once('app/Models/ProductPromotion.php');
require_once('app/Models/Basket.php');
require_once('app/Seeders/TestSeeder.php');

$products = TestSeeder::seedProducts();
$productPromotions = TestSeeder::seedProductPromotions();

//I would've done this in database items collection, which wouldn't require passing $products at all, but I am not using any database for this assignment, as it is time sensitive
usort($productPromotions, function($a, $b) use($products) {
    return $b->getSavingOnAUnit($products[$b->productSku]) <=> $a->getSavingOnAUnit($products[$a->productSku]);
});

$basket = new Basket();
$basket->addProduct($products['1A'], 5)->addProduct($products['2B'], 9)->addProduct($products['3C'], 12)->addProduct($products['4D'], 11)->addProduct($products['5E'], 4);

$basket->processBasket($productPromotions);
