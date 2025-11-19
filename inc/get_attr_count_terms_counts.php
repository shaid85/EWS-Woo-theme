<?php

/**
 * Return array of [ WP_Term $term, int $count ] where $count
 * is the number of products in *this archive* that have that term.
 *
 * @param string $attr_slug  e.g. 'pa_dydis'
 * @return array            array of arrays [ 'term' => WP_Term, 'count' => int ]
 */
function my_get_attr_terms_with_local_counts($attr_slug)
{

    $archive = get_queried_object();
    if (! $archive instanceof WP_Term) {
        return [];
    }

    // 1. Product IDs in the current archive
    $product_ids = wc_get_products([
        'status'    => 'publish',
        'limit'     => -1,
        'return'    => 'ids',
        'tax_query' => [
            [
                'taxonomy' => $archive->taxonomy,
                'field'    => 'term_id',
                'terms'    => $archive->term_id,
            ],
        ],
    ]);

    if (! $product_ids) {
        return [];
    }

    // 2. Build counts [ term_id => n ]
    $counts = [];

    foreach ($product_ids as $pid) {
        $term_ids = wc_get_product_terms($pid, $attr_slug, ['fields' => 'ids']);
        foreach ($term_ids as $tid) {
            $counts[$tid] = isset($counts[$tid]) ? $counts[$tid] + 1 : 1;
        }
    }

    if (! $counts) {
        return [];
    }

    // 3. Fetch term objects and attach counts
    $terms = get_terms([
        'taxonomy'   => $attr_slug,
        'include'    => array_keys($counts),
        'hide_empty' => false,
    ]);

    $out = [];
    foreach ($terms as $t) {
        $out[] = [
            'term'  => $t,
            'count' => $counts[$t->term_id],
        ];
    }

    // optional: sort alphabetically
    usort($out, fn($a, $b) => strcmp($a['term']->name, $b['term']->name));

    return $out;
}
