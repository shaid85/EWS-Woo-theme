<?php
// Add EAN & Cost columns in Products list
add_filter('manage_edit-product_columns', function ($columns) {
    $new_columns = [];

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        // Insert custom columns after "Price"
        if ($key === 'price') {
            $new_columns['cost_of_goods'] = __('Savikaina', 'ewsdev');
            $new_columns['ean_code'] = __('EAN kodas', 'ewsdev');
        }
    }

    return $new_columns;
});

// Populate the EAN and Cost columns
add_action('manage_product_posts_custom_column', function ($column, $post_id) {
    $product = wc_get_product($post_id);

    if (!$product) {
        return;
    }

    // ---- EAN Code ----
    if ($column === 'ean_code') {
        $ean_display = '—';

        if ($product->is_type('variable')) {
            $variations = $product->get_children();
            $eans = [];

            foreach ($variations as $variation_id) {
                $ean = get_post_meta($variation_id, '_global_unique_id', true);
                if ($ean) {
                    $eans[] = $ean;
                }
            }

            if (!empty($eans)) {
                // Join all EANs with commas (change to "<br>" for line breaks)
                $ean_display = implode(', ', $eans);
            }
        } else {
            $ean = get_post_meta($post_id, '_global_unique_id', true);
            if ($ean) {
                $ean_display = $ean;
            }
        }

        echo esc_html($ean_display);
    }

    // ---- Cost of Goods ----
    if ($column === 'cost_of_goods') {
        $cost_display = '—';

        if ($product->is_type('variable')) {
            $variations = $product->get_children();
            $costs = [];

            foreach ($variations as $variation_id) {
                $cost = get_post_meta($variation_id, '_cost_of_goods', true);
                if ($cost) {
                    $costs[] = $cost;
                }
            }

            if (!empty($costs)) {
                // Show comma-separated cost values
                $cost_display = implode(', ', $costs);
            }
        } else {
            $cost = get_post_meta($post_id, '_cost_of_goods', true);
            if ($cost) {
                $cost_display = $cost;
            }
        }

        echo esc_html($cost_display);
    }
}, 10, 2);



// Add custom CSS (if layout still breaks)
add_action('admin_head', 'fix_admin_table_overflow');
function fix_admin_table_overflow()
{
    echo '<style>
        .wp-list-table.widefat.fixed {
            table-layout: auto;
        }
        .column-cost_of_goods {
            width: 5%;
        }
        .column-ean_code {
            width: 8%;
        }
    </style>';
}
