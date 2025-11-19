<?php
$section_title = get_sub_field('section_title');
$background_color = get_sub_field('background_color');
$add_custom_class = get_sub_field('add_custom_class');

$full_height_image = get_sub_field('full_height_image');
?>
<section id="need_help_section" class="help_box bg-white <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">
    <div class="container-fluid gx-0">
        <div class="row align-items-center gx-0">
            <div class="col-md-6 py-4 py-md-0">
                <?php
                $left_box = get_sub_field('left_content_box');
                ?>
                <div class="text_content p-md-5 p-3 mx-xxl-5">
                    <?php if ($left_box['description']): ?>
                        <?php echo wp_kses_post($left_box['description']); ?>
                    <?php endif; ?>

                    <?php if ($left_box['button']): ?>
                        <div class="button mb-md-1 mb-2">
                            <a class="arrow_btn btn black_btn mt-4" href="<?php echo esc_url($left_box['button']['url']); ?>"><?php echo esc_html($left_box['button']['title']); ?><span></span></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <?php if ($full_height_image): ?>
                    <img src="<?php echo esc_url($full_height_image['url']); ?>" alt="image" />
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>