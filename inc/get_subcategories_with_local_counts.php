<?php
function my_get_subcategories_with_local_counts(array $allowed_cat_slugs = [])
{
    $queried = get_queried_object();

    // Check if current archive is a product category and allowed
    if (
        ! $queried instanceof WP_Term ||
        $queried->taxonomy !== 'product_cat' ||
        ! empty($allowed_cat_slugs) && ! in_array($queried->slug, $allowed_cat_slugs, true)
    ) {
        return [];
    }

    // Now we have a valid product_cat term within allowed slugs or no restrictions

    // Get direct subcategories of current category
    $subcategories = get_terms([
        'taxonomy'   => 'product_cat',
        'parent'     => $queried->term_id,
        'hide_empty' => false, // we'll filter manually
        'orderby'    => 'menu_order',
        'order'      => 'ASC',
    ]);

    if (empty($subcategories)) {
        return [];
    }

    // Get products in current category only (no children)
    $product_ids = wc_get_products([
        'status'    => 'publish',
        'limit'     => -1,
        'return'    => 'ids',
        'tax_query' => [[
            'taxonomy'         => 'product_cat',
            'field'            => 'term_id',
            'terms'            => $queried->term_id,
            'include_children' => true,
        ]],
    ]);

    if (empty($product_ids)) {
        return [];
    }

    $counts = [];

    foreach ($product_ids as $pid) {
        $terms = wp_get_post_terms($pid, 'product_cat', ['fields' => 'ids']);
        foreach ($subcategories as $sub) {
            if (in_array($sub->term_id, $terms, true)) {
                $counts[$sub->term_id] = ($counts[$sub->term_id] ?? 0) + 1;
            }
        }
    }

    $out = [];
    foreach ($subcategories as $sub) {
        if (! empty($counts[$sub->term_id])) {
            $out[] = [
                'term'  => $sub,
                'count' => $counts[$sub->term_id],
            ];
        }
    }

    // usort($out, fn($a, $b) => strcmp($a['term']->name, $b['term']->name));

    return $out;
}
