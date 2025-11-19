<?php
$section_title = get_sub_field('section_title');
$background_color = get_sub_field('background_color');
$add_custom_class = get_sub_field('add_custom_class');
?>
<section id="faq_section" class="faq_section <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">
    <div class="container">

        <div class="row ">
            <div class="col-md-5">
                <div class="content_box">
                    <?php if ($section_title) : ?>
                        <h3 class="ews_title my-3">
                            <?php echo acf_esc_html($section_title); ?>
                        </h3>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-7">
                <div class="content_box">
                    <!-- Accordion -->
                    <div class="faq_box">
                        <?php
                        if (have_rows('faq_content')):

                            while (have_rows('faq_content')) : the_row();

                                $faq_heading = get_sub_field('faq_heading');
                                $faq_description = get_sub_field('faq_description');

                        ?>
                                <div class="faq-item">
                                    <button class="faq-question"><?php echo esc_html($faq_heading); ?></button>
                                    <div class="faq-answer">
                                        <p><?php echo esc_html($faq_description); ?></p>
                                    </div>
                                </div>
                                <!-- Add more FAQ items as needed -->
                        <?php
                            endwhile;
                        endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>