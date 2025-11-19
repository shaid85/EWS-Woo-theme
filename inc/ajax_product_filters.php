<?php
add_action('wp_ajax_filter_products', 'ews_ajax_filter_products');
add_action('wp_ajax_nopriv_filter_products', 'ews_ajax_filter_products');

function ews_ajax_filter_products()
{
    $product_show = get_field('product_show', 'option');
    // Check if the request is valid
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    // initialize the query arguments
    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $product_show,
        'meta_query' => [],
        'tax_query' => ['relation' => 'AND'],
        'paged' => $paged,
    ];

    /* 1️⃣ keep the archive term ALWAYS */
    if (! empty($_POST['taxonomy']) && ! empty($_POST['term_id'])) {
        $args['tax_query'][] = [
            'taxonomy' => sanitize_text_field($_POST['taxonomy']),
            'field'    => 'term_id',
            'terms'    => intval($_POST['term_id']),
        ];
    }

    // 2️⃣ Apply discount filter (optional, only if passed)
    $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;

    // ✅ Add filter by kategorija if exists in URL
    if (isset($_POST['param_cat']) && !empty($_POST['param_cat'])) {

        $param_cat_slug = sanitize_text_field($_POST['param_cat']);

        $args['tax_query'][] = [
            'taxonomy' => 'product_cat', // ← change to your taxonomy if needed
            'field'    => 'slug',
            'terms'    => $param_cat_slug,
        ];
    }

    $discounted_ids = get_discounted_product_ids($discount);
    if ($discount > 0) {
        if (!empty($discounted_ids)) {
            $args['post__in'] = $discounted_ids;
        } else {
            // Force no results
            $args['post__in'] = [0];
        }
    }

    // 3. Handle search
    if (!empty($_POST['search'])) {
        $args['s'] = sanitize_text_field($_POST['search']);
    }

    // Price min+max
    if (!empty($_POST['min_price']) || !empty($_POST['max_price'])) {
        $min = !empty($_POST['min_price']) ? floatval($_POST['min_price']) : 0;
        $max = !empty($_POST['max_price']) ? floatval($_POST['max_price']) : 400;

        $args['meta_query'][] = [
            'key' => '_price',
            'value' => [$min, $max],
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN',
        ];
    }

    // cat
    if (!empty($_POST['sub_cat'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => array_map('sanitize_text_field', $_POST['sub_cat']),
        ];
    }

    // Brands
    if (!empty($_POST['brands'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_brand',
            'field' => 'slug',
            'terms' => array_map('sanitize_text_field', $_POST['brands']),
        ];
    }

    /* -----------------------------------------------------------
 *  Dynamic attribute filtering
 *  (replaces hard‑coded Size / Weight blocks)
 * ---------------------------------------------------------- */

    // 1. Get all registered global attributes.
    $all_attrs = wc_get_attribute_taxonomies();   // returns objects with ->attribute_name

    foreach ($all_attrs as $attr) {

        $slug      = $attr->attribute_name;          // 'dydis', 'weight', 'color' …
        $taxonomy  = 'pa_' . $slug;                  // 'pa_dydis', 'pa_weight', 'pa_color'

        // 2. Check if this attribute key is present in POST (e.g. dydis[]=M,L)
        if (! empty($_POST[$slug])) {

            // 3. Sanitize incoming values (array of slugs)
            $terms = array_map('sanitize_text_field', (array) $_POST[$slug]);

            // 4. Push a tax_query clause
            $args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $terms,
            ];
        }
    }

    // Sorting
    if (!empty($_POST['sort_by'])) {
        switch ($_POST['sort_by']) {
            case 'popularity':
                $args['meta_key'] = 'total_sales';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;

            case 'lowprice':
                $args['meta_key'] = '_price';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'ASC';
                break;

            case 'highprice':
                $args['meta_key'] = '_price';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;

            case 'date_desc':
            default:
                $args['orderby'] = 'date';
                $args['order']   = 'DESC';
                break;
        }
    }



    // Collection
    // if (!empty($_POST['collection'])) {
    //     $args['tax_query'][] = [
    //         'taxonomy' => 'pa_collection',
    //         'field' => 'slug',
    //         'terms' => $_POST['collection'],
    //     ];
    // }


    // // Size
    // if (!empty($_POST['dydis'])) {
    //     $args['tax_query'][] = [
    //         'taxonomy' => 'pa_dydis',
    //         'field' => 'slug',
    //         'terms' => $_POST['dydis'],
    //     ];
    // }

    // // Weight (custom field key: _weight)
    // if (!empty($_POST['weight'])) {
    //     $args['tax_query'][] = [
    //         'taxonomy' => 'pa_weight',
    //         'field' => 'slug',
    //         'terms' => $_POST['weight'],
    //     ];
    // }




    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            global $product;
?>
            <div class="col-md-4">
                <?php echo ewsdev_product_part_html($product); ?>
                <!-- end single product -->
            </div>
<?php
        endwhile;

    else:
        echo '<p>Nerasta jokių produktų.</p>';
    endif;
    $html = ob_get_clean();

    // Pagination
    $pagination_html = '';

    $pagination_links = paginate_links([
        'total'   => $query->max_num_pages,
        'current' => $paged,
        'type'    => 'array',
        'prev_text' => '', // remove default text to avoid fallback
        'next_text' => '', // same here
    ]);

    if (!empty($pagination_links)) {
        $pagination_html .= '<div class="pagination filter_products">';

        foreach ($pagination_links as $link) {
            // Custom NEXT
            if (strpos($link, 'next') !== false) {
                if (preg_match('/page\/(\d+)/', $link, $matches)) {
                    $page_num = $matches[1];
                    $pagination_html .= '<a href="#Top" class="next page-numbers" data-page="' . esc_attr($page_num) . '">
                    <img src="' . get_stylesheet_directory_uri() . '/assets/images/arrow-right-black.png" alt="Next" />
                </a>';
                }
            }
            // Custom PREV
            elseif (strpos($link, 'prev') !== false) {
                if (preg_match('/page\/(\d+)/', $link, $matches)) {
                    $page_num = $matches[1];
                    $pagination_html .= '<a href="#Top" class="prev page-numbers" data-page="' . esc_attr($page_num) . '">
                    <img src="' . get_stylesheet_directory_uri() . '/assets/images/arrow-left-black.png" alt="Previous" />
                </a>';
                }
            }
            // Numeric page links
            else {
                if (preg_match('/page\/(\d+)/', $link, $matches)) {
                    $page_num = $matches[1];
                    $link = str_replace('<a ', '<a href="#Top" data-page="' . esc_attr($page_num) . '" ', $link);
                }
                $pagination_html .= $link;
            }
        }

        $pagination_html .= '</div>';
    }


    wp_send_json([
        'html' => $html,
        'count' => 'Rodoma: ' . $query->post_count . ' iš ' . $query->found_posts,
        'pagination' => $pagination_html,
    ]);
}
