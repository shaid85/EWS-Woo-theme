<?php
$section_title = get_sub_field('section_title');
$background_color = get_sub_field('background_color');
$add_custom_class = get_sub_field('add_custom_class');
$columns_vertical = get_sub_field('columns_vertical');
?>
<section id="2_column_contentboth" class="two_col_box <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">
    <div class="container">
        <?php if ($section_title) : ?>
            <h2 class="ews_title">
                <?php echo acf_esc_html($section_title); ?>
            </h2>
        <?php endif; ?>
        <div class="row gap-4 gap-md-0 <?php echo esc_html($columns_vertical); ?>">
            <div class="col-md-6">
                <?php
                $left_box = get_sub_field('left_content_box');
                $show_content = $left_box['show_content'] ?? [];
                ?>
                <div class="content_box">
                    <?php if ($left_box['title'] && in_array('Title', $show_content)): ?>
                        <h2><?php echo esc_html($left_box['title']); ?></h2>
                    <?php endif; ?>
                    <?php if ($left_box['short_description'] && in_array('Short Description', $show_content)): ?>
                        <?php echo wp_kses_post($left_box['short_description']); ?>
                    <?php endif; ?>
                    <?php if ($left_box['description'] && in_array('Description', $show_content)): ?>
                        <?php echo wp_kses_post($left_box['description']); ?>
                    <?php endif; ?>
                    <?php if ($left_box['button'] && in_array('Button', $show_content)): ?>
                        <a href="<?php echo esc_url($left_box['button']['url']); ?>"><?php echo esc_html($left_box['button']['title']); ?></a>
                    <?php endif; ?>
                    <?php if ($left_box['image'] && in_array('Image', $show_content)): ?>
                        <img src="<?php echo esc_url($left_box['image']); ?>" alt="image" />
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <?php
                $right_box = get_sub_field('right_content_box');
                $show_content2 = $right_box['show_content_copy'] ?? [];
                // print_r($show_content2);
                ?>
                <div class="content_box">
                    <?php if ($right_box['title'] && in_array('Title', $show_content2)): ?>
                        <h2><?php echo esc_html($right_box['title']); ?></h2>
                    <?php endif; ?>
                    <?php if ($right_box['short_description'] && in_array('Short Description', $show_content2)): ?>
                        <?php echo wp_kses_post($right_box['short_description']); ?>
                    <?php endif; ?>
                    <?php if ($right_box['description'] && in_array('Description', $show_content2)): ?>
                        <?php echo $right_box['description']; ?>

                    <?php endif; ?>
                    <?php if ($right_box['button'] && in_array('Button', $show_content2)): ?>
                        <a href="<?php echo esc_url($right_box['button']['url']); ?>"><?php echo esc_html($right_box['button']['title']); ?></a>
                    <?php endif; ?>
                    <?php if ($right_box['image'] && in_array('Image', $show_content2)): ?>
                        <div class="ps-xl-4">
                            <img src="<?php echo esc_url($right_box['image']); ?>" />
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>