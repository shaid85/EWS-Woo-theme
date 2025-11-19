<?php if (have_rows('mega_menu_list', 'options')): ?>
    <nav class="main-menu megamenu">
        <div class="mobile_love d-block d-xl-none">
            <div class="icon-box ">
                <a href="/wishlist/" class="love-btn2 love_prd">
                    <div class="icon-holder">
                        <img data-src="<?php echo get_template_directory_uri(); ?>/assets/images/favorite2.png" class="default ls-is-cached lazyloaded" alt="icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/favorite2.png">
                        <img data-src="<?php echo get_template_directory_uri(); ?>/assets/images/favorite22.png" class="added lazyload" alt="icon" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
                    </div>
                </a>
            </div>
        </div>
        <ul class="top_nav menu">
            <?php while (have_rows('mega_menu_list', 'options')) : the_row();
                // Case: Pages layout. 
                if (get_row_layout() == 'add_menu_from_page'):
                    $menu_tab = get_sub_field('menu_tab');

            ?>
                    <li>
                        <?php
                        if ($menu_tab): ?>
                            <a href="<?php echo $menu_tab['url']; ?>" class="menu_tab"><?php echo $menu_tab['title']; ?></a>
                        <?php endif; ?>
                    </li>
                <?php    // Case: Category layout.
                elseif (get_row_layout() == 'add_menu_from_category'):
                    $menu_tab = get_sub_field('menu_tab');

                ?>
                    <li>
                        <?php
                        if ($menu_tab): ?>
                            <a href="<?php echo esc_url(get_term_link($menu_tab)); ?>" class="menu_tab"><?php echo esc_html($menu_tab->name); ?></a>
                        <?php endif; ?>
                    </li>

                <?php    // Case: Pages with dropdown layout.
                elseif (get_row_layout() == 'add_dropdown_menu_pages'):
                    $menu_tab = get_sub_field('menu_tab_page_link');
                    $sub_menu_tab = get_sub_field('select_pages_for_dropdown');
                ?>
                    <li class="has-sub">
                        <?php
                        if ($menu_tab): ?>
                            <a href="<?php echo $menu_tab['url']; ?>" class="menu_tab"><?php echo $menu_tab['title']; ?></a>
                        <?php endif; ?>
                        <a class="dropdown_icon" href="#"></a>
                        <ul class="dropdown_box">
                            <?php foreach ($sub_menu_tab as $post):
                                setup_postdata($post);

                            ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <?php
                            // Reset the global post object so that the rest of the page works correctly.
                            wp_reset_postdata();
                            ?>
                        </ul>
                    </li>

                <?php    // Case: Product Category with dropdown layout.
                elseif (get_row_layout() == 'add_dropdown_menu_categories'):
                    $menu_tab_cat = get_sub_field('menu_tab_cat_link');
                    $sub_menu_tabs = get_sub_field('select_categories_for_dropdown');
                ?>
                    <li class="has-sub">
                        <?php
                        if ($menu_tab_cat): ?>
                            <a href="<?php echo esc_url(get_term_link($menu_tab_cat)); ?>" class="menu_tab"><?php echo esc_html($menu_tab_cat->name); ?></a>
                        <?php endif; ?>
                        <a class="dropdown_icon" href="#"></a>
                        <ul class="dropdown_box">
                            <?php foreach ($sub_menu_tabs as $term):

                            ?>
                                <li>
                                    <a href="<?php echo esc_url(get_term_link($term)); ?>">
                                        <?php echo esc_html($term->name); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>

                <?php    // Case: Megamenu - Product Category layout.
                elseif (get_row_layout() == 'add_megamenu_dropdown'):
                    $menu_tab_cat = get_sub_field('menu_tab_any_link');
                ?>
                    <li class="has-sub <?php echo $menu_tab_cat['title']; ?>">
                        <?php
                        if ($menu_tab_cat): ?>
                            <a href="<?php echo $menu_tab_cat['url']; ?>" class="menu_tab"><?php echo $menu_tab_cat['title']; ?></a>
                        <?php endif; ?>
                        <a class="dropdown_icon" href="#"></a>
                        <?php
                        // Flexible Content
                        if (have_rows('create_megamenu_columns', 'options')): ?>
                            <ul class="dropdown_box megamenu_box">
                                <div class="mm_inner container">
                                    <?php while (have_rows('create_megamenu_columns', 'options')): the_row(); ?>
                                        <?php    // Case: Megamenu - Column layout.
                                        if (get_row_layout() == 'menu_list_l2'):
                                            $secect_to_active = get_sub_field('secect_to_active');
                                            $menu_tab_cat = get_sub_field('category_heading_item');
                                            $custom_link = get_sub_field('custom_link');
                                            $categories_list = get_sub_field('categories_list');
                                            $brands_list = get_sub_field('brands_list');
                                            $new_product = get_sub_field('nauji_modeliai');
                                        ?>

                                            <li class="cat_head">

                                                <?php if ($secect_to_active == 'Category'): ?>
                                                    <?php if ($menu_tab_cat): ?>
                                                        <a href="<?php echo esc_url(get_term_link($menu_tab_cat)); ?>"><?php echo esc_html($menu_tab_cat->name); ?></a>
                                                    <?php endif; ?>
                                                    <?php if ($custom_link): ?>
                                                        <?php if ($custom_link['url'] != '#'): ?>
                                                            <a href="<?php echo $custom_link['url']; ?>">
                                                                <?php echo $custom_link['title']; ?>
                                                            </a>
                                                        <?php else: ?>
                                                            <h3><?php echo $custom_link['title']; ?></h3>
                                                        <?php endif; ?>
                                                        <a class="dropdown_icon" href="#"></a>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <?php if ($custom_link): ?>
                                                        <?php if ($custom_link['url'] != '#'): ?>
                                                            <a href="<?php echo $custom_link['url']; ?>">
                                                                <?php echo $custom_link['title']; ?>
                                                            </a>
                                                        <?php else: ?>
                                                            <h3><?php echo $custom_link['title']; ?></h3>
                                                        <?php endif; ?>
                                                        <a class="dropdown_icon" href="#"></a>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <ul>
                                                    <?php if ($secect_to_active == 'Category'):

                                                        if ($categories_list): ?>
                                                            <?php foreach ($categories_list as $term):
                                                            ?>
                                                                <li>
                                                                    <a href="<?php echo esc_url(get_term_link($term)); ?>"><?php echo esc_html($term->name); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                            <!-- <li><a href="<?php // echo site_url(); 
                                                                                ?>/shop">Žiūrėti visas <img src="<?php //  bloginfo('stylesheet_directory'); 
                                                                                                                    ?>/assets/images/arrow-east.png" width=24 alt="arrow-image" /></a></li> -->
                                                        <?php endif; ?>

                                                        <?php elseif ($secect_to_active == 'Brand'):

                                                        if ($brands_list): ?>
                                                            <?php foreach ($brands_list as $term):
                                                            ?>
                                                                <li>
                                                                    <a href="<?php echo esc_url(get_term_link($term)); ?>"><?php echo esc_html($term->name); ?></a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>

                                                        <?php else:
                                                        if ($new_product): ?>
                                                            <?php foreach ($new_product as $post):
                                                                setup_postdata($post);
                                                            ?>
                                                                <li class="new_item item">
                                                                    <!-- <a href="<?php // echo site_url(); 
                                                                                    ?>/product-tag/nuolaida/"><strong>Naujiena </strong></a> -->
                                                                    <a href="<?php the_permalink(); ?>" class="m-0">

                                                                        <div class="default_show2">
                                                                            <?php
                                                                            $product_title = get_the_title();
                                                                            // Trim the title to 50 characters and add "..." if it's too long
                                                                            $trimmed_title = mb_strimwidth($product_title, 0, 40, '...');
                                                                            echo $trimmed_title;
                                                                            ?>
                                                                        </div>
                                                                    </a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                            <?php
                                                            // Reset the global post object so that the rest of the page works correctly.
                                                            wp_reset_postdata(); ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </ul>
                                            </li>



                                        <?php    // Case: Content layout.
                                        elseif (get_row_layout() == 'content'):
                                            $col_image = get_sub_field('col_image');
                                            $text_content = get_sub_field('text_content');
                                            $link_1 = get_sub_field('link_1');
                                            $newest_post = get_sub_field('newest_post');
                                        ?>
                                            <li class="mm_img_box">
                                                <div class="img_col">
                                                    <?php if ($link_1): ?>
                                                        <a href="<?php echo esc_url($link_1); ?>">
                                                        <?php endif; ?>
                                                        <?php
                                                        if (!empty($col_image)): ?>
                                                            <div class="firstimg mb-2">
                                                                <img src="<?php echo esc_url($col_image['url']); ?>" class="fixed-size-img" alt="<?php echo esc_attr($col_image['alt']); ?>" />
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php
                                                        if ($text_content): ?>
                                                            <div class="text first mb-4">
                                                                <div class="default_show2">
                                                                    <?php
                                                                    // Trim the title to 50 characters and add "..." if it's too long
                                                                    $trimmed_title = mb_strimwidth($text_content, 0, 68, '...');
                                                                    echo $trimmed_title;
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ($link_1): ?>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="img_col pb-1">
                                                    <?php if ($newest_post):
                                                        $thumbnail_url = get_the_post_thumbnail_url($newest_post->ID, 'medium'); // or 'full' 'medium', 'thumbnail', etc.
                                                        $post_url = get_the_permalink($newest_post->ID);
                                                    ?>
                                                        <a href="<?php echo esc_url($post_url); ?>">
                                                            <div class="firstimg last mb-2">
                                                                <?php if ($thumbnail_url): ?>
                                                                    <img src="<?php echo esc_url($thumbnail_url); ?>" class="fixed-size-img" alt="<?php echo esc_attr($featured_post->post_title); ?>" />
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="text last">
                                                                <?php // echo esc_html($newest_post->post_title); 
                                                                ?>
                                                                <div class="default_show2">
                                                                    <?php
                                                                    $post_title2 = $newest_post->post_title;
                                                                    // Trim the title to 50 characters and add "..." if it's too long
                                                                    $trimmed_title2 = mb_strimwidth($post_title2, 0, 68, '...');
                                                                    echo $trimmed_title2;
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    <?php else: ?>
                                                        <?php
                                                        $args = array(
                                                            'post_type'      => 'post',
                                                            'posts_per_page' => 1,
                                                            'post_status'    => 'publish',
                                                        );

                                                        $latest_query = new WP_Query($args);

                                                        if ($latest_query->have_posts()) :
                                                            while ($latest_query->have_posts()) : $latest_query->the_post();
                                                                $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium'); // or 'full', 'large', etc.
                                                        ?>
                                                                <div class="firstimg last mb-2">
                                                                    <?php if ($thumbnail_url): ?>
                                                                        <a href="<?php the_permalink(); ?>">
                                                                            <img src="<?php echo esc_url($thumbnail_url); ?>" class="fixed-size-img" alt="<?php the_title_attribute(); ?>">
                                                                        </a>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="text last">

                                                                    <div class="default_show2">
                                                                        <a href="<?php the_permalink(); ?>">
                                                                            <?php
                                                                            $post_title2 = get_the_title();
                                                                            // Trim the title to 50 characters and add "..." if it's too long
                                                                            $trimmed_title2 = mb_strimwidth($post_title2, 0, 68, '...');
                                                                            echo $trimmed_title2;
                                                                            ?>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                        <?php
                                                            endwhile;
                                                            wp_reset_postdata();
                                                        endif;
                                                        ?>

                                                    <?php endif; ?>

                                                </div>
                                            </li>
                                        <?php    // Case: Megamenu - Column layout.
                                        elseif (get_row_layout() == 'menu_list_custom'):
                                            $heading_1 = get_sub_field('heading_of_category');
                                            $heading_2 = get_sub_field('heading_of_category_2');
                                            $heading_3 = get_sub_field('heading_of_category_3');
                                            $menu_list_1 = get_sub_field('select_menu_from_list_1');
                                            $menu_list_2 = get_sub_field('select_menu_from_list_2');
                                            $menu_list_3 = get_sub_field('select_menu_from_list_3');

                                            $parsed_url = parse_url($menu_tab_cat['url'], PHP_URL_PATH); // Get path from URL
                                            $path_parts = explode('/', trim($parsed_url, '/')); // Split and trim the path
                                            $last_slug = end($path_parts); // Get the last slug

                                            // echo $last_slug;
                                        ?>
                                            <li class="cat_head">
                                                <?php if ($heading_1) {  ?>
                                                    <h3><?php echo $heading_1; ?></h3>
                                                    <?php if ($menu_list_1) {
                                                        // echo $menu_list_1;
                                                        if ($menu_list_1 == 'test-menu' || $menu_list_1 == 'gamintojas' || $menu_list_1 == 'gamintojas-avalyne') {
                                                            echo get_custom_menu_with_kategorija($menu_list_1, [], $last_slug);
                                                        } else {
                                                            wp_nav_menu(array(
                                                                'menu' => $menu_list_1,
                                                                'container' => '',
                                                                'menu_class' => 'custom_cat'
                                                            ));
                                                        }
                                                    } ?>
                                                <?php } ?>
                                                <?php if ($heading_2) {  ?>
                                                    <h3><?php echo $heading_2; ?></h3>
                                                    <?php if ($menu_list_1) {
                                                        // echo $menu_list_2;
                                                        if ($menu_list_2 == 'test-menu' || $menu_list_2 == 'gamintojas' || $menu_list_2 == 'gamintojas-avalyne') {
                                                            echo get_custom_menu_with_kategorija($menu_list_2, [], $last_slug);
                                                        } else {
                                                            wp_nav_menu(array(
                                                                'menu' => $menu_list_2,
                                                                'container' => '',
                                                                'menu_class' => 'custom_cat'
                                                            ));
                                                        }
                                                    } ?>
                                                <?php } ?>
                                                <?php if ($heading_3) {  ?>
                                                    <h3><?php echo $heading_3; ?></h3>
                                                    <?php if ($menu_list_3) {
                                                        wp_nav_menu(array(
                                                            'menu' => $menu_list_3,
                                                            'container' => '',
                                                            'menu_class' => 'custom_cat'
                                                        ));
                                                    } ?>
                                                <?php } ?>
                                            </li>

                                        <?php endif; ?><!-- end layout -->

                                    <?php endwhile; ?>
                                </div><!-- end mega_m inner -->
                            </ul>
                        <?php endif; ?><!-- end dropdown flexiable content -->

                    </li>

                <?php endif; // end layout 
                ?>
            <?php endwhile; ?>
        </ul>

        <div class="mobile_only mt-4">
            <div class="info-wrap flex-column gap-2">
                <?php if (get_field('phone_number', 'option')) { ?>
                    <div class="info">
                        <div class="icon icon40">
                            <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/call2.png" alt="icon-image" class="st_wht2" />
                        </div>
                        <div class="text">
                            <a href="tel:<?php echo esc_html(get_field('phone_number', 'option')); ?>"><?php echo esc_html(get_field('phone_number', 'option')); ?></a>
                        </div>
                    </div> <!--  end info -->
                <?php   }; ?>
                <?php if (get_field('mail_id', 'option')) { ?>
                    <div class="info gap-2">
                        <div class="icon icon40">
                            <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/mail2.png" alt="icon-image" class="st_wht2" />
                        </div>
                        <div class="text">
                            <a href="mailto:<?php echo esc_html(get_field('mail_id', 'option')); ?>"><?php echo esc_html(get_field('mail_id', 'option')); ?></a>
                        </div>
                    </div> <!--  end info -->
                <?php   }; ?>
            </div> <!--  end info-wrap -->

            <nav class="topbar_m mb-3">
                <?php $topbar_selected_menu = get_field('topbar_menu_select', 'option');

                if ($topbar_selected_menu) {
                    wp_nav_menu(array(
                        'menu' => $topbar_selected_menu,
                        'container' => 'nav',
                        'menu_class' => 'custom-menu'
                    ));
                } ?>

            </nav> <!--  end info-wrap -->
        </div>

    </nav>
<?php endif; ?>

<style>
    .nav-container .checkbox {
        position: absolute;
        display: block;
        height: 32px;
        width: 32px;
        top: 20px;
        left: 20px;
        z-index: 9999;
        opacity: 0;
        cursor: pointer;
    }

    .nav-container .hamburger-lines {
        display: block;
        height: 16px;
        width: 20px;
        position: absolute;
        top: 17px;
        left: 20px;
        z-index: 999;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 1px 0;
    }

    .nav-container .hamburger-lines .line {
        display: block;
        height: 1px;
        width: 100%;
        border-radius: 10px;
        background: #000;
    }

    .header_overly .nav-container .hamburger-lines .line {
        background: #fff;
    }

    .nav-container .hamburger-lines .line1 {
        transform-origin: 0% 0%;
        transition: transform 0.4s ease-in-out;
    }

    .nav-container .hamburger-lines .line2 {
        transition: transform 0.2s ease-in-out;
    }

    .nav-container .hamburger-lines .line3 {
        transform-origin: 0% 100%;
        transition: transform 0.4s ease-in-out;
    }


    @media (max-width: 1200px) {}

    .nav-container input[type="checkbox"]:checked~.main-menu {
        transform: translateX(0);
    }

    .main-menu.open {
        transform: translateX(0);
    }

    .nav-container input[type="checkbox"]:checked~.hamburger-lines .line1 {
        transform: rotate(45deg);
    }

    .nav-container input[type="checkbox"]:checked~.hamburger-lines .line2 {
        transform: scaleY(0);
    }

    .nav-container input[type="checkbox"]:checked~.hamburger-lines .line3 {
        transform: rotate(-45deg);
    }
</style>