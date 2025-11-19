<footer class="bg-black text-white pt-5 pb-3">
    <div class="container">
        <div class="row py-0 py-md-4">
            <div class="col-md-6">
                <?php if (get_field('subscribe_text', 'option')) { ?>
                    <div class="subs_text mb-4 mb-md-0">
                        <?php echo wp_kses_post(get_field('subscribe_text', 'option')); ?>
                    </div>
                <?php   }; ?>

            </div>
            <div class="col-md-6">
                <div class="subs_form">
                    <?php echo do_shortcode('[contact-form-7 id="075a926" title="Subscribe form"]'); ?>
                    <!-- Omnisend Embed Form -->
                    <div id="omnisend-embedded-v2-680b75ea6a35d55a0034f846"></div>
                </div>
            </div>
        </div>
        <div class="text-white">
            <hr>
        </div>
        <div class="row py-4 py-md-5">
            <!-- Column 1: Logo & Description -->
            <div class="col-md-6 mb-4">
                <div class="f_box">
                    <div class="logo mb-4 pb-md-1">
                        <a class="mb-3 d-inline-block" href="<?php echo site_url(); ?>">
                            <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/tennis-land-logo-98.png" alt="logo-image" />
                        </a>
                        <?php if (get_field('text_under_footer_logo', 'option')) { ?>
                            <p class="text-16"><?php echo esc_html(get_field('text_under_footer_logo', 'option')); ?></p>
                        <?php   }; ?>
                    </div>
                    <div class="info-wrap">

                        <?php if (get_field('phone_number', 'option')) { ?>
                            <div class="info">
                                <div class="icon">
                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/icon-call-24.png" alt="icon-image" />
                                </div>
                                <div class="text">
                                    <a href="tel:<?php echo esc_html(get_field('phone_number', 'option')); ?>"><?php echo esc_html(get_field('phone_number', 'option')); ?></a>
                                </div>
                            </div> <!--  end info -->
                        <?php   }; ?>

                        <?php if (get_field('mail_id', 'option')) { ?>
                            <div class="info">
                                <div class="icon">
                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/icon-mail-24.png" alt="icon-image" />
                                </div>
                                <div class="text">
                                    <a href="mailto:<?php echo esc_html(get_field('mail_id', 'option')); ?>"><?php echo esc_html(get_field('mail_id', 'option')); ?></a>
                                </div>
                            </div> <!--  end info -->
                        <?php   }; ?>
                        <?php if (get_field('location', 'option')) { ?>
                            <div class="info">
                                <div class="icon">
                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/icon-mark-24.png" alt="icon-image" />
                                </div>
                                <div class="text">
                                    <p class="text-16 m-0"><?php echo esc_html(get_field('location', 'option')); ?> </p>
                                </div>
                            </div> <!--  end info -->
                        <?php   }; ?>
                        <div class="info  align-items-start">
                            <div class="icon">
                                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/icon-time-24.png" alt="icon-image" />
                            </div>
                            <div class="text">
                                <p class="text-16 opentime">
                                    <strong>I-V</strong>
                                    <?php echo esc_html(get_field('day4_from', 'option')); ?> - <?php echo esc_html(get_field('day4__to', 'option')); ?><span class="d-block"></span>
                                    <strong>VI</strong>
                                    <?php echo esc_html(get_field('day6__from', 'option')); ?> <?php if (get_field('day6__to', 'option')) echo '- ' . esc_html(get_field('day6__to', 'option')); ?><span class="d-block"></span>
                                    <strong>VII</strong>
                                    <?php echo esc_html(get_field('day7__from', 'option')); ?> <?php if (get_field('day7__to', 'option')) echo '- ' . esc_html(get_field('day7__to', 'option')); ?>
                                </p>
                            </div>
                        </div> <!--  end info -->

                    </div> <!--  end info-wrap -->
                </div>
                <div class="text-white d-block d-md-none">
                    <hr>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4 col-6 mb-4">
                        <div class="f_box">
                            <?php if (get_field('title_for_2nd_column', 'option')) { ?>
                                <h5 class="text-white fw-semibold"><?php echo esc_html(get_field('title_for_2nd_column', 'option')); ?></h5>
                            <?php   }; ?>

                            <?php $footer_menu_2nd_column = get_field('footer_menu_2nd_column', 'option');
                            if ($footer_menu_2nd_column) {
                                wp_nav_menu(array(
                                    'menu' => $footer_menu_2nd_column,
                                    'container' => 'nav',
                                    'menu_class' => 'custom-menu'
                                ));
                            } ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 mb-4">
                        <div class="f_box">
                            <?php if (get_field('title_for_3rd_column', 'option')) { ?>
                                <h5 class="text-white fw-semibold"><?php echo esc_html(get_field('title_for_3rd_column', 'option')); ?></h5>
                            <?php   }; ?>

                            <?php $footer_menu_3rd_column = get_field('footer_menu_3rd_column', 'option');
                            if ($footer_menu_3rd_column) {
                                wp_nav_menu(array(
                                    'menu' => $footer_menu_3rd_column,
                                    'container' => 'nav',
                                    'menu_class' => 'custom-menu'
                                ));
                            } ?>
                        </div>
                    </div>
                    <div class="col-md-4 mb-md-4">
                        <div class="f_box">
                            <?php if (get_field('title_for_4th_column', 'option')) { ?>
                                <h5 class="text-white fw-semibold"><?php echo esc_html(get_field('title_for_4th_column', 'option')); ?></h5>
                            <?php   }; ?>

                            <?php $footer_menu_4th_column = get_field('footer_menu_4th_column', 'option');
                            if ($footer_menu_4th_column) {
                                wp_nav_menu(array(
                                    'menu' => $footer_menu_4th_column,
                                    'container' => 'nav',
                                    'menu_class' => 'custom-menu'
                                ));
                            } ?>
                        </div>
                    </div>
                </div>


            </div>


        </div>
        <div class="text-white row">
            <hr>
        </div>
        <!-- Bottom Footer -->
        <div class="row py-4">
            <div class="col-md-6">
                <div class="copyright text-left">
                    <p class="text-white text-14 text-opacity-75 mb-0 pt-2 text-md-start">
                        <?php echo esc_html(get_field('copyright_text', 'option')); ?>
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="f_menu d-none">

                    <?php $footer_menu_bottom = get_field('footer_menu_bottom', 'option');

                    if ($footer_menu_bottom) {
                        wp_nav_menu(array(
                            'menu' => $footer_menu_bottom,
                            'container' => 'nav',
                            'menu_class' => 'custom-menu'
                        ));
                    } ?>
                </div>
            </div>
        </div>

    </div>
</footer>

<?php wp_footer(); ?>

<?php if (is_front_page() || is_shop() || is_search() || is_product_taxonomy()) : ?>
    <div id="compare_box" class="compare_box" style="display: none;">

        <div id="compare-popup" class="compare-popup">
            <div class="compare-popup-inner">
                <div class="cmp_head d-flex justify-content-between">
                    <h4>Produktų palyginimas</h4>
                    <div class="icon-box">
                        <a id="close-cmpop" class="cart-contents no-scroll" href="#">
                            <div class="icon-holder bg icon-close">

                            </div>
                        </a>
                    </div>
                </div>

                <div class="list_wrap py-3 d-flex gap-3">
                    <ul id="compare-list-items" class="d-flex gap-3 flex-wrap"></ul>
                    <aside id="additem"><img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/additem.png" alt="icon-image" class="additem" /></aside>
                </div>

                <div class="compare-actions d-flex justify-content-between">
                    <button id="clear-compare">Ištrinti viską</button>
                    <a id="compare-now-btn" href="#" class="compare-now-btn">Palyginti</a>
                </div>
            </div>
        </div>

    </div>
    <style>
        .compare-popup {
            background: #fff;
            border: 1px solid #ddd;
            padding: 30px 24px;
            width: 900px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }

        .compare-popup ul {
            list-style: none;
            padding: 0;
            margin: 0 0 10px;
            max-height: 200px;
            overflow-y: auto;
        }

        .compare-popup ul li {
            display: flex;
            flex-direction: column;
            margin-bottom: 8px;
        }

        .compare-popup img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            margin-right: 10px;
            border-radius: 4px;
        }

        .compare-popup .compare-now-btn {
            background: #0071a1;
            color: #fff;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
<?php endif; ?>

<script>
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'sr_submit') {
            document.getElementById('search-form').submit();
        }
    });
</script>


</body>

</html>