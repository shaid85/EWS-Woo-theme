<?php
$section_title = get_sub_field('section_title');
$background_color = get_sub_field('background_color');
$add_custom_class = get_sub_field('add_custom_class');
?>
<section id="contact_info_section" class="contact_info_box <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">
    <div class="container">
        <?php if ($section_title) : ?>
            <h2 class="ews_title mb-5">
                <?php echo acf_esc_html($section_title); ?>
            </h2>
        <?php endif; ?>
        <div class="row align-items-end  gap-5 gap-md-0">
            <div class="col-md-6 col-xl-4">
                <?php
                $left_box = get_sub_field('left_content_box');
                ?>
                <div class="content_box">
                    <?php if ($left_box['title']): ?>
                        <h3><?php echo esc_html($left_box['title']); ?></h3>
                    <?php endif; ?>
                    <?php if ($left_box['description']): ?>
                        <p><?php echo esc_html($left_box['description']); ?></p>
                    <?php endif; ?>

                    <div class="info-wrap style-2 mt-4">

                        <?php if (get_field('phone_number', 'option')) { ?>
                            <div class="info">
                                <div class="icon">
                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/call_con.png" alt="icon-image" />
                                </div>
                                <div class="text">
                                    <a href="tel:<?php echo esc_html(get_field('phone_number', 'option')); ?>"><?php echo esc_html(get_field('phone_number', 'option')); ?></a>
                                </div>
                            </div> <!--  end info -->
                        <?php   }; ?>

                        <?php if (get_field('mail_id', 'option')) { ?>
                            <div class="info">
                                <div class="icon">
                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/mail_con.png" alt="icon-image" />
                                </div>
                                <div class="text">
                                    <a href="mailto:<?php echo esc_html(get_field('mail_id', 'option')); ?>"><?php echo esc_html(get_field('mail_id', 'option')); ?></a>
                                </div>
                            </div> <!--  end info -->
                        <?php   }; ?>
                        <?php if (get_field('location', 'option')) { ?>
                            <div class="info">
                                <div class="icon">
                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/location_con.png" alt="icon-image" />
                                </div>
                                <div class="text">
                                    <p class="text-16 m-0"><a href="https://maps.app.goo.gl/x2FFAzKcX6nNnT6u8" target="_blank"><?php echo esc_html(get_field('location', 'option')); ?> </a></p>
                                </div>
                            </div> <!--  end info -->
                        <?php   }; ?>


                    </div> <!--  end info-wrap -->

                    <?php if ($left_box['button_text']): ?>
                        <a class="btn btn-primary bg-white text-black see_more mt-2" href="#Contact_form"><?php echo esc_html($left_box['button_text']); ?> <span></span></a>
                    <?php endif; ?>

                </div>
            </div>
            <div class="col-md-6 col-xl-8 ps-xl-5">
                <?php
                $right_box = get_sub_field('right_content_box');
                ?>
                <div class="content_box ms-md-4">
                    <?php if ($right_box['image']): ?>
                        <img src="<?php echo esc_url($right_box['image']);
                                    ?>" />
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>