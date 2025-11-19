<?php
$section_title = get_sub_field('section_title');
$background_color = get_sub_field('background_color');
$add_custom_class = get_sub_field('add_custom_class');

$top_categories = get_sub_field('select_4_product_category');

?>
<section id="top_categories" class="top-categories d-none d-md-block <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">
    <div class="container ">

        <?php if ($section_title) : ?>
            <h2 class="ews_title mb-5">
                <?php echo acf_esc_html($section_title); ?>
            </h2>
        <?php endif; ?>
        <div class="row gx-3 only_desktop2">
            <?php
            if ($top_categories):
                // Sort by menu_order (used by WooCommerce)
                // usort($top_categories, function ($a, $b) {
                //     $order_a = get_term_meta($a->term_id, 'order', true) ?: 0;
                //     $order_b = get_term_meta($b->term_id, 'order', true) ?: 0;
                //     return $order_a - $order_b;
                // });
            ?>
                <?php foreach ($top_categories as $term):
                    $product_category = $term['product_category'];
                    $custom_name = $term['custom_name'];
                    $thumbnail_id = get_term_meta($product_category->term_id, 'thumbnail_id', true);
                    $image_url = wp_get_attachment_url($thumbnail_id);
                    $image = $image_url ? $image_url : wc_placeholder_img_src();
                ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="cat_box">
                            <a href="<?php echo esc_url(get_term_link($product_category)); ?>">
                                <div class="f_image">
                                    <div class="bg_img" style="background-image: url('<?php echo esc_url($image); ?>');"></div>
                                    <div class="icon-box">
                                        <div class="icon-holder">
                                            <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/arrow-up2.png" alt="icon" />
                                        </div>
                                    </div>

                                </div>
                            </a>
                            <h2 class="text">
                                <a href="<?php echo esc_url(get_term_link($product_category)); ?>">
                                    <?php if ($custom_name) :  ?>
                                        <?php echo esc_html($custom_name); ?>
                                    <?php else : ?>
                                        <?php echo esc_html($product_category->name); ?>
                                    <?php endif; ?>
                                </a>
                            </h2>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</section>

<section id="top_categories_mobile" class="popular_product d-block d-md-none  <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">
    <div class="container">
        <div class="head_box d-flex justify-content-between align-items-start ">
            <?php if ($section_title) : ?>
                <h2 class="ews_title mb-5">
                    <?php echo acf_esc_html($section_title); ?>
                </h2>
            <?php endif; ?>

        </div>
        <div class="swiper-container my-swiper sl-<?php echo $layout . '-' . $index; ?>">
            <div class="swiper-wrapper">
                <?php if ($top_categories): ?>
                    <?php foreach ($top_categories as $term):
                        $product_category = $term['product_category'];
                        $custom_name = $term['custom_name'];
                        $thumbnail_id = get_term_meta($product_category->term_id, 'thumbnail_id', true);
                        $image_url = wp_get_attachment_url($thumbnail_id);
                        $image = $image_url ? $image_url : wc_placeholder_img_src();
                    ?>
                        <div class="swiper-slide ">
                            <div class="col-12">
                                <div class="cat_box">
                                    <a href="<?php echo esc_url(get_term_link($product_category)); ?>">
                                        <div class="f_image">
                                            <div class="bg_img" style="background-image: url('<?php echo esc_url($image); ?>');"></div>
                                            <div class="icon-box">
                                                <div class="icon-holder">
                                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/arrow-up2.png" alt="icon" />
                                                </div>
                                            </div>

                                        </div>
                                    </a>
                                    <h2 class="text">
                                        <a href="<?php echo esc_url(get_term_link($product_category)); ?>">
                                            <?php if ($custom_name) :  ?>
                                                <?php echo esc_html($custom_name); ?>
                                            <?php else : ?>
                                                <?php echo esc_html($product_category->name); ?>
                                            <?php endif; ?>
                                        </a>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div><!-- swiper-wrapper -->
            <!-- Navigation -->
            <div class="container-fluid px-0 d-flex justify-content-between align-items-center mt-3 mt-md-5 pt-4">
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