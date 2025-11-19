<?php
$section_title = get_sub_field('section_title');
$background_color = get_sub_field('background_color');
$add_custom_class = get_sub_field('add_custom_class');

$post_per_page = get_sub_field('post_per_page');
$featured_post = get_sub_field('featured_post');

?>
<section id="post_block_section" class="postblock_box <?php if (!$featured_post) {
                                                            echo ' d-none d-md-block';
                                                        } ?> <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">

    <div class="container">

        <div class="head_box d-flex justify-content-between align-items-start ">
            <?php if ($section_title) : ?>
                <h2 class="ews_title mb-5">
                    <?php echo acf_esc_html($section_title); ?>
                </h2>
            <?php endif; ?>
            <?php $pagination_status2 = get_sub_field('pagination_status');
            if ($pagination_status2 != 'Show') { ?>
                <a href="<?php echo site_url(); ?>/naujienos/" class="btn btn-primary bg-white text-black see_more mt-2 d-none d-md-block">Daugiau</a>
            <?php } ?>
        </div>
    </div>

    <?php if ($featured_post) :
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => 1,
            'order'          => 'DESC',
        );

        $featured_query = new WP_Query($args);
        if ($featured_query->have_posts()):
            while ($featured_query->have_posts()) {
                $featured_query->the_post();
                $image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
    ?>
                <div class="featured_post custom-post-loop m_reverse">
                    <div class="container">
                        <div class="row gap-4 gap-md-0 align-items-end p-50 pt-0 border-bottom border-light-subtle">
                            <div class="col-md-6">
                                <div class="content_box card_box">
                                    <h3><?php the_title(); ?></h3>
                                    <p>
                                        <?php
                                        $excerpt = get_the_excerpt();
                                        $trimmed_excerpt = mb_strimwidth($excerpt, 0, 160, '...');
                                        echo esc_html($trimmed_excerpt);
                                        ?>
                                    </p>
                                    <a class="seemore" href="<?php the_permalink(); ?>">Skaityti</a>
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
        <?php
            }
        endif; ?>
    <?php endif; ?>

    <div class="container">
        <?php // Post loop Function
        function custom_post_loop_with_pagination($posts_per_page = 6)
        {
            $featured_post = get_sub_field('featured_post');
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => $posts_per_page,
                'order'          => 'DESC',
                'paged'          => $paged,
                'offset'         => ($paged - 1) * $posts_per_page + 1,
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

                $pagination_status = get_sub_field('pagination_status');
                if ($pagination_status == 'Show') {
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
                        $pagination_html .= '<div class="pagination">';

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
    <div class="container">
        <div id="pagination-wrapper8">
            <?php echo $pagination_html; ?>

        </div>
    </div>
<?php
                }
            } else {
                echo '<p>No posts found.</p>';
            }

            wp_reset_postdata();
        }
?>
<?php custom_post_loop_with_pagination($post_per_page); ?>
<div class="container">
    <a href="<?php echo site_url(); ?>/naujienos" class="btn btn-primary bg-white text-black see_more mt-2 d-block d-md-none mt-5">Daugiau</a>
</div>

</div>
</section>




<?php if (!$featured_post) : ?>

    <section id="recent_posts" class="popular_product  d-block d-md-none <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">
        <div class="container">
            <div class="head_box d-flex justify-content-between align-items-start ">
                <?php if ($section_title) : ?>
                    <h2 class="ews_title mb-5">
                        <?php echo acf_esc_html($section_title); ?>
                    </h2>
                <?php endif; ?>
                <a href="<?php echo site_url(); ?>/shop" class="btn btn-primary bg-white text-black see_more mt-2 d-none d-md-block">Daugiau</a>
            </div>
            <div class="swiper-container my-swiper sl-<?php echo $layout . '-' . $index; ?>">
                <div class="swiper-wrapper">
                    <?php
                    $args = array(
                        'post_type'      => 'post',
                        'posts_per_page' => 10,
                        'order'          => 'DESC',
                    );

                    $loop = new WP_Query($args);

                    if ($loop->have_posts()) :

                        while ($loop->have_posts()) : $loop->the_post();
                            global $product;
                    ?>
                            <div class="swiper-slide ">
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
                            </div>
                        <?php
                        endwhile;

                    else :
                        ?>
                        <div class="col">
                            <h2 class="ews_title">
                                Produktų nerasta.
                            </h2>
                        </div>
                    <?php
                    endif;

                    wp_reset_postdata();
                    ?>
                </div><!-- swiper-wrapper -->
                <!-- Navigation -->
                <div class="container-fluid px-0 d-flex justify-content-between align-items-center mt-5 pt-4">
                    <div class="swiper-progress-bar">
                        <span class="swiper-progress"></span>
                    </div>
                    <div class="arrow">
                        <div class="swiper-button-prev">
                            <div class="icon-box">
                                <div class="icon-holder">
                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/arrow-left-black.png" alt="icon" />
                                </div>
                            </div>
                        </div>
                        <div class="swiper-button-next">
                            <div class="icon-box">
                                <div class="icon-holder">
                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/arrow-right-black.png" alt="icon" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- end container Navigation -->

            </div><!-- end swiper-container -->
            <a href="<?php echo site_url(); ?>/naujienos" class="btn btn-primary bg-white text-black see_more mt-2 d-block d-md-none mt-5">Daugiau</a>
        </div>

    </section>
    <script>
        // Product slider
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.sl-<?php echo $layout . '-' . $index; ?>').forEach((slider) => {
                new Swiper(slider, {
                    slidesPerView: 4, // Show 4 items at a time
                    slidesPerGroup: 1, // Move 1 item per slide
                    loop: true, // Disable looping
                    autoplay: false, // No autoplay
                    spaceBetween: 16,
                    mousewheel: {
                        forceToAxis: true,
                        sensitivity: 1,
                        releaseOnEdges: true, // Recommended for better UX
                    },
                    navigation: {
                        nextEl: slider.querySelector('.sl-<?php echo $layout . '-' . $index; ?> .swiper-button-next'),
                        prevEl: slider.querySelector('.sl-<?php echo $layout . '-' . $index; ?> .swiper-button-prev'),
                    },
                    breakpoints: {
                        0: {
                            slidesPerView: 1,
                        },
                        560: {
                            slidesPerView: 2,
                        },
                        767: {
                            slidesPerView: 3,
                        },
                        1024: {
                            slidesPerView: 4,
                        },
                    },
                    on: {
                        slideChangeTransitionStart: function() {
                            let progress = (this.realIndex + 1) / this.slides.length * 100;
                            document.querySelector('.sl-<?php echo $layout . '-' . $index; ?> .swiper-progress').style.width = progress + "%";
                        }
                    }
                });
            });
        });
    </script>

<?php endif; ?>