<?php

/**
 * Get the discount percentage for a product.
 *
 * @param  int|WC_Product|null $product  Product object or ID. Defaults to global $product.
 * @param  bool                $floor    Round down to whole number? (true = 29.61 → 29)
 * @return int|float|false               Discount percentage, or false if no sale.
 */
function ews_get_discount_percent($product = null, $floor = true)
{

    $product = wc_get_product($product ?? get_the_ID());
    if (! $product) {
        return false;
    }

    // Simple product
    if ($product->is_type('simple')) {

        $regular = (float) $product->get_regular_price();
        $sale    = (float) $product->get_sale_price();

        if ($regular && $sale && $regular > $sale) {
            $percent = (($regular - $sale) / $regular) * 100;
            return $floor ? floor($percent) : $percent;
        }

        // Variable product – return the *largest* % among variations
    } elseif ($product->is_type('variable')) {

        $max = 0;
        foreach ($product->get_children() as $child_id) {
            $child   = wc_get_product($child_id);
            $regular = (float) $child->get_regular_price();
            $sale    = (float) $child->get_sale_price();

            if ($regular && $sale && $regular > $sale) {
                $pct = (($regular - $sale) / $regular) * 100;
                $max = max($max, $pct);
            }
        }
        if ($max > 0) {
            return $floor ? floor($max) : $max;
        }
    }

    return false; // no sale / no discount detected
}

/**
 * Echo / return a ready‑made badge "<span class='onsale'>‑29%</span>"
 *
 * @param  int|WC_Product|null $product
 * @param  bool                $echo  Echo directly? (true) or return string?
 * @return string|null
 */
function ews_get_discount_badge($product = null, $echo = true)
{

    $percent = ews_get_discount_percent($product);
    if (false === $percent) {
        return '';
    }

    $badge = '<span class="onsale">-' . intval($percent) . '%</span>';

    if ($echo) {
        echo $badge;
        return null;
    }
    return $badge;
}
