<?php

class Helper
{
    /**
     * Used to sorting promotions to establish which one is the highest in promotion hierarchy
     * @param $productPromotions
     * @param $products
     * @return mixed
     */
    public static function sortPromotions($productPromotions, $products)
    {
        usort($productPromotions, function($a, $b) use($products) {
            return $b->getSavingOnAUnit($products[$b->productSku]) <=> $a->getSavingOnAUnit($products[$a->productSku]);
        });

        return $productPromotions;
    }
}