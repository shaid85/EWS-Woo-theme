<?php
/*
Template Name: Home Page Templete
*/

get_header(); ?>

<div id="content" class="site-content">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php
            echo do_shortcode('[flexlayout name=page_section]');
            ?>


            <?php the_content(); ?>


        </main>
    </div>
</div>
<?php get_footer(); ?>