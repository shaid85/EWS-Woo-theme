<?php get_header();
$image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
?>

<div id="content" class="site-content">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <section class="blog_head" style="background-color: #FAFAF5;">
                <div class="container">
                    <div class="featured_post custom-post-loop ">
                        <div class="container">
                            <div class="row gap-3 gap-md-0 align-items-end p-50 ">
                                <div class="col-md-6">
                                    <div class="content_box card_box">
                                        <a href="<?php echo site_url(); ?>/naujienos/">
                                            <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/back-icon.png" class="back-icon mb-4 pb-2" width="40" alt="icon">
                                        </a>
                                        <h3><?php the_title(); ?></h3>
                                        <p>
                                            <?php
                                            $excerpt = get_the_excerpt();
                                            $trimmed_excerpt = mb_strimwidth($excerpt, 0, 200, '...');
                                            echo esc_html($trimmed_excerpt);
                                            ?>
                                        </p>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="content_box card_box">
                                        <a href="<?php the_permalink(); ?>">
                                            <div class="f_image">
                                                <div class="bg_img" style="background-image: url('<?php echo esc_url($image_url); ?>');"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="home-blog">
                <div class="container my-5">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="post_details">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </div>
                </div>

            </section>


            <section class="related_post p-70" style="background-color: #FAFAF5;">
                <div class="container">
                    <?php // Post loop 

                    global $post;

                    $categories = wp_get_post_categories($post->ID);

                    $args = array(
                        'post_type'      => 'post',
                        'posts_per_page' => 3,
                        'post__not_in'   => array($post->ID), // Exclude current post
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                        'category__in'   => $categories, // Match categories
                    );

                    $query = new WP_Query($args);

                    if ($query->have_posts()) { ?>
                        <div class="custom-post-loop <?php echo ($featured_post == 1) ? 'p-60' : ''; ?>
">
                            <?php
                            echo '<div class="row row-gap-5">';

                            while ($query->have_posts()) {
                                $query->the_post();
                                $image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
                            ?>
                                <div class="col-md-4">
                                    <div class="card_box">
                                        <a href="<?php the_permalink(); ?>">
                                            <div class="f_image">
                                                <div class="bg_img" style="background-image: url('<?php echo esc_url($image_url); ?>');"></div>
                                            </div>
                                        </a>
                                        <h2 class="text"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                        <p>
                                            <?php // the_excerpt(); 
                                            ?>
                                            <?php
                                            $excerpt = get_the_excerpt();
                                            $trimmed_excerpt = mb_strimwidth($excerpt, 0, 100, '...');
                                            echo esc_html($trimmed_excerpt);
                                            ?>

                                        </p>
                                        <a class="seemore" href="<?php the_permalink(); ?>">Skaityti</a>
                                    </div>
                                </div>
                            <?php
                            }
                            echo '</div>';
                            ?>
                        </div>
                    <?php

                    }

                    ?>
                </div>
            </section>
        </main>
    </div>
</div>

<style>
    @media (min-width: 1200px) {
        .post_details h1 {
            font-size: 36px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .post_details h2 {
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .post_details h3 {
            font-size: 22px;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .post_details p {
            margin-bottom: 20px;
        }

        .post_details ul {
            margin-bottom: 20px;
            padding-left: 30px;
        }

        section .post_details li {
            background-image: none;
            list-style: disc;
            padding-left: 0;
        }

        .post_details blockquote {
            background-color: #f8f9fa;
            padding: 40px;
            margin: 30px 0;
            font-size: 20px;
            font-weight: 500;
            color: #000;
            border-radius: 8px;
        }



    }
</style>
<?php get_footer(); ?>