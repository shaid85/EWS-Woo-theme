<?php
/*
Template Name: wishlist page
*/

/**
 * Compare Products template
 * URL pattern: /compare-products/?ids=131,133,135
 */
defined('ABSPATH') || exit;
get_header(); ?>

<div id="content_area" class="site-content">
    <div id="primary" class="content-area">
        <main id="main" class="site-main ">
            <?php

            // Get wishlist IDs
            $product_ids = [];

            if (is_user_logged_in()) {
                $product_ids = (array) get_user_meta(get_current_user_id(), '_loved_products', true);
                // print_r($product_ids);
            } else {
                $product_ids = !empty($_GET['guest_ids'])
                    ? array_map('absint', explode(',', $_GET['guest_ids']))
                    : [];
            }


            // Show empty state if no products
            if (
                !is_array($product_ids) ||
                empty(array_filter($product_ids, function ($id) {
                    return !empty($id);
                }))
            ) {
            ?>
                <div class="cat_header py-5">
                    <div class="container">
                        <div class="col-xl-6">
                            <?php do_action('woocommerce_before_main_content'); ?>
                        </div>

                        <?php echo '</div>'; ?>
                    </div>

                </div>
                <div class="container pb-5 mb-md-5 mb-md-3">

                    <div class="h-100 col-xl-6" style="min-height: 400px;">
                        <h2 class="woocommerce-products-header__title page-title d-flex gap-2 align-items-center mb-3">
                            <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/favorite32.png" width="32" height="32" alt="icon" class="search_icon " />
                            Neturite patikusių prekių
                        </h2>
                        <p class="w-md-50">Spustelėkite prie prekės esančią širdelę, jei norite tą prekę išsaugoti mėgstamiausių sąraše.</p>
                        <div class="not_found d-none">
                            <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/error3.png" alt="icon" class="search_icon" />
                            <aside>Nieko neradome pagal paieškos terminą <strong><?php echo esc_html(get_search_query()); ?></strong></aside>
                        </div>
                        <!-- Divider -->
                        <hr class="divider my-5" />
                        <a class="btn arrow_btn black_btn back" href="<?php echo site_url(); ?>/shop/"><span></span> Tęsti apsipirkimą</a>
                    </div>
                </div>
            <?php
            } else {
            ?>
                <div class="loved_products p-50">
                    <div class="container">
                        <div class="cat_header text-center ">
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
                            <h1 class="woocommerce-products-header__title page-title mb-md-5 mb-4 mt-3">
                                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/search3.png" alt="icon" class="search_icon d-none" />
                                Patikusios prekės
                            </h1>

                        </div>
                        <?php
                        /* 2. Query products in the exact order provided */
                        $query = new WP_Query([
                            'post_type'           => 'product',
                            'post_status'         => 'publish',
                            'post__in'            => $product_ids,
                            'orderby'             => 'post__in',  // preserves the original order 131,133,135 …
                            'posts_per_page'      => -1,
                            'ignore_sticky_posts' => true,
                        ]);

                        if (! $query->have_posts()) {
                            echo '<p>' . __('No valid products found.', 'textdomain') . '</p>';
                        }

                        /* 3. Loop through products */
                        echo '<div class="row row-cols-md-' . count($product_ids) . '">';

                        while ($query->have_posts()) : $query->the_post();
                            $product = wc_get_product(get_the_ID()); ?>

                            <div class="col-md-3 product_head position-relative">
                                <?php echo ewsdev_product_part_html($product, 52, 1); ?>
                                <!-- end single product -->
                            </div>

                        <?php endwhile;
                        echo '</div>'; // .row
                        wp_reset_postdata();
                        ?>

                    </div>

                </div> <!-- End container -->
            <?php
            }
            ?>


            <div class="p-4"></div>
        </main>
    </div>
</div>
<?php get_footer(); ?>