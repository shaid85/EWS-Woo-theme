<?php
/*
Template Name: Step page
*/
defined('ABSPATH') || exit;
get_header(); ?>
<script>
    (function($) {
        Cookies.remove('min_price');
        Cookies.remove('max_price');
        Cookies.remove('product_filter_data');
    })(jQuery);
</script>
<div id="content" class="site-content">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <section class="statter_sec step_page position-relative ">
                <?php echo do_shortcode('[racket_finder]'); ?>
            </section>

        </main>
    </div>
</div>
<?php get_footer(); ?>