<?php
$section_title = get_sub_field('section_title');
$background_color = get_sub_field('background_color');
$add_custom_class = get_sub_field('add_custom_class');

$promo_title = get_sub_field('promo_title');
$background_image = get_sub_field('background_image');
$promo_title_copy = get_sub_field('promo_title_copy');
$button = get_sub_field('button');
?>
<!-- Winter Sale -->
<section id="promo_box_winter" class="winter_sale <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">
    <div class="container">
        <div class="cat_box overly promo_box">
            <div class="f_image"
                style="background-image: url('<?php echo esc_url($background_image['url']); ?>');">
                <div class="cattitle_wrap ">
                    <?php if ($promo_title) : ?>
                        <h2 class="text">
                            <?php echo acf_esc_html($promo_title); ?>
                        </h2>
                    <?php endif; ?>
                    <?php if ($promo_title_copy) : ?>
                        <p class="text-white"><?php echo acf_esc_html($promo_title_copy); ?></p>
                    <?php endif; ?>
                    <?php if ($button) : ?>
                        <a class="arrow_btn btn mt-4" href="<?php echo esc_url($button['url']); ?>"><?php echo acf_esc_html($button['title']); ?> <span></span></a>
                    <?php endif; ?>

                </div>

            </div>

        </div>
    </div><!-- end container -->
</section>