<?php
/*
Template Name: register page
*/
defined('ABSPATH') || exit;
get_header(); ?>

<div id="content" class="site-content">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php
            echo do_shortcode('[flexlayout name=page_section]');
            ?>

            <section class="statter_sec position-relative p-50">
                <div class="container">

                    <div class="account_page">
                        <div class="cat_header px-3 pb-4">
                            <?php do_action('ews_before_page_content'); ?>
                        </div>
                    </div>

                    <?php the_content(); ?>
                </div>
            </section>

        </main>
    </div>
</div>
<?php get_footer(); ?>