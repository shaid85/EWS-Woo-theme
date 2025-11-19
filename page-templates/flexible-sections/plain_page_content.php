<?php
$section_title = get_sub_field('section_title');
$background_color = get_sub_field('background_color');
$add_custom_class = get_sub_field('add_custom_class');

$page_content = get_sub_field('page_content');
?>
<section id="plain_page_content" class="contact_form_sec <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">
    <div class="container">
        <?php if ($section_title) : ?>
            <div class="page_header mb-5">
                <h2 class="ews_title text-center">
                    <?php echo acf_esc_html($section_title); ?>
                </h2>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12 px-lg-5">
                <div class="page_content">
                    <?php echo $page_content; ?>
                </div>

            </div>
        </div>
    </div>
</section>