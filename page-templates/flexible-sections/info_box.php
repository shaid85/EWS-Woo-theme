<?php
$info_box_style = get_sub_field('info_box_style');
$section_title = get_sub_field('section_title');
$background_image = get_sub_field('background_image');
$background_color = get_sub_field('background_color');
$text_color = get_sub_field('text_color');
$padding_up = get_sub_field('padding_up');
$padding_down = get_sub_field('padding_down');
$padding_down = get_sub_field('padding_down');
$add_custom_class = get_sub_field('add_custom_class');
?>
<section class="iconbox_section <?php echo acf_esc_html($add_custom_class); ?> <?php echo $info_box_style; ?>" style="background-color:<?php echo $background_color; ?>;">
    <div class="container">
        <?php if ($section_title) : ?>
            <h2 class="ews_title text-center mb-4 mb-md-5">
                <?php echo acf_esc_html($section_title); ?>
            </h2>
        <?php endif; ?>

        <div class="icon_list <?php if ($info_box_style == 'style-2') {
                                    echo 's2';
                                }; ?>">
            <?php
            if (have_rows('info_content')):

                while (have_rows('info_content')) : the_row();

                    $image = get_sub_field('image');
                    $title = get_sub_field('title');
                    $description = get_sub_field('description');

            ?>
                    <div class="info">
                        <div class="icon">
                            <?php if ($image) : ?>
                                <img src="<?php echo esc_url($image['url']); ?>" alt="icon-image" />
                            <?php else: ?>
                                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/list-Grazinimas.png" alt="icon-image" />
                            <?php endif; ?>
                        </div>
                        <div class="text">
                            <?php if ($title) : ?>
                                <h3><?php echo acf_esc_html($title); ?></h3>
                            <?php endif; ?>
                            <?php if ($description) : ?>
                                <p><?php echo acf_esc_html($description); ?></p>
                            <?php endif; ?>
                        </div>
                    </div> <!--  end info -->
                <?php
                endwhile;
            else : ?>
                <div class="info">
                    <div class="icon">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/list-Grazinimas.png" alt="icon-image" />
                    </div>
                    <div class="text">
                        <h3>Nemokamas grąžinimas</h3>
                        <p>Visoje Lietuvoje</p>
                    </div>
                </div> <!--  end info -->
                <div class="info">
                    <div class="icon">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/llist-Saugus apmokejimas.png" alt="icon-image" />
                    </div>
                    <div class="text">
                        <h3>Saugus atsiskaitymas</h3>
                        <p>SLL mokėjimų apsauga</p>
                    </div>
                </div> <!--  end info -->
                <div class="info">
                    <div class="icon">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/list-Delivery.png" alt="icon-image" />
                    </div>
                    <div class="text">
                        <h3>Nemokamas pristatymas</h3>
                        <p>Perkant nuo 99 EUR</p>
                    </div>
                </div> <!--  end info -->
                <div class="info">
                    <div class="icon">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/list-Stygavimas.png" alt="icon-image" />
                    </div>
                    <div class="text">
                        <h3>Individualus stygavimas</h3>
                        <p>Geriausiems rezultatams</p>
                    </div>
                </div> <!--  end info -->
            <?php endif; ?>

        </div>
    </div>
</section>