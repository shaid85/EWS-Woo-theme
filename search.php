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

get_header();
?>
<div class="container my-5">
    <h1 class="row mb-5">Search Results for: <?php echo get_search_query(); ?></h1>
    <div class="row p-50">
        <?php while (have_posts()): the_post(); ?>
            <div class="col-md-3 mb-3">
                <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            </div>
        <?php endwhile; ?>
    </div>
</div>


<?php


get_footer();
