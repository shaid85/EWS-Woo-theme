<?php
$section_title = get_sub_field('section_title');
$background_color = get_sub_field('background_color');
$add_custom_class = get_sub_field('add_custom_class');

$form_shortcode = get_sub_field('form_shortcode_here');
?>
<section id="Contact_form" class="contact_form_sec <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">
    <div class="container">
        <?php if ($section_title) : ?>
            <h1 class="ews_title mb-5 pb-md-3 text-center">
                <?php echo acf_esc_html($section_title); ?>
            </h1>
        <?php endif; ?>

        <div class="row justify-content-center con_form_st">
            <div class="col-md-8"><?php echo do_shortcode($form_shortcode); ?></div>
        </div>
    </div>
</section>