<?php
$background_color = get_sub_field('background_color');
$add_custom_class = get_sub_field('add_custom_class');

?>
<section class="hero  <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">
    <div class="hero-slider-container">
        <div class="swiper hero-slider">

            <div class="swiper-wrapper">
                <?php
                if (have_rows('add_slide_for_hero_slider')):

                    while (have_rows('add_slide_for_hero_slider')) : the_row();

                        $full_width_image = get_sub_field('full_width_image');
                        $heading_of_slide = get_sub_field('heading_of_slide');
                        $text_for_slide = get_sub_field('text_for_slide');
                        $button = get_sub_field('hero_button');

                ?>
                        <!-- Slide 1 -->
                        <div class="swiper-slide" style="background-image: url('<?php echo esc_url($full_width_image['url']); ?>');">
                            <div class="overshadow"></div>
                            <div class="col-md-6 content_wrap px-3">
                                <h1><?php echo acf_esc_html($heading_of_slide); ?></h1>
                                <p><?php echo acf_esc_html($text_for_slide); ?></p>
                                <?php if ($button) : ?>
                                    <a class="arrow_btn btn mt-3" href="<?php echo esc_url($button['url']); ?>"><?php echo acf_esc_html($button['title']); ?> <span></span></a>
                                <?php endif; ?>
                            </div>
                        </div>
                <?php
                    endwhile;
                else :
                    // Do something...
                    echo '<h2>No Slide Added.</h2>';
                endif; ?>
            </div>
            <!-- Navigation -->
            <div class="container">
                <div class="swiper-button-next">
                    <div class="icon-box">
                        <div class="icon-holder">
                            <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/arrow-right2.png" alt="icon" />
                        </div>
                    </div>
                </div>
                <div class="swiper-button-prev">
                    <div class="icon-box">
                        <div class="icon-holder">
                            <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/arrow-left2.png" alt="icon" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination Dots -->
            <div class="swiper-pagination"></div>

        </div>
    </div>

</section>