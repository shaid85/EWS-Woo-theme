<?php
/*
* This template for displaying the header
*/
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>


    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-MX7CV3P4D5"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());



        gtag('config', 'G-MX7CV3P4D5');
    </script>
</head>

<body <?php body_class(); ?>>

    <div id="page" class="site">
        <div class="header_wrap <?php echo esc_html(get_field('select_header_style')); ?>"> <!-- header_overly -->
            <?php if (!is_checkout()): ?>
                <div class="top_bar d-md-block d-none">
                    <div class="container-xxl">
                        <div class="row justify-content-between">
                            <div class="col-md-6">
                                <div class="info-wrap">
                                    <?php if (get_field('phone_number', 'option')) { ?>
                                        <div class="info">
                                            <div class="icon icon40">
                                                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/call.png" alt="icon-image" class="st_trp" />
                                                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/call2.png" alt="icon-image" class="st_wht" />
                                            </div>
                                            <div class="text">
                                                <a href="tel:<?php echo esc_html(get_field('phone_number', 'option')); ?>"><?php echo esc_html(get_field('phone_number', 'option')); ?></a>
                                            </div>
                                        </div> <!--  end info -->
                                    <?php   }; ?>
                                    <?php if (get_field('mail_id', 'option')) { ?>
                                        <div class="info gap-2">
                                            <div class="icon icon40">
                                                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/mail.png" alt="icon-image" class="st_trp" />
                                                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/mail2.png" alt="icon-image" class="st_wht" />
                                            </div>
                                            <div class="text">
                                                <a href="mailto:<?php echo esc_html(get_field('mail_id', 'option')); ?>"><?php echo esc_html(get_field('mail_id', 'option')); ?></a>
                                            </div>
                                        </div> <!--  end info -->
                                    <?php   }; ?>
                                </div> <!--  end info-wrap -->

                            </div> <!--  end col -->
                            <div class="col-md-6">
                                <nav class="top-menu">
                                    <?php $topbar_selected_menu = get_field('topbar_menu_select', 'option');

                                    if ($topbar_selected_menu) {
                                        wp_nav_menu(array(
                                            'menu' => $topbar_selected_menu,
                                            'container' => 'nav',
                                            'menu_class' => 'custom-menu'
                                        ));
                                    } ?>

                                </nav> <!--  end info-wrap -->
                            </div> <!--  end col -->

                        </div> <!--  end row -->
                    </div> <!--  end container -->
                </div> <!-- end topbar -->
                <header class="site-header ">
                    <div class="container-xxl">
                        <div class="row align-items-center justify-content-between flex-xl-nowrap">
                            <div class="col-8 col-xl-3 order-xl-1">
                                <div class="logo">
                                    <a href="<?php echo site_url(); ?>">
                                        <?php
                                        $logo_primary = get_field('logo_primary', 'option');
                                        if (is_array($logo_primary) && !empty($logo_primary['url'])): ?>
                                            <img src="<?php echo esc_url($logo_primary['url']); ?>" alt="logo-image" class="st_wht" />
                                        <?php endif; ?>
                                        <?php
                                        $logo_white = get_field('logo_white', 'option');
                                        if (!empty($logo_white['url'])): ?>
                                            <img src="<?php echo esc_url($logo_white['url']); ?>" alt="logo-image" class="st_trp" />
                                        <?php endif; ?>
                                    </a>

                                </div>
                            </div>

                            <div class="col-4 col-xl-3 d-flex justify-content-end column-gap-3 order-xl-3 position-relative">
                                <div class="icon-box">
                                    <a href="/paskyra">
                                        <div class="icon-holder <?php is_user_logged_in() ? $cls = 'logged_in2' : $cls = 'none';
                                                                echo  $cls; ?>">
                                            <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/user.png" alt="icon" class="st_trp" />
                                            <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/user2.png" alt="icon" class="st_wht" />
                                        </div>
                                    </a>

                                </div>
                                <div class="icon-box d-none d-md-block">
                                    <a href="/patikusios-prekes" class="wishlist-link">
                                        <div class="icon-holder">
                                            <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/favorite.png" alt="icon" class="st_trp" />
                                            <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/favorite2.png" alt="icon" class="st_wht" />
                                        </div>
                                    </a>
                                    <span id="loveCount" class="love-total d-none">0</span>
                                </div>
                                <?php if (! is_cart() && ! is_checkout()) : ?>
                                    <div class="icon-box">
                                        <a class="cart-contents" href="<?php echo wc_get_cart_url(); ?>">
                                            <div class="icon-holder">
                                                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/cart.png" alt="icon" class="st_trp" />
                                                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/cart2.png" alt="icon" class="st_wht" />
                                            </div>
                                        </a>

                                        <?php
                                        $count = WC()->cart->get_cart_contents_count();
                                        if ($count > 0) : ?>
                                            <span id="cartCount" class="count head"><?php echo $count; ?></span>
                                        <?php else: ?>
                                            <span id="cartCount" class="count d-none"><?php echo $count; ?></span>
                                        <?php endif; ?>

                                    </div>
                                    <?php echo do_shortcode('[popup_minicart]');
                                    ?>
                                <?php endif; ?>
                            </div>

                            <div class="col-12 col-xl-6 order-xl-2">
                                <div class="search-bar">
                                    <form role="search" method="get" id="search-form" class="woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
                                        <input type="search" id="product-search" class="search-field" placeholder="<?php echo esc_attr__('Ieškokite produktų', 'woocommerce'); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
                                        <input type="hidden" name="post_type" value="product" />
                                        <button type="button" class="clear-btn" style="display: none;" aria-label="Clear search">
                                            <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/close.png" width="20" alt="icon" />
                                        </button>
                                        <button type="submit" hidden aria-hidden="true"></button>
                                    </form>
                                    <div id="search-results" class="search-popup">
                                        <!-- <div class="recent_search">
                                        <?php // echo do_shortcode('[wp_recent_searches]'); 
                                        ?>
                                    </div> -->
                                    </div>
                                </div>
                            </div>
                            <script>
                                const searchInput = document.getElementById('product-search');
                                const search_popup = document.getElementById('search-results');
                                const clearBtn = document.querySelector('.clear-btn');
                                const close_spopup = document.querySelector('.close_spopup');

                                searchInput.addEventListener('input', () => {
                                    clearBtn.style.display = searchInput.value ? 'block' : 'none';
                                });

                                clearBtn.addEventListener('click', () => {
                                    searchInput.value = '';
                                    clearBtn.style.display = 'none';
                                    searchInput.focus();
                                    search_popup.style.display = 'none';
                                });
                            </script>

                        </div><!-- end row -->

                        <div class="row">
                            <div class="col-md-12">
                                <div id="mobile_menu" class="mobile_menu nav-container d-xl-none">
                                    <input class="checkbox" type="checkbox" name="" id="menu-toggle" />
                                    <div class="hamburger-lines">
                                        <span class="line line1"></span>
                                        <span class="line line2"></span>
                                        <span class="line line3"></span>
                                    </div>
                                </div> <!-- end nav-container -->
                                <?php if (get_field('use_mega_menu', 'option')): ?>
                                    <?php get_template_part('inc/megamenu'); ?>
                                <?php else: ?>
                                    <nav class="main-menu">
                                        <?php wp_nav_menu(
                                            array(
                                                'theme_location' => 'ewsdev_main_menu',
                                                'depth' => 2
                                            )
                                        ); ?>
                                    </nav>
                                <?php endif; ?>


                            </div>
                        </div><!-- end row -->

                    </div>
                </header>
                <div class="mobile_menu_wrap d-none">
                    <nav class="main-menu mobile_menu">
                        <?php wp_nav_menu(
                            array(
                                'theme_location' => 'ewsdev_main_menu',
                                'depth' => 2
                            )
                        ); ?>
                    </nav>
                </div>
            <?php endif; ?>
            <?php if (is_checkout()): ?>
                <div class="admin_header">
                    <header class="site-header ">
                        <div class="container-xxl">
                            <div class="row align-items-center justify-content-between flex-xl-nowrap">
                                <div class="col-6 col-xl-6">
                                    <div class="logo d-flex align-items-center gap-3">
                                        <?php if (is_checkout() && !is_order_received_page()): ?>
                                            <div class="icon-box">
                                                <a href="/cart">
                                                    <div class="icon-holder">
                                                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/button-left-icon.png" alt="icon" class="st_wht" />
                                                    </div>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <a href="<?php echo site_url(); ?>">
                                            <?php
                                            $logo_primary = get_field('logo_primary', 'option');
                                            if (is_array($logo_primary) && !empty($logo_primary['url'])): ?>
                                                <img src="<?php echo esc_url($logo_primary['url']); ?>" alt="logo-image" class="st_wht" />
                                            <?php endif; ?>
                                            <?php
                                            $logo_white = get_field('logo_white', 'option');
                                            if (!empty($logo_white['url'])): ?>
                                                <img src="<?php echo esc_url($logo_white['url']); ?>" alt="logo-image" class="st_trp" />
                                            <?php endif; ?>
                                        </a>

                                    </div>
                                </div>

                                <div class="col-md-6 d-none d-md-block">
                                    <div class="info-wrap">
                                        <?php if (get_field('phone_number', 'option')) { ?>
                                            <div class="info">
                                                <div class="icon icon40">
                                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/call.png" alt="icon-image" class="st_trp" />
                                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/call2.png" alt="icon-image" class="st_wht" />
                                                </div>
                                                <div class="text">
                                                    <a href="tel:<?php echo esc_html(get_field('phone_number', 'option')); ?>"><?php echo esc_html(get_field('phone_number', 'option')); ?></a>
                                                </div>
                                            </div> <!--  end info -->
                                        <?php   }; ?>
                                        <?php if (get_field('mail_id', 'option')) { ?>
                                            <div class="info">
                                                <div class="icon icon40">
                                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/mail.png" alt="icon-image" class="st_trp" />
                                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/mail2.png" alt="icon-image" class="st_wht" />
                                                </div>
                                                <div class="text">
                                                    <a href="mailto:<?php echo esc_html(get_field('mail_id', 'option')); ?>"><?php echo esc_html(get_field('mail_id', 'option')); ?></a>
                                                </div>
                                            </div> <!--  end info -->
                                        <?php   }; ?>

                                    </div> <!--  end info-wrap -->

                                </div> <!--  end col -->

                            </div><!--  end row -->
                            <hr />
                        </div><!--  end container -->
                    </header>
                </div><!-- end admin -->
            <?php endif; ?>
        </div><!-- end header_wrap -->