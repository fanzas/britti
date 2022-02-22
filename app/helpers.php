<?php

class Helper
{
    public static function sortPromotions($productPromotions, $products)
    {
        usort($productPromotions, function($a, $b) use($products) {
            return $b->getSavingOnAUnit($products[$b->productSku]) <=> $a->getSavingOnAUnit($products[$a->productSku]);
        });
    }
}