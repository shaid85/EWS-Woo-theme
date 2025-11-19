<?php
$section_title = get_sub_field('section_title');
$background_color = get_sub_field('background_color');
$add_custom_class = get_sub_field('add_custom_class');

$product_slider = get_sub_field('product_slider');

$custom_posts = get_sub_field('custom_select');
$post_show_no = get_sub_field('post_show_no');

$layout = get_row_layout();
$index = get_row_index();

?>
<section id="popular_products" class="popular_product  <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">
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
                if ($product_slider ==  'custom') {
                    // If the field returns post objects
                    if (is_object($custom_posts[0])) {
                        $post_ids = wp_list_pluck($custom_posts, 'ID');
                    } else {
                        // If the field returns IDs
                        $post_ids = $custom_posts;
                    }

                    $args = array(
                        'post_type'      => 'product',
                        'post__in'       => $post_ids,
                        'orderby'        => 'post__in', // Preserve ACF order
                        'posts_per_page' => $post_show_no,
                    );
                } else if ($product_slider ==  'total_sales') {
                    // Custom WooCommerce Product Loop
                    $args = array(
                        'post_type'      => 'product',
                        'posts_per_page' => $post_show_no, // Adjust as needed
                        'meta_key'       => 'total_sales', // Sort by total sales (popular products)
                        'orderby'        => 'meta_value_num', // Order by numeric value
                        'order'          => 'DESC',
                        'post_status'    => 'publish',
                    );
                } else {
                    // Custom WooCommerce Product Loop
                    $args = array(
                        'post_type'      => 'product',
                        'posts_per_page' => $post_show_no, // Adjust as needed
                        // 'meta_key'       => 'total_sales', // Sort by total sales (popular products)
                        'orderby'        => 'meta_value_num', // Order by numeric value
                        'order'          => 'DESC',
                        'post_status'    => 'publish',
                    );
                }

                $loop = new WP_Query($args);

                if ($loop->have_posts()) :

                    while ($loop->have_posts()) : $loop->the_post();
                        global $product;
                ?>
                        <div class="swiper-slide ">
                            <?php echo ewsdev_product_part_html($product, 54); ?>
                            <!-- end single product -->
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
        <a href="<?php echo site_url(); ?>/shop" class="btn btn-primary bg-white text-black see_more mt-2 d-block d-md-none mt-5">Daugiau</a>
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