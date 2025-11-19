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
// 2. Handle search
if (!empty($_GET['s'])) {
    $args['s'] = sanitize_text_field($_GET['s']);
}

$args = array(
    'post_type'      => 'product',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    's'         => sanitize_text_field($_GET['s']),
    'meta_query' => array(
        array(
            'key'     => '_price',
            'value'   => 0,
            'compare' => '>',
            'type'    => 'NUMERIC'
        )
    )
);



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
    var categoryPriceRange = {
        min: <?php echo $min_price; ?>,
        max: <?php echo $max_price; ?>
    };
    console.log(categoryPriceRange);
</script>
<div class="container search_page">
    <div class="cat_header text-center p-60">
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
        //do_action('woocommerce_shop_loop_header'); // show archive title
        ?>
        <?php if (is_search()) : ?>
            <h1 class="woocommerce-products-header__title page-title">
                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/search3.png" alt="icon" class="search_icon d-none" />
                <?php // echo esc_html(get_search_query()); 
                ?>
            </h1>
            <div class="recent_list mt-4">
                <?php // show_wp_recent_searches(); //1 paramiter number, max 6  
                ?>
            </div>
        <?php else : ?>
            <?php do_action('woocommerce_shop_loop_header'); ?>
        <?php endif; ?>

    </div>

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
    <?php if (is_search() && is_post_type_archive('product')): ?>
        <div class="container">
            <div class="row product-filter_wrap">
                <div class="col-md-3">
                    <div id="product-filter" class="container mb-4">
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


                                <div class="hiddenfield">

                                    <!-- search -->
                                    <input type="hidden" id="search_query" name="search" value="">
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

                                                    <span class="mx-2">-</span>

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






                                <!-- sort_by select is outside -->
                                <input type="hidden" name="sort_by" id="sort_by_hidden" value="<?php echo esc_attr($_GET['sort_by'] ?? 'date_desc'); ?>">


                            </div><!-- End row -->
                        </form>
                    </div><!-- End product-filter -->


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

                    $discount = isset($_GET['discount']) ? floatval($_GET['discount']) : 0;

                    if ($discount > 0) {
                        $args['post__in'] = get_discounted_product_ids($discount);
                    }

                    // 2. Handle search
                    if (!empty($_GET['s'])) {
                        $args['s'] = sanitize_text_field($_GET['s']);
                    }

                    $query = new WP_Query($args); ?>
                    <div class="d-flex justify-content-between">
                        <!-- Result Container -->
                        <div id="results-count" class="mb-3">
                            <?php
                            if ($query->have_posts()) :
                                echo 'Rodoma: ' . $query->post_count . ' iš ' . $query->found_posts;
                            endif;
                            ?>
                        </div>
                        <!-- Sort By (outside the form) -->
                        <div class="filter_sort mb-3">
                            <select class="form-select" id="sort-by-select" name="sort_by">
                                <option value="date_desc"><?php _e('Naujausi', 'ewsdev'); ?></option>
                                <option value="popularity"><?php _e('Populiariausi', 'ewsdev'); ?></option>
                                <option value="lowprice"><?php _e('Mažiausia kaina', 'ewsdev'); ?></option>
                                <option value="highprice"><?php _e('Didžiausia kaina', 'ewsdev'); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="products-wrapper " id="products-list">
                        <div id="product-results" class="row g-4">
                            <?php

                            $shown_count = 0;
                            if ($query->have_posts()) :
                                while ($query->have_posts()) : $query->the_post();
                                    global $product;

                                    if (!$product instanceof WC_Product) continue;

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
                                            $pagination_html .= '<a class="next page-numbers" data-page="' . esc_attr($page_num) . '">
                    <img src="' . get_stylesheet_directory_uri() . '/assets/images/arrow-right-black.png" alt="Next" />
                </a>';
                                        }
                                    }
                                    // Custom PREV
                                    elseif (strpos($link, 'prev') !== false) {
                                        if (preg_match('/page\/(\d+)/', $link, $matches)) {
                                            $page_num = $matches[1];
                                            $pagination_html .= '<a class="prev page-numbers" data-page="' . esc_attr($page_num) . '">
                    <img src="' . get_stylesheet_directory_uri() . '/assets/images/arrow-left-black.png" alt="Previous" />
                </a>';
                                        }
                                    }
                                    // Numeric page links
                                    else {
                                        if (preg_match('/page\/(\d+)/', $link, $matches)) {
                                            $page_num = $matches[1];
                                            $link = str_replace('<a ', '<a data-page="' . esc_attr($page_num) . '" ', $link);
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
        </div> <!-- End container -->


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

    <?php endif; ?>
    <script>
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
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const search_query = urlParams.get('s');

            if (search_query) {
                const input = document.getElementById('search_query');
                if (input) {
                    input.value = search_query;
                }
            }
        });
    </script>
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
    //do_action('woocommerce_after_shop_loop');
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
            <h2 class="woocommerce-products-header__title page-title d-flex gap-2 align-items-center mb-5">
                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/search3.png" width="32" height="32" alt="icon" class="search_icon " />
                Prekių nerasta
            </h2>
            <div class="not_found">
                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/error3.png" alt="icon" class="search_icon" />
                <aside>Nieko neradome pagal paieškos terminą <strong class="fw-medium"><?php echo esc_html(get_search_query()); ?></strong></aside>
            </div>
            <!-- Divider -->
            <hr class="divider my-5" />
            <a class="btn arrow_btn black_btn back" href="<?php echo site_url(); ?>/shop/"><span></span> Tęsti apsipirkimą</a>
        </div>
    </div>

    <div class="py-0" style="background-color: #FAFAF5;">
        <?php
        echo do_shortcode('[apie_mus_faq]');
        ?>
    </div>


    <?php
    $background_color = "#fff";
    ?>

    <section class="iconbox_section py-5 s2 in_page white_bg" style="background-color:<?php echo $background_color; ?>;">
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
