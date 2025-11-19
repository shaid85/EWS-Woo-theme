<?php
/*
Template Name: 404 page
*/
defined('ABSPATH') || exit;
get_header(); ?>

<div id="content" class="site-content">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <section class="statter_sec position-relative p-50">
                <div class="container">
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
            </section>

            <?php
            echo do_shortcode('[apie_mus_faq]');

            ?>

        </main>
    </div>
</div>
<?php get_footer(); ?>