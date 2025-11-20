<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined('ABSPATH') || exit;

get_header('shop');
?>
<?php
$term_id = 0; // Initialize it with a default

$current_cat = get_queried_object();
if ($current_cat && !empty($current_cat->term_id)) {
    $term_id = $current_cat->term_id;
}


$discount = isset($_GET['discount']) ? floatval($_GET['discount']) : 0;
if ($discount > 0) {
    $args['post__in'] = get_discounted_product_ids($discount);
}

if (isset($_GET['kategorija']) && !empty($_GET['kategorija'])) {
    // ✅ Add filter by kategorija if exists in URL

    $kategorija_slug = sanitize_text_field($_GET['kategorija']);

?>
    <script>
        (function($) {
            Cookies.remove('min_price');
            Cookies.remove('max_price');
            Cookies.remove('product_filter_data');
        })(jQuery);
    </script>

<?php
}
if (isset($_GET['raketes-vedlys'])) {
?>
    <style>
        .cat_details,
        .sub_cat_card,
        .cat_header {
            display: none !important;
        }

        .vedlys_header.d-none {
            display: block !important;
            margin-top: 162px;
        }
    </style>
<?php
}

if ($term_id) {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $term_id,
            ),
        ),
        'meta_query' => array(
            array(
                'key'     => '_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'NUMERIC'
            )
        )
    );
} else {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'post__in'         => get_discounted_product_ids($discount),

        'meta_query' => array(
            array(
                'key'     => '_price',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'NUMERIC'
            )
        )
    );
}



$query = new WP_Query($args);
$product_ids = $query->posts;

$prices = [];

foreach ($product_ids as $product_id) {
    $price = get_post_meta($product_id, '_price', true);
    if ($price !== '') {
        $prices[] = floatval($price);
    }
}

$min_price = !empty($prices) ? floor(min($prices)) : 1;
$max_price = !empty($prices) ? ceil(max($prices)) : 400;
?>
<script>
    let categoryPriceRange = {
        min: <?php echo $min_price; ?>,
        max: <?php echo $max_price; ?>
    };
    let dicountValue = <?php echo $discount; ?>;
</script>



<div class="container">
    <div class="cat_header text-center p-40">
        <?php
        /**
         * Hook: woocommerce_before_main_content.
         *
         * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
         * @hooked woocommerce_breadcrumb - 20
         * @hooked WC_Structured_Data::generate_website_data() - 30
         */
        do_action('woocommerce_before_main_content'); // show archive breadcrumbs 

        /**
         * Hook: woocommerce_shop_loop_header.
         *
         * @since 8.6.0
         *
         * @hooked woocommerce_product_taxonomy_archive_header - 10
         */
        // do_action('woocommerce_shop_loop_header'); // show archive title
        ?>

        <?php if ($discount > 0) { ?>
            <header class="woocommerce-products-header">
                <h1 class="woocommerce-products-header__title page-title">išpardavimas</h1>
            </header>
        <?php
        } else {
            do_action('woocommerce_shop_loop_header'); // show archive title
        }
        ?>

    </div>

    <div class="sub_cat_card">
        <?php
        if (is_product_category(array('padelio-raketes', 'tenisas'))) {
            $current_cat = get_queried_object();

            $args = array(
                'taxonomy'     => 'product_cat',
                'parent'       => $current_cat->term_id,
                'hide_empty'   => false, // Change to true if you only want categories that have products
                'number'       => 4, // ✅ LIMIT to 4 subcategories
            );

            $subcategories = get_terms($args);

            if (!empty($subcategories) && !is_wp_error($subcategories)) {
                echo '<div class="row subcategory-grid mt-5">';
                foreach ($subcategories as $subcategory) {
                    $thumbnail_id = get_term_meta($subcategory->term_id, 'thumbnail_id', true);
                    $image_url = wp_get_attachment_url($thumbnail_id);
                    if (empty($image_url)) {
                        $image_url = "https://tennis.urbanlabs.lt/wp-content/uploads/2025/04/image-wrap-1.jpg";
                    }

        ?>
                    <div class="col-sm-6 col-md-3">
                        <div class="cat_box sub_catbox">
                            <a href="<?php echo esc_url(get_term_link($subcategory)); ?>">
                                <div class="f_image" style="background-image: url('<?php echo esc_url($image_url); ?>');">

                                    <h2 class="text">
                                        <?php echo esc_html($subcategory->name); ?>
                                    </h2>

                                </div>
                            </a>

                        </div>
                    </div>

        <?php
                }
                echo '</div>'; // .row
            }
        }
        ?>

    </div>

</div>

<div class="container">
    <div class="vedlys_header mb-5 d-none text-center p-40" style="background-color: #FAFAF5;">
        <h2 class="ews_title text-center">
            Tinkamiausios raketės
        </h2>
        <p class="mb-0">Raketės vedlio kruopščiai parinkti modeliai, geriausiai atitinkantys poreikius ir žaidimo stilių</p>
    </div>
</div>


<?php
if (woocommerce_product_loop()) {

    /**
     * Hook: woocommerce_before_shop_loop.
     *
     * @hooked woocommerce_output_all_notices - 10
     * @hooked woocommerce_result_count - 20
     * @hooked woocommerce_catalog_ordering - 30
     */
    // do_action('woocommerce_before_shop_loop');
?>

    <div class="row product-filter_wrap">
        <div class="col-md-3">
            <div class="d-block d-md-none">
                <div class="d-flex justify-content-between flex-wrap">
                    <!-- Result Container -->
                    <div id="results-count-mobile" class="mb-3 order-2 order-md-0">
                        <?php
                        if ($query->have_posts()) :
                            echo 'Rodoma: ' . $query->post_count . ' iš ' . $query->found_posts;
                        endif;
                        ?>
                    </div>
                    <div id="filterbtn_wrap" class="d-md-none d-block">
                        <a id="filterbtn" class="filterbtn" href="javascript:void(0);">Filtrai
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-filter.png" width="20" class="ms-1" alt="Filtruoti">
                        </a>
                    </div>
                    <!-- Sort By (outside the form) -->
                    <div class="filter_sort mb-3 custom">
                        <select class="form-select w-100" id="sort-by-select" name="sort_by">
                            <option value="date_desc"><?php _e('Naujausi', 'ewsdev'); ?></option>
                            <option value="popularity"><?php _e('Populiariausi', 'ewsdev'); ?></option>
                            <option value="lowprice"><?php _e('Mažiausia kaina', 'ewsdev'); ?></option>
                            <option value="highprice"><?php _e('Didžiausia kaina', 'ewsdev'); ?></option>
                        </select>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const toggleBtn = document.getElementById('filterbtn');
                    const filterPanel = document.getElementById('product-filter');

                    toggleBtn?.addEventListener('click', function() {
                        filterPanel.classList.toggle('active');
                        // optional: change button text or icon
                        if (filterPanel.classList.contains('active')) {
                            toggleBtn.innerHTML = `<img src="<?php echo get_template_directory_uri(); ?>/assets/images/close.png" width="20" class="me-0" alt="Close"> Uždaryti`;
                        } else {
                            toggleBtn.innerHTML = `Filtrai
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-filter.png" width="20" class="ms-1" alt="Filtruoti">`;
                        }
                    });
                });
            </script>
            <style>
                /* Mobile hidden by default */
                @media (max-width: 767px) {
                    #product-filter {
                        max-height: 0;
                        overflow: hidden;
                        opacity: 0;
                        transition: all 0.4s ease;
                    }

                    #product-filter.active {
                        max-height: 1000px;
                        /* enough for full filters */
                        opacity: 1;
                        margin-bottom: 1rem;
                    }
                }
            </style>
            <div id="product-filter" class="container px-0 px-md-2 mb-4">
                <form id="ajax-filter-form">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="filter_box">
                                <h2 class="nr-head open">Pasirinkti filtrai <span class="filter_count">0</span></h2>
                                <div class="box_content">
                                    <div id="active-filters" class="mb-3"></div>
                                    <a id="clear-filters" class="clear_all"><img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/atstatyti.png" width="20" alt=""> </a>
                                </div>

                            </div>
                        </div>


                        <!-- Price Range -->
                        <div class="col-12 mb-3">
                            <!-- Accordion -->
                            <div class="filter_box">
                                <div class="accordion-group">
                                    <h2 class="fil-head open"><?php _e('Kaina', 'ewsdev'); ?></h2>
                                    <div class="fil-body open">
                                        <div class="price-inputs my-3 d-flex">
                                            <div class="position-relative">
                                                <aside class="euro_icon position-absolute"><?php echo get_woocommerce_currency_symbol(); ?></aside>
                                                <input type="number" id="min_price" name="min_price" class="form-control d-inline " value="0">
                                            </div>

                                            <span class="mx-2">–</span>

                                            <div class="position-relative">
                                                <aside class="euro_icon position-absolute"><?php echo get_woocommerce_currency_symbol(); ?></aside>
                                                <input type="number" id="max_price" name="max_price" class="form-control d-inline " value="400">
                                            </div>
                                        </div>
                                        <div id="price-range-slider"></div>

                                    </div>
                                </div><!-- End Accordion -->
                            </div>
                        </div>

                        <?php
                        if (isset($_GET['discount'])) {
                        ?>
                            <!-- Discount Range -->
                            <div class="d-none col-12 mb-3 ">
                                <!-- Accordion -->
                                <div class="filter_box">
                                    <div class="accordion-group">
                                        <h2 class="fil-head open"><?php _e('Discount', 'ewsdev'); ?></h2>
                                        <div class="fil-body open">
                                            <div class="discount_range">
                                                <!-- discount -->
                                                <input type="number" id="discount_range" name="discount" class="form-control d-inline  w-50 mb-3 ps-3" value="0">
                                                <span id="discount_value">%</span>
                                            </div>
                                            <div id="discount-slider"></div>
                                        </div>
                                    </div><!-- End Accordion -->
                                </div>
                            </div>

                            <script>
                                (function($) {
                                    Cookies.remove('min_price');
                                    Cookies.remove('max_price');
                                    Cookies.remove('product_filter_data');
                                })(jQuery);
                                document.addEventListener('DOMContentLoaded', function() {
                                    const urlParams = new URLSearchParams(window.location.search);
                                    const discount = urlParams.get('discount');

                                    if (discount && !isNaN(discount)) {
                                        const input = document.getElementById('discount_range');
                                        if (input) {
                                            input.value = discount;
                                        }
                                    }
                                });
                            </script>

                        <?php
                        }
                        ?>
                        <?php
                        // Only show subcategories if current category slug is one of these:
                        $allowed_slugs = [
                            'raketes',
                            'raketes-stalo-teniso-raketes',
                            'tenisas',
                            'kamuoliukai',
                            'avalyne',
                            'krepsiai-ir-kuprines',
                            'rakeciu-aksesuarai',
                            'kitos-prekes',
                        ];
                        $sub_cats = my_get_subcategories_with_local_counts($allowed_slugs);

                        if ($sub_cats):

                            // Your fixed whitelist of subcategory slugs to show
                            $fixed_sub_cat_slugs = [
                                'jaunimui',
                                'padelis',
                                'papludimio-teniso-raketes',
                                'tenisas',
                                'pikebolas',
                                'piklbolas',
                                'stalo-tenisas',
                                '',
                                'teniso-kamuoliukai',
                                'padelio-kamuoliukai',
                                'papludimio-teniso-kamuoliukai',
                                'pikebolas-kamuoliukai',
                                'piklbolas-kamuoliukai',
                                'stalo-tenisas-kamuoliukai',
                                '',
                                'tenisas-avalyne',
                                'padelis-avalyne',
                                '',
                                'teniso-krepsiai-ir-kuprines',
                                'padelio-krepsiai-ir-kuprines',
                                'papludimio-teniso-krepsiai',
                                'pikebolas-krepsiai-ir-kuprines',
                                'piklbolas-krepsiai-ir-kuprines',
                                'stalo-tenisas-krepsiai-ir-kuprines',
                                '',
                                'pallet-rack-protection',
                                'tenisas-rakeciu-aksesuarai',
                                'papludimio-tenisas-rakeciu-aksesuarai',
                                'piklbolas-rakeciu-aksesuarai',
                                '',
                                'tenisas-kitos-prekes',
                                'padelis-kitos-prekes',
                                'papludimio-tenisas',
                                'pikebolas-kitos-prekes',
                                'piklbolas-kitos-prekes',
                                'stalo-tenisas-kitos-prekes',
                            ];

                            // Filter $sub_cats to only include subcategories with slug in fixed whitelist
                            $filtered_sub_cats = array_filter($sub_cats, function ($item) use ($fixed_sub_cat_slugs) {
                                return in_array($item['term']->slug, $fixed_sub_cat_slugs, true);
                            });
                            if (!empty($filtered_sub_cats)) :
                        ?>
                                <!-- Sub Cat -->
                                <div class="col-md-12 mb-3">
                                    <!-- Accordion -->
                                    <div class="filter_box ">
                                        <div class="accordion-group">
                                            <h2 class="fil-head open"><?php _e('Sporto šaka', 'ewsdev'); ?></h2>
                                            <div class="fil-body open">
                                                <?php // checkbox for categories
                                                //    foreach ($filtered_sub_cats as $item) {
                                                //       $catItem = $item['term'];
                                                //        $count = $item['count'];
                                                //         echo '<div class="form-check custom">

                                                //   <label class="form-check-label custom-checkbox" for="cat_' . $catItem->term_id . '">
                                                //   <input class="form-check-input" type="checkbox" name="sub_cat[]" value="' . esc_attr($catItem->slug) . '" id="cat_' . $catItem->term_id . '">
                                                //   <span class="checkmark"></span>' . esc_html($catItem->name) . ' (' . $count . ')</label>
                                                // </div>';
                                                //                                 }
                                                ?>
                                                <?php // link for categories
                                                foreach ($filtered_sub_cats as $item) {
                                                    $catItem = $item['term'];
                                                    $count = $item['count'];
                                                    $catUrl = get_term_link($catItem); // Generate category link
                                                ?>
                                                    <div class="form-check custom">
                                                        <labal>
                                                            <a href="<?php echo esc_html($catUrl); ?>" class="text-black d-flex gap-2">
                                                                <?php echo esc_html($catItem->name); ?>
                                                                <span><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/east.png" class="p-0" width="14" alt="Next" /></span>
                                                            </a>
                                                        </labal>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div><!-- End filter_box -->
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>


                        <?php
                        // Recursive function to get all descendant term IDs
                        function get_all_child_terms($parent_id, $taxonomy)
                        {
                            $children = get_terms(array(
                                'taxonomy'   => $taxonomy,
                                'hide_empty' => false,
                                'parent'     => $parent_id,
                                'fields'     => 'ids'
                            ));

                            $all_children = $children;
                            foreach ($children as $child_id) {
                                $all_children = array_merge($all_children, get_all_child_terms($child_id, $taxonomy));
                            }

                            return $all_children;
                        }

                        $current_cat = get_queried_object();
                        $parent_cat  = get_term_by('slug', 'apranga', 'product_cat');

                        $all_cats = array_merge(
                            array($parent_cat->term_id),                     // parent
                            get_all_child_terms($parent_cat->term_id, 'product_cat') // all descendants
                        );


                        if ($current_cat instanceof WP_Term && isset($current_cat->term_id)) :
                            if (in_array($current_cat->term_id, $all_cats)) :


                                // Get the term object by slug
                                $parent_term = get_term_by('slug', 'apranga', 'product_cat');

                                if ($parent_term && !is_wp_error($parent_term)):

                                    $parent_id = $parent_term->term_id;
                        ?>
                                    <!-- Lytis as cat -->
                                    <div class="col-md-12 mb-3">
                                        <!-- Accordion -->

                                        <div id="Lytis" class="filter_box">
                                            <div class="accordion-group">
                                                <h2 class="fil-head open"><?php _e('Lytis', 'ewsdev'); ?></h2>
                                                <div class="fil-body mh_none open">
                                                    <?php
                                                    $taxonomy = 'product_cat';
                                                    $args = array(
                                                        'taxonomy'   => $taxonomy,
                                                        'hide_empty' => 0,
                                                        'parent'     => $parent_id
                                                    );

                                                    $all_categories = get_terms($args);

                                                    $allowed_slugs = array('berniuku-apranga', 'mergaiciu-apranga', 'moteriska-apranga', 'vyriska-apranga', 'berniukai', 'mergaites', 'moterys-apranga', 'vyrai');


                                                    foreach ($all_categories as $category) {
                                                        // Only include categories in the allowed list
                                                        if (!in_array($category->slug, $allowed_slugs)) {
                                                            continue;
                                                        } ?>
                                                        <!-- Accordion -->
                                                        <div class="faq_box">
                                                            <div class="faq-item">
                                                                <button class="faq-question border-0"><?php echo esc_html($category->name); ?></button>
                                                                <div class="faq-answer border-0">
                                                                    <?php
                                                                    // Get subcategories of the current category
                                                                    $sub_args = array(
                                                                        'taxonomy'   => $taxonomy,
                                                                        'hide_empty' => 0,
                                                                        'parent'     => $category->term_id
                                                                    );
                                                                    $sub_categories = get_terms($sub_args);

                                                                    if (!empty($sub_categories)) {

                                                                        foreach ($sub_categories as $sub_category) {
                                                                            $catUrl = get_term_link($sub_category->term_id);
                                                                    ?>
                                                                            <!-- <div class="form-check custom">
                                                                            <label class="form-check-label custom-checkbox" for="cat_<?php // echo $sub_category->term_id; 
                                                                                                                                        ?>">
                                                                                <input class="form-check-input" type="checkbox" name="sub_cat[]" value="<?php // echo esc_html($sub_category->slug); 
                                                                                                                                                        ?>" id="cat_<?php // echo $sub_category->term_id; 
                                                                                                                                                                    ?>">
                                                                                <span class="checkmark"></span>
                                                                                <?php // echo esc_html($sub_category->name); 
                                                                                ?>
                                                                            </label>
                                                                        </div> -->
                                                                            <div class="form-check custom">
                                                                                <labal>
                                                                                    <a href="<?php echo esc_html($catUrl); ?>" class="text-black d-flex gap-2">
                                                                                        <?php echo esc_html($sub_category->name); ?>
                                                                                        <span><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/east.png" class="p-0" width="14" alt="Next" /></span>
                                                                                    </a>
                                                                                </labal>
                                                                            </div>
                                                                    <?php

                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }

                                                    ?>

                                                </div>
                                            </div> <!-- End Accordion -->
                                        </div><!-- End filter_box -->
                                    </div>

                        <?php
                                endif;
                            endif;
                        endif;
                        ?>

                        <?php
                        //Get al attributes - 
                        // custom order plugin under - WooCommerce -> Attribute order
                        $all_attrs = wc_get_attribute_taxonomies();
                        $attribute_order = get_option('my_attribute_sort_order', []);

                        usort($all_attrs, function ($a, $b) use ($attribute_order) {
                            $a_index = array_search($a->attribute_name, $attribute_order);
                            $b_index = array_search($b->attribute_name, $attribute_order);
                            return ($a_index === false ? PHP_INT_MAX : $a_index) - ($b_index === false ? PHP_INT_MAX : $b_index);
                        });

                        foreach ($all_attrs as $attr) :

                            // For hide filter item - work 
                            $current_cat = get_queried_object();
                            if (
                                ($current_cat instanceof WP_Term && $current_cat->taxonomy == 'product_brand')
                                || is_tax('product_tag')
                                || isset($_GET['discount'])
                            ) {
                                continue;
                            }

                            // print_r($attr);
                            $taxonomy = 'pa_' . $attr->attribute_name;            // pa_dydis
                            $terms    = my_get_available_terms_for_archive($taxonomy);

                            if (isset($_GET['discount'])) {
                                $terms = my_get_available_terms_for_discount($taxonomy, $discount);
                            }
                            $orderby = $attr->attribute_orderby;
                            $show_public = $attr->attribute_public;
                            // Decide whether accordion should be open
                            $is_open = ($orderby === 'name');
                            // $is_open = ($show_public == '1');
                            // Assign open/closed class
                            $accordion_class = $is_open ? 'custom_accordion open' : 'custom_accordion';
                            // Black-list
                            $exclude_taxonomies = [
                                'pa_savikaina',
                                // 'pa_gamintojas',
                                // 'pa_amzius',
                                'pa_amzius-pagal-metus',
                                'pa_paslauga',
                                'pa_raketes-modelis',
                                'pa_kamuoliukai-vaikams',
                                'pa_avalynes-tipas',
                            ];

                            if (! $terms || in_array($taxonomy, $exclude_taxonomies, true)) {
                                continue;
                            }

                            // Black-list
                            $black_list_apranga = [
                                'pa_kategorija',
                            ];
                            // For hide filter item
                            $current_cat = get_queried_object();
                            if ($current_cat->slug == 'apranga' && in_array($taxonomy, $black_list_apranga, true)) {
                                continue;
                            }

                            // Black-list
                            $black_list_padelis = [
                                'pa_storis-mm',
                                'pa_ilgis-in',
                                'pa_svoris-g',
                                'pa_raketes-forma',
                                'pa_padelio-kategorija',
                            ];
                            // For hide filter item
                            $current_cat = get_queried_object();
                            if ($current_cat->slug == 'padelis' && in_array($taxonomy, $black_list_padelis, true)) {
                                continue;
                            }

                            // Black-list
                            $black_list_avalyne = [
                                'pa_dydis',
                                'pa_lytis-2',
                            ];
                            // For hide filter item
                            $current_cat = get_queried_object();
                            if ($current_cat->slug == 'avalyne' && in_array($taxonomy, $black_list_avalyne, true)) {
                                continue;
                            }

                            // Black-list
                            $black_list_aksesuarai = [
                                'pa_storis-mm',
                                'pa_svoris-g',
                            ];
                            // For hide filter item 
                            $current_cat = get_queried_object();
                            if ($current_cat->slug == 'rakeciu-aksesuarai' && in_array($taxonomy, $black_list_aksesuarai, true)) {
                                continue;
                            }

                            // Black-list
                            $black_list_kuprines = [
                                'pa_talpa-l',
                            ];
                            // For hide filter item
                            $current_cat = get_queried_object();
                            if ($current_cat->slug == 'krepsiai-ir-kuprines' && in_array($taxonomy, $black_list_kuprines, true)) {
                                continue;
                            }

                            // Black-list
                            $exclude_taxonomies2 = [
                                'pa_lygis',
                                'pa_ilgis-mm',
                                'pa_plotis-mm',
                                'pa_talpa-l',
                                'pa_storis-mm',
                                'pa_krepsio-tipas',
                                'pa_zaidimo-lygis',
                                'pa_vienetai',
                            ];
                            $current_cat = get_queried_object();
                            if ($current_cat->slug == 'kitos-prekes' && in_array($taxonomy, $exclude_taxonomies2, true)) {
                                continue;
                            }

                            // Black-list
                            $exclude_taxonomies3 = [
                                'pa_dydis',
                                'pa_lytis',
                                'pa_lytis-2',
                                'pa_raketes-forma',
                                'pa_padelio-kategorija',
                                'pa_ko-tikimasi-is-raketes',
                                'pa_kietumas',
                                'pa_pavirsius',
                                'pa_lanko-dydis-cm2',
                                'pa_storis-mm',
                                'pa_balansas-mm',
                                'pa_ilgis-in',
                                'pa_svoris-g',
                                'pa_stygu-tinklas',
                                'pa_sudetis',
                                'pa_tempimas',
                                'pa_lygis',
                                'pa_ilgis-mm',
                                'pa_plotis-mm',

                            ];
                            // For hide filter item
                            $current_cat = get_queried_object();
                            if ($current_cat->slug == 'raketes' && in_array($taxonomy, $exclude_taxonomies3, true)) {
                                continue;
                            }

                            // Black-list
                            $black_list_tenisas = [
                                'pa_dydis',
                                // 'pa_lytis',
                                // 'pa_ko-tikimasi-is-raketes',
                            ];
                            // For hide filter item
                            $current_cat = get_queried_object();
                            if ($current_cat->slug == 'tenisas' && in_array($taxonomy, $black_list_tenisas, true)) {
                                continue;
                            }

                        ?>
                            <!-- Size -->
                            <div class="col-md-12 mb-3 <?php echo $attr->attribute_name; ?>">
                                <!-- Accordion -->
                                <div class="filter_box ">
                                    <div class="accordion-group attr_group " data-attribute="<?php echo $attr->attribute_name; ?>">
                                        <h2 class="fil-head <?php echo $accordion_class; ?>">
                                            <?php echo esc_html($attr->attribute_label); ?>
                                        </h2>
                                        <div class="fil-body <?php echo $accordion_class; ?>">

                                            <?php foreach ($terms as $term) : ?>
                                                <div class="form-check custom">

                                                    <label class="form-check-label custom-checkbox"
                                                        for="<?php echo esc_attr($taxonomy . '-' . $term->term_id); ?>">
                                                        <input class="form-check-input"
                                                            type="checkbox"
                                                            name="<?php echo esc_attr($attr->attribute_name); ?>[]"
                                                            value="<?php echo esc_attr($term->slug); ?>"
                                                            id="<?php echo esc_attr($taxonomy . '-' . $term->term_id); ?>">
                                                        <span class="checkmark"></span>
                                                        <?php echo esc_html($term->name); ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>

                                        </div><!-- /.fil-body -->
                                    </div><!-- /.accordion-group -->
                                </div><!-- /.filter_box -->
                            </div><!-- /.col -->
                        <?php
                        endforeach;
                        ?>
                        <!-- sort_by select is outside -->
                        <input type="hidden" name="sort_by" id="sort_by_hidden" value="<?php echo esc_attr($_GET['sort_by'] ?? 'date_desc'); ?>">

                        <?php
                        $kategorija_slug = isset($_GET['kategorija']) ? sanitize_text_field($_GET['kategorija']) : '';
                        ?>
                        <input type="hidden" id="param_cat" name="param_cat" value="<?php echo esc_attr($kategorija_slug); ?>">

                    </div><!-- End row -->
                </form>
            </div><!-- End product-filter -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const COOKIE_NAME = 'product_filter_data';
                    const savedFilters = Cookies.get(COOKIE_NAME);

                    if (savedFilters) {
                        const data = JSON.parse(savedFilters);
                        const attributes = data.attributes;

                        if (attributes) {
                            // Loop through each .accordion-group
                            document.querySelectorAll('.accordion-group').forEach(group => {
                                const taxName = group.dataset.attribute;

                                if (attributes.hasOwnProperty(taxName)) {

                                    // Add .open to child elements
                                    const filHead = group.querySelector('.fil-head');
                                    const filBody = group.querySelector('.fil-body');

                                    if (filHead) filHead.classList.add('open');
                                    if (filBody) {
                                        filBody.classList.add('open');
                                        filBody.style.maxHeight = 'none'; // <- Here
                                    }
                                }
                            });
                        }
                    }
                });
            </script>

        </div><!-- End column -->
        <div id="Top" class="col-md-9 mb-5">
            <?php
            $product_show = get_field('product_show', 'option');

            $term = get_queried_object();

            $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

            $args = [
                'post_type'      => 'product',
                'post_status'    => 'publish',
                'posts_per_page' => $product_show,
                'paged'          => $paged,
                'meta_query'     => [],
                'tax_query'      => [],
            ];

            // Filter by current category/tag/brand if on archive page
            if (!empty($term->taxonomy)) {
                $args['tax_query'][] = [
                    'taxonomy' => $term->taxonomy,
                    'field'    => 'slug',
                    'terms'    => $term->slug,
                ];
            }

            // ✅ Add filter by kategorija if exists in URL
            if (isset($_GET['kategorija']) && !empty($_GET['kategorija'])) {

                $kategorija_slug = sanitize_text_field($_GET['kategorija']);

                $args['tax_query'][] = [
                    'taxonomy' => 'product_cat', // ← change to your taxonomy if needed
                    'field'    => 'slug',
                    'terms'    => $kategorija_slug,
                ];
            }

            $discount = isset($_GET['discount']) ? floatval($_GET['discount']) : 0;

            if ($discount > 0) {
                $args['post__in'] = get_discounted_product_ids($discount);
            }

            $query = new WP_Query($args); ?>
            <div class="d-none d-md-block">
                <div class="d-flex justify-content-between flex-wrap">
                    <!-- Result Container -->
                    <div id="results-count" class="mb-3 order-2 order-md-0">
                        <?php
                        if ($query->have_posts()) :
                            echo 'Rodoma: ' . $query->post_count . ' iš ' . $query->found_posts;
                        endif;
                        ?>
                    </div>

                    <!-- Sort By (outside the form) -->
                    <div class="filter_sort mb-3 custom">
                        <select class="form-select w-100" id="sort-by-select" name="sort_by">
                            <option value="date_desc"><?php _e('Naujausi', 'ewsdev'); ?></option>
                            <option value="popularity"><?php _e('Populiariausi', 'ewsdev'); ?></option>
                            <option value="lowprice"><?php _e('Mažiausia kaina', 'ewsdev'); ?></option>
                            <option value="highprice"><?php _e('Didžiausia kaina', 'ewsdev'); ?></option>
                        </select>
                    </div>
                </div>
            </div>


            <!-- <div class="filter-loading-spinner" id="filter-loader">Įkeliami filtruoti produktai...</div> -->
            <div id="filter-loader" class="skeleton-loader hidden">
                <div class="skeleton-card"></div>
                <div class="skeleton-card"></div>
                <div class="skeleton-card"></div>
                <div class="skeleton-card"></div>
                <div class="skeleton-card"></div>
                <div class="skeleton-card"></div>
            </div>
            <style>
                .skeleton-loader {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                    gap: 15px;
                    margin: 20px 0;
                }

                .skeleton-card {
                    height: 250px;
                    border-radius: 8px;
                    background: linear-gradient(-90deg,
                            #f0f0f0 0%,
                            #e0e0e0 50%,
                            #f0f0f0 100%);
                    background-size: 400% 400%;
                    animation: skeleton-loading 1.5s ease infinite;
                }

                @keyframes skeleton-loading {
                    0% {
                        background-position: 100% 0;
                    }

                    100% {
                        background-position: -100% 0;
                    }
                }

                .hidden {
                    display: none !important;
                }
            </style>
            <div class="products-wrapper hidden-on-load" id="products-list">
                <div id="product-results" class="row g-4">
                    <?php

                    $shown_count = 0;
                    if ($query->have_posts()) :
                        while ($query->have_posts()) : $query->the_post();
                            global $product;

                            if (!$product instanceof WC_Product) continue;

                    ?>

                            <div class="col-md-4">
                                <?php echo ewsdev_product_part_html($product);
                                ?>
                                <!-- end single product -->
                            </div>
                    <?php
                        endwhile;

                    else:
                        echo '<p>Nerasta jokių produktų.</p>';
                    endif;
                    ?>

                    <?php
                    $count = 'Rodoma: ' . $query->post_count . ' iš ' . $query->found_posts;
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
                    ?>
                </div>
            </div>
            <!-- products-wrapper hidden-on-load -->

            <div id="pagination-wrapper">
                <?php echo $pagination_html; ?>

            </div>

        </div>


    </div> <!-- End row -->
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-9">
            <div class="cat_details pb-5 mb-md-4">
                <?php
                $term = get_queried_object();
                if ($term instanceof WP_Term && !empty($term->description)) { ?>
                    <div class="term-description expand" style="display: none;"><?php echo term_description($term); ?></div>
                    <div class="term-description sort"><?php echo ews_short_description(52); ?></div>

                    <button class="toggle-description dropdown_arrow">Daugiau</button>
                    <script>
                        jQuery(document).ready(function($) {
                            $('.toggle-description').on('click', function() {
                                var $sort = $('.term-description.sort');
                                var $expand = $('.term-description.expand');
                                var $btn = $(this);

                                if ($expand.is(':visible')) {
                                    $expand.hide();
                                    $sort.show();
                                    $btn.text('Daugiau');
                                    $btn.removeClass('less');
                                } else {
                                    $sort.hide();
                                    $expand.show();
                                    $btn.text('Mažiau');
                                    $btn.addClass('less');
                                }
                            });

                        });
                    </script>
                <?php
                }
                ?>

            </div>
        </div>
    </div>

    </div> <!-- End container -->



    <style>
        .products-wrapper.hidden-on-load {
            display: none;
        }

        .filter-loading-spinner {
            display: none;
            text-align: center;
            padding: 40px;
            font-size: 18px;
        }

        .filter-loading-spinner.active {
            display: block;
        }
    </style>

    <script>

    </script>

    <?php
    $background_color = "#F8F9FA";
    ?>

    <section class="iconbox_section py-5 s2 in_page" style="background-color:<?php echo $background_color; ?>;">
        <div class="container">

            <div class="icon_list s2">
                <div class="info">
                    <div class="icon">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/list-Grazinimas.png" alt="icon-image" />
                    </div>
                    <div class="text">
                        <h3>Nemokamas grąžinimas</h3>
                        <p>Visoje Lietuvoje</p>
                    </div>
                </div> <!--  end info -->
                <div class="info">
                    <div class="icon">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/llist-Saugus apmokejimas.png" alt="icon-image" />
                    </div>
                    <div class="text">
                        <h3>Saugus atsiskaitymas</h3>
                        <p>SLL mokėjimų apsauga</p>
                    </div>
                </div> <!--  end info -->
                <div class="info">
                    <div class="icon">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/list-Delivery.png" alt="icon-image" />
                    </div>
                    <div class="text">
                        <h3>Nemokamas pristatymas</h3>
                        <p>Perkant nuo 99 EUR</p>
                    </div>
                </div> <!--  end info -->
                <div class="info">
                    <div class="icon">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/list-Stygavimas.png" alt="icon-image" />
                    </div>
                    <div class="text">
                        <h3>Individualus stygavimas</h3>
                        <p>Maksimaliam našumui</p>
                    </div>
                </div> <!--  end info -->
            </div>
        </div>
    </section>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>


<?php
    woocommerce_product_loop_start();

    // if (wc_get_loop_prop('total')) {
    //     while (have_posts()) {
    //         the_post();

    //         /**
    //          * Hook: woocommerce_shop_loop.
    //          */
    //         do_action('woocommerce_shop_loop');

    //         wc_get_template_part('content', 'product');
    //     }
    // }

    woocommerce_product_loop_end();

    /**
     * Hook: woocommerce_after_shop_loop.
     *
     * @hooked woocommerce_pagination - 10
     */
    // do_action('woocommerce_after_shop_loop');
} else {
    /**
     * Hook: woocommerce_no_products_found.
     *
     * @hooked wc_no_products_found - 10
     */
    // do_action('woocommerce_no_products_found');
?>
    <div class="container pb-5 mb-md-5 mb-md-3">
        <div class="h-100" style="min-height: 400px;">
            <h2 class="woocommerce-products-header__title page-title d-flex gap-2 align-items-center mb-3">
                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/search3.png" width="32" height="32" alt="icon" class="search_icon " />
                Prekių nerasta
            </h2>
            <div class="not_found">
                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/error3.png" alt="icon" class="search_icon" />
                <aside>Nieko neradome pagal paieškos terminą <strong><?php echo esc_html(get_search_query()); ?></strong></aside>
            </div>
            <!-- Divider -->
            <hr class="divider my-4" />
            <a class="btn arrow_btn black_btn back" href="<?php echo site_url(); ?>/parduotuve/"><span></span> Tęsti apsipirkimą</a>
        </div>
    </div>

    <?php
    $background_color = "#F8F9FA";
    ?>

    <section class="iconbox_section py-5 s2 in_page" style="background-color:<?php echo $background_color; ?>;">
        <div class="container">

            <div class="icon_list s2">
                <div class="info">
                    <div class="icon">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/list-Grazinimas.png" alt="icon-image" />
                    </div>
                    <div class="text">
                        <h3>Nemokamas grąžinimas</h3>
                        <p>Visoje Lietuvoje</p>
                    </div>
                </div> <!--  end info -->
                <div class="info">
                    <div class="icon">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/llist-Saugus apmokejimas.png" alt="icon-image" />
                    </div>
                    <div class="text">
                        <h3>Saugus atsiskaitymas</h3>
                        <p>SLL mokėjimų apsauga</p>
                    </div>
                </div> <!--  end info -->
                <div class="info">
                    <div class="icon">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/list-Delivery.png" alt="icon-image" />
                    </div>
                    <div class="text">
                        <h3>Nemokamas pristatymas</h3>
                        <p>Perkant nuo 99 EUR</p>
                    </div>
                </div> <!--  end info -->
                <div class="info">
                    <div class="icon">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/list-Stygavimas.png" alt="icon-image" />
                    </div>
                    <div class="text">
                        <h3>Individualus stygavimas</h3>
                        <p>Maksimaliam našumui</p>
                    </div>
                </div> <!--  end info -->
            </div>
        </div>
    </section>


<?php
}
?>
<style>
    .term-padelis .ko-tikimasi-is-raketes {
        display: none;
    }
</style>
<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
// do_action('woocommerce_sidebar');

get_footer('shop');
