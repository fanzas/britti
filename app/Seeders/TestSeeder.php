<?php

/**
 * This i a seeding method written fast for the purpose of the task as well as running unit tests
 */
class TestSeeder
{
    public static function seedProducts() {
        return [
            '1A' => new Product('1A', 'Shovel', 50),
            '2B' => new Product('2B', 'iPhone', 30),
            '3C' => new Product('3C', 'Sponge', 20),
            '4D' => new Product('4D', 'Domino', 15),
            '5E' => new Product('5E', 'Duvet', 5),
        ];
    }

    public static function seedProductPromotions() {
        //array key will ba an id, since this is how We would get certain discounts from the database by id
        return [
            1 => new ProductPromotion(1, ProductPromotion::$stockup, '1A', 3, null, 130),
            2 => new ProductPromotion(2, ProductPromotion::$stockup, '2B', 2, null, 45),
            3 => new ProductPromotion(3, ProductPromotion::$stockup, '3C', 2, null, 38),
            4 => new ProductPromotion(4, ProductPromotion::$stockup, '3C', 3, null, 50),
            5 => new ProductPromotion(5, ProductPromotion::$bundle, '4D', null, '1A', 5),
        ];
    }
}