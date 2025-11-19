<?php
$section_title = get_sub_field('section_title');
$background_color = get_sub_field('background_color');
$add_custom_class = get_sub_field('add_custom_class');

$cat_gridbox = get_sub_field('categories_gridbox');
?>
<section class="category_grid <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">
    <div class="container">
        <div class="head_box d-flex justify-content-between align-items-start ">
            <?php if ($section_title) : ?>
                <h2 class="ews_title mb-5">
                    <?php echo acf_esc_html($section_title); ?>
                </h2>
            <?php endif; ?>

        </div>
        <div class="swiper-container my-swiper2">
            <div class="swiper-wrapper">
                <?php
                if ($cat_gridbox):
                    // Sort by menu_order (used by WooCommerce)
                    usort($cat_gridbox, function ($a, $b) {
                        $order_a = get_term_meta($a->term_id, 'order', true) ?: 0;
                        $order_b = get_term_meta($b->term_id, 'order', true) ?: 0;
                        return $order_a - $order_b;
                    });
                ?>
                    <?php foreach ($cat_gridbox as $term):
                        $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
                        $image_url = wp_get_attachment_url($thumbnail_id);
                        $image = $image_url ? $image_url : wc_placeholder_img_src();
                    ?>
                        <div class="swiper-slide ">
                            <div class="cat_box overly">
                                <a href="<?php echo esc_url(get_term_link($term)); ?>">
                                    <div class="f_image">
                                        <div class="bg_img" style="background-image: url('<?php echo esc_url($image); ?>');"></div>
                                        <div class="cattitle_wrap">
                                            <h2 class="text">
                                                <?php echo esc_html($term->name); ?>
                                            </h2>

                                            <div class="icon-box">
                                                <div class="icon-holder">
                                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/arrow-up2.png" alt="icon" />
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </a>

                            </div>
                        </div><!-- swiper-slide -->

                    <?php endforeach; ?>
                <?php endif; ?>




            </div><!-- swiper-wrapper -->
            <!-- Navigation -->
            <div class="container px-0 d-flex justify-content-between align-items-center mt-5 pt-4 d-block d-md-none">
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
    </div><!-- end container -->

</section>