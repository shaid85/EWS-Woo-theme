<?php

/**
 * Return terms of $target_taxonomy that appear in products
 * inside the current archive (category, tag, brand…).
 *
 * @param string $target_taxonomy e.g. 'product_brand' or 'pa_color'
 * @return WP_Term[] array of term objects
 */
function my_get_available_terms_for_archive($target_taxonomy)
{
    $current = get_queried_object(); // WP_Term on any product archive
    if (! $current instanceof WP_Term) {
        return [];
    }

    // Set up blacklist for 'apranga' category
    $blacklist = [];
    if ($current->taxonomy === 'product_cat' && $current->slug === 'apranga') {
        $blacklist = ['vyriskos-kojines', 'moteriskos-kojines', 'aksesuarai-apranga', 'test'];
    }

    // Convert blacklist slugs to term IDs
    $blacklist_ids = [];
    if (!empty($blacklist)) {
        foreach ($blacklist as $slug) {
            $term = get_term_by('slug', $slug, 'product_cat');
            if ($term instanceof WP_Term) {
                $blacklist_ids[] = $term->term_id;
            }
        }
    }

    // 1. Pull all product IDs that belong to this archive, excluding blacklisted subcategories
    $tax_query = [
        [
            'taxonomy' => $current->taxonomy,
            'field'    => 'term_id',
            'terms'    => $current->term_id,
        ]
    ];

    if (!empty($blacklist_ids)) {
        $tax_query[] = [
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $blacklist_ids,
            'operator' => 'NOT IN',
        ];
    }

    $product_ids = wc_get_products([
        'status'    => 'publish',
        'limit'     => -1,
        'return'    => 'ids',
        'tax_query' => $tax_query,
    ]);

    if (empty($product_ids)) {
        return [];
    }

    // 2. Collect the target taxonomy term IDs used by those products
    $term_ids = [];
    foreach ($product_ids as $pid) {
        $ids = wc_get_product_terms($pid, $target_taxonomy, ['fields' => 'ids']);
        if ($ids) {
            $term_ids = array_merge($term_ids, $ids);
        }
    }

    $term_ids = array_unique($term_ids);
    if (empty($term_ids)) {
        return [];
    }

    // 3. Return the term objects
    return get_terms([
        'taxonomy'   => $target_taxonomy,
        'include'    => $term_ids,
        'hide_empty' => false,
        'orderby'    => 'menu_order',
        'order'      => 'ASC',
    ]);
}


// Search - attributes
if (! function_exists('my_get_available_terms_for_search')) {
    function my_get_available_terms_for_search($taxonomy)
    {
        global $wp_query;

        // Get all product IDs from current query
        $product_ids = wp_list_pluck($wp_query->posts, 'ID');

        if (empty($product_ids)) return [];

        // Get terms used by those products for this attribute
        $terms = wp_get_object_terms($product_ids, $taxonomy, [
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => true,
        ]);

        return $terms;
    }
}

// discount - attributes
if (! function_exists('my_get_available_terms_for_discount')) {
    function my_get_available_terms_for_discount($taxonomy, $discount)
    {
        global $wp_query;

        // Get all product IDs from current query
        $product_ids = get_discounted_product_ids($discount);

        if (empty($product_ids)) return [];

        // Get terms used by those products for this attribute
        $terms = wp_get_object_terms($product_ids, $taxonomy, [
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => true,
        ]);

        return $terms;
    }
}
