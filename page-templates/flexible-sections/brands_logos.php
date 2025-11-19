<?php
$section_title = get_sub_field('section_title');
$background_color = get_sub_field('background_color');
$add_custom_class = get_sub_field('add_custom_class');

$company_logo = get_sub_field('add_company_logo');
?>
<!-- Brands -->
<section id="brands_logos" class="brands winter_sale  <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">
    <div class="container">
        <?php if ($section_title) : ?>
            <h2 class="ews_title mb-5">
                <?php echo acf_esc_html($section_title); ?>
            </h2>
        <?php endif; ?>
        <div class="swiper-container brand-slider">
            <div class="swiper-wrapper">
                <?php
                if ($company_logo): ?>
                    <?php foreach ($company_logo as $logo):
                        $brand_name = $logo['caption'];
                    ?>
                        <div class="swiper-slide ">
                            <a href="<?php echo site_url(); ?>/gamintojas/<?php echo $brand_name; ?>">
                                <div class="brandimg">
                                    <img src="<?php echo esc_url($logo['sizes']['medium_large']); ?>" alt="<?php echo esc_attr($logo['alt']); ?>" />
                                </div>
                            </a>
                        </div><!-- swiper-slide -->
                    <?php endforeach; ?>
                <?php endif; ?>

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
    </div><!-- end container -->
</section>