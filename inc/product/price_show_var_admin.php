<?php

add_filter('manage_edit-product_columns', 'custom_product_price_column');
function custom_product_price_column($columns)
{
    // No change to columns layout needed unless you want to rename it
    return $columns;
}

add_action('manage_product_posts_custom_column', 'custom_product_price_column_content', 10, 2);
function custom_product_price_column_content($column, $post_id)
{
    if ($column === 'price') {
        $product = wc_get_product($post_id);

        // ✅ Only override for variable products
        if ($product && $product->is_type('variable')) {
            $min_price = $product->get_variation_price('min', true);
            $max_price = $product->get_variation_price('max', true);

            if ($min_price !== $max_price) {
                echo '<span class="woocommerce-Price-amount amount">' . wc_price($min_price) . ' - ' . wc_price($max_price) . '</span>';
            } else {
                echo '<span class="woocommerce-Price-amount amount">' . wc_price($min_price) . '<br/></span>';
            }

            return; // ✅ prevent WooCommerce default output
        }

        // ✅ For other product types, let WooCommerce handle it
        return;
    }
}
