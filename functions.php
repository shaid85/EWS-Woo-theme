<?php
/*
* My theme functions
*/

/**
 * Theme CSS and JS file calling
 */
function ewsdev_load_scripts()
{
    wp_enqueue_style('ewsdev-style', get_stylesheet_uri());
    wp_register_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', array(), '5.3.3', 'all');
    wp_enqueue_style('bootstrap');
    wp_register_style('custom-style', get_template_directory_uri() . '/assets/css/custom.css', array(), '2.0.0', 'all');
    wp_enqueue_style('custom-style');
    wp_register_style('responsive-style', get_template_directory_uri() . '/assets/css/responsive.css', array(), '2.0.0', 'all');
    wp_enqueue_style('responsive-style');
    // wp_enqueue_style('custom-fonts', get_template_directory_uri() . '/assets/css/font.css', [], null);
    wp_enqueue_style('custom-fonts', get_template_directory_uri() . '/assets/fonts/general-sans.css', [], null);

    //wp_enqueue_style('wpdevs-style', get_stylesheet_uri(), array(), filemtime(get_template_directory() . '/style.css'), 'all'); // version over right for loading without cache
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap', array(), null);

    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.js', array(), '2.0.0', 'true');
    // nice-select
    wp_enqueue_style('nice-select', get_stylesheet_directory_uri() . '/assets/css/nice-select.css');
    wp_enqueue_script('nice-select', get_stylesheet_directory_uri() . '/assets/js/jquery.nice-select.min.js', array('jquery'), null, true);

    wp_enqueue_script('main-js', get_template_directory_uri() . '/assets/js/main.js', array(), '2.0.0', 'true');
}
add_action('wp_enqueue_scripts', 'ewsdev_load_scripts');

// Load swiper_assets 
function enqueue_swiper_assets()
{
    // Swiper.js CSS
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0');

    // Swiper.js JS
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array('jquery'), '11.0.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_swiper_assets');




/**
 * Config function
 */
function ewsdev_config()
{
    register_nav_menus(
        array(
            'ewsdev_top_menu' => 'Topbar Menu',
            'ewsdev_main_menu' => 'Main Menu',
            'ewsdev_footer_menu' => 'Footer Menu',
        )
    );

    // $args = array(
    //     'height' => 225,
    //     'width' => 1920
    // );
    // add_theme_support('custom-header', $args);

    add_theme_support('title-tag');   // theme title
    add_theme_support('post-thumbnails');

    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script'));
}
add_action('after_setup_theme', 'ewsdev_config');


// Disable Default Image Sizes
function disable_default_image_sizes($sizes)
{
    // unset($sizes['thumbnail']); // Remove thumbnail size
    unset($sizes['medium']); // Remove medium size
    // unset($sizes['large']); // Remove large size
    // unset($sizes['medium_large']); // Remove medium-large size
    unset($sizes['1536x1536']); // Remove 2x medium-large size
    unset($sizes['2048x2048']); // Remove 2x large size
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'disable_default_image_sizes');

// enable widgets
function ewsheme_widgets_init()
{
    register_sidebar(array(
        'name'          => __('Sidebar Widget Area', 'textdomain'),
        'id'            => 'sidebar-1',
        'description'   => __('Widgets in this area will be shown in the sidebar.', 'textdomain'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'ewsheme_widgets_init');


// Populate the Select Field with WordPress Menus
function populate_acf_menu_select($field)
{
    // List of ACF field names where this should apply
    $target_fields = ['footer_menu_bottom', 'topbar_menu_select', 'footer_menu_2nd_column', 'footer_menu_3rd_column', 'footer_menu_4th_column', 'select_menu_from_list_1', 'select_menu_from_list_2', 'select_menu_from_list_3'];

    // Check if the current field is in our target list
    if (!in_array($field['name'], $target_fields)) {
        return $field;
    }

    // Reset choices
    $field['choices'] = array();

    // Get all registered WordPress menus
    $menus = wp_get_nav_menus();

    if (!empty($menus)) {
        foreach ($menus as $menu) {
            $field['choices'][$menu->slug] = $menu->name; // Slug as value, Name as label
        }
    }

    return $field;
}
add_filter('acf/load_field', 'populate_acf_menu_select');


// upload image will add "media-" prefix - as can't common name
add_filter('wp_handle_upload_prefilter', 'add_media_prefix_to_filename');

function add_media_prefix_to_filename($file)
{
    $info = pathinfo($file['name']);
    $ext = isset($info['extension']) ? '.' . $info['extension'] : '';
    $name = basename($file['name'], $ext);

    // Check if the filename already starts with 'media-'
    if (strpos($name, 'media-') !== 0) {
        $file['name'] = 'media-' . $name . $ext;
    }

    return $file;
}


// include file of path inc
require get_template_directory() . '/inc/call_flexiable_layout.php';




// for filtering products via AJAX
function enqueue_filter_scripts()
{
    // Enqueue jQuery if not already included (usually included by default in WordPress)
    wp_enqueue_script('jquery');

    // Enqueue js-cookie library
    wp_enqueue_script(
        'js-cookie',
        'https://cdn.jsdelivr.net/npm/js-cookie@3.0.5/dist/js.cookie.min.js',
        [],
        null,
        true
    );
    // Enqueue your custom filter script
    wp_enqueue_script('custom-filter', get_template_directory_uri() . '/assets/js/ajax-filter.js', ['jquery', 'js-cookie'], '1.0', true);

    // Pass ajax_url to the script using wp_localize_script
    wp_localize_script('custom-filter', 'filter_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php')  // Define the AJAX URL for your request
    ));

    // pass current archive context
    $term = get_queried_object();
    wp_localize_script('custom-filter', 'filterContext', [
        'taxonomy' => ($term instanceof WP_Term) ? $term->taxonomy : '',
        'term_id'  => ($term instanceof WP_Term) ? $term->term_id  : '',
        'discount' => isset($_GET['discount']) ? floatval($_GET['discount']) : 0,
    ]);
}

add_action('wp_enqueue_scripts', 'enqueue_filter_scripts');



/*
* Woocommerce Function start
*/
add_action('wp_enqueue_scripts', 'enable_ajax_add_to_cart_single');
function enable_ajax_add_to_cart_single()
{
    wp_enqueue_script('wc-add-to-cart');
}
add_action('wp_enqueue_scripts', function () {
    if (function_exists('is_woocommerce') && is_woocommerce()) {
        wp_enqueue_script('wc-cart-fragments');
    }
});


function mytheme_add_woocommerce_support()
{
    add_theme_support('woocommerce');


    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');


function conditional_woocommerce_image_sizes($sizes)
{
    if (isset($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'product') {
        // Allow WooCommerce image sizes only for product images
        return $sizes;
    }

    // Remove WooCommerce sizes for non-product images
    unset($sizes['woocommerce_thumbnail']);
    unset($sizes['woocommerce_single']);
    unset($sizes['woocommerce_gallery_thumbnail']);

    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'conditional_woocommerce_image_sizes');

function restrict_image_sizes_for_products($sizes)
{
    // Check if the uploaded image is for a WooCommerce product
    if (isset($_REQUEST['post_id'])) {
        $post_type = get_post_type($_REQUEST['post_id']);
        if ($post_type === 'product') {
            // Only keep WooCommerce-specific sizes
            return [
                'woocommerce_thumbnail'        => $sizes['woocommerce_thumbnail'] ?? '',
                'woocommerce_single'           => $sizes['woocommerce_single'] ?? '',
                'woocommerce_gallery_thumbnail' => $sizes['woocommerce_gallery_thumbnail'] ?? '',
            ];
        }
    }
    return $sizes; // Return all sizes for other post types
}
add_filter('intermediate_image_sizes_advanced', 'restrict_image_sizes_for_products');




// Page builder style
add_action('admin_head', 'my_custom_acf_style');
function my_custom_acf_style()
{ ?>
    <style>
        #Megamenu .layout:nth-child(odd)>.acf-fc-layout-handle {
            background-color: #e1e1e1;
        }

        #Megamenu .layout:nth-child(even)>.acf-fc-layout-handle {
            background-color: #f2ffdc;
        }

        #Megamenu #Grig_col .layout:nth-child(odd)>.acf-fc-layout-handle {
            background-color: #d7e9ff;
        }

        #Megamenu #Grig_col .layout:nth-child(even)>.acf-fc-layout-handle {
            background-color: #f2ffdc;
        }

        div#Page_Builder .acf-label.acf-accordion-title {
            background-color: #e4f0f6;
        }

        @media (min-width: 1200px) and (max-width: 1550px) {
            #Grig_col .values.ui-sortable {
                display: flex !important;
                width: 120% !important;
                flex-wrap: wrap !important;
            }

            #Grig_col .values.ui-sortable>* {
                flex: 0 0 48%;
            }
        }
    </style>
    <?php }

function custom_woocommerce_search_template($template)
{
    if (is_search() && is_woocommerce()) {
        $custom_template = locate_template('woocommerce/search.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter('template_include', 'custom_woocommerce_search_template', 50);


/*
*  To change the WooCommerce breadcrumb divider
*/
add_filter('woocommerce_breadcrumb_defaults', function ($defaults) {
    $defaults['delimiter'] = ' <span class="breadcrumb-arrow"></span> ';
    return $defaults;
});
add_filter('woocommerce_get_breadcrumb', 'custom_remove_taxonomy_from_breadcrumb', 20, 2);

function custom_remove_taxonomy_from_breadcrumb($crumbs, $breadcrumb)
{
    // Loop through the breadcrumb and remove taxonomy names
    foreach ($crumbs as $key => $crumb) {
        // Example: remove "Brands", "Categories", etc.
        if (in_array($crumb[0], ['Brands', 'Raketės', 'Kategorija'])) {
            unset($crumbs[$key]);
        }
    }

    // Reindex array keys
    return array_values($crumbs);
}

add_filter('woocommerce_get_breadcrumb', function ($crumbs, $breadcrumb) {
    foreach ($crumbs as &$crumb) {
        if (!isset($crumb[0])) {
            continue;
        }

        // ✅ Translate search results
        if (is_search() && strpos($crumb[0], 'Paieškos rezultatai pagal') !== false) {
            $crumb[0] = __('Paieško', 'woocommerce');
        }

        // ✅ Translate My Account
        if ($crumb[0] === 'My account') {
            $crumb[0] = __('Mano paskyra', 'woocommerce');
        }
    }

    return $crumbs;
}, 10, 2);

add_filter('woocommerce_account_menu_items', function ($items) {
    // Example: Change "Dashboard" to "Home"
    if (isset($items['dashboard'])) {
        $items['dashboard'] = __('Apžvalga', 'woocommerce');
    }
    if (isset($items['edit-address'])) {
        $items['edit-address'] = __('Adresas', 'woocommerce');
    }

    // Example: Change "My Account" tab name if present
    if (isset($items['edit-account'])) {
        $items['edit-account'] = __('Paskyros informacija', 'woocommerce');
    }

    return $items;
}, 20);



// Functions for Single Product Page
// start single product swiper
add_action('wp_enqueue_scripts', function () {
    if (is_product()) {
        wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');
        wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', [], null, true);
        wp_add_inline_script('swiper', "
            document.addEventListener('DOMContentLoaded',function(){
                new Swiper('.myProdSwiper',{pagination:{el:'.swiper-pagination'},loop:true});
            });
        ");
    }
});
/**
 * Show sale price first, regular price second.
 * <ins>€139,00</ins>  <del>€199,00</del>
 */
add_filter('woocommerce_format_sale_price', function ($price, $regular, $sale) {

    // Build custom markup: sale inside <ins>, regular inside <del>.
    $new  = '<ins class="sale-price">' . wc_price(wc_format_localized_price($sale)) . '</ins> ';
    $new .= '<del class="gray_text regular-price">' . wc_price(wc_format_localized_price($regular)) . '</del>';

    return $new;
}, 20, 3);


// woo  quantity - 1 +
add_action('wp_enqueue_scripts', function () {
    if (is_product() || is_cart()) { // where qty appears
        wp_add_inline_script('jquery-core', "
            jQuery(document).on('click','.qty-btn',function(){
                var \$qty = jQuery(this).siblings('.qty');
                var current = parseFloat(\$qty.val()) || 0;
                var max = parseFloat(\$qty.attr('max')) || 9999;
                var min = parseFloat(\$qty.attr('min')) || 0;
                var step = parseFloat(\$qty.attr('step')) || 1;
                if(jQuery(this).hasClass('qty-plus')){
                    if(current+step<=max) \$qty.val(current+step).change();
                }else{
                    if(current-step>=min) \$qty.val(current-step).change();
                }
            });
        ");
    }
});


add_filter('body_class', function ($classes) {
    if (is_page()) {
        $page = get_post();
        if ($page) {
            $classes[] = 'page-' . sanitize_title($page->post_name);
        }
    }
    return $classes;
});



if (class_exists('WooCommerce')) {
    require get_template_directory() . '/inc/wc_cart_fragments.php';
    require get_template_directory() . '/inc/ajax_search_product_popup.php';
    require get_template_directory() . '/inc/ajax_popup_cart_counter.php';
    require get_template_directory() . '/inc/ajax_product_filters.php';
    require get_template_directory() . '/inc/compare_product_fn.php';

    require get_template_directory() . '/inc/get_available_terms_filter.php';
    require get_template_directory() . '/inc/get_attr_count_terms_counts.php';
    require get_template_directory() . '/inc/get_subcategories_with_local_counts.php';

    require get_template_directory() . '/inc/plugin_attribute_reorder.php';

    require get_template_directory() . '/inc/discount_function.php';
    require get_template_directory() . '/inc/shortcode/step_form_query_choose.php';

    require get_template_directory() . '/inc/product/single_product_part.php';
    require get_template_directory() . '/inc/product/string_radio_btn.php';
    require get_template_directory() . '/inc/product/price_show_var_admin.php';

    require get_template_directory() . '/inc/product/add_action_hook.php';
    require get_template_directory() . '/inc/product/custom_fns.php';

    require get_template_directory() . '/inc/plugin_love_button_function.php';

    require get_template_directory() . '/inc/admin/add_action_fn.php';
}

// Limit description
function ews_short_description($word_limit = 50)
{
    $term = get_queried_object();

    if (!empty($term->description)) {
        $html = term_description($term);

        // Load HTML into DOMDocument
        $doc = new DOMDocument();
        libxml_use_internal_errors(true); // Suppress warnings for malformed HTML
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();

        $xpath = new DOMXPath($doc);
        $body = $xpath->query('//body')->item(0);

        $word_count = 0;
        $output = '';

        foreach ($body->childNodes as $node) {
            if ($word_count >= $word_limit) break;

            // Clone node to extract text only
            $clone = $node->cloneNode(true);
            $text = trim($clone->textContent);
            $words = preg_split('/\s+/', $text);
            $node_word_count = count($words);

            if ($word_count + $node_word_count <= $word_limit) {
                $output .= $doc->saveHTML($node);
                $word_count += $node_word_count;
            } else {
                // Truncate and keep tag
                $remaining = $word_limit - $word_count;
                $truncated_text = implode(' ', array_slice($words, 0, $remaining)) . '...';

                $node_name = $node->nodeName;
                $output .= "<{$node_name}>{$truncated_text}</{$node_name}>";
                break;
            }
        }

        //echo '<div class="term-description sort">' . $output . '</div>';
    }
    return $output;
}

// Faq section 
function show_apie_mus_faq_section()
{
    ob_start();

    $page = get_page_by_path('apie-mus');

    if ($page && have_rows('page_section', $page->ID)) {
        while (have_rows('page_section', $page->ID)) {
            the_row();
            if (get_row_layout() === 'faq_section') {
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
<?php
            }
        }
    }
    return ob_get_clean();
}
add_shortcode('apie_mus_faq', 'show_apie_mus_faq_section');

// Save ACF JSON files into /acf-json folder
add_filter('acf/settings/load_json', function ($paths) {
    // Path to your JSON files
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
});



add_filter('acf/load_field_group', function ($field_group) {
    // Load this group only from JSON, even if DB copy exists
    if ($field_group['key'] === 'group_67e466631a9dc') {
        $field_group['local'] = 'json';
    }
    return $field_group;
});


// Disable WooCommerce Tracks and AI Content calls (prevents CORS issues)
add_filter('woocommerce_admin_features', function ($features) {
    return array_diff($features, array(
        'remote-free-extensions',
        'marketing',
        'coupons',
        'navigation',
        'analytics',
        'new-navigation',
        'onboarding',
        'product_block_editor',
        'ai-content',
    ));
}, 999);

add_filter('woocommerce_allow_tracking', '__return_false');

// Remove filter cookies when search results page loads
add_action('wp_enqueue_scripts', function () {
    if (is_search()) {
        wp_add_inline_script('jquery', "
            jQuery(function($) {
                // Remove filter cookies
                Cookies.remove('min_price');
                Cookies.remove('max_price');
                Cookies.remove('product_filter_data');

                // Reset noUiSlider if exists
                if (typeof priceSlider !== 'undefined' && priceSlider.noUiSlider) {
                    var min = parseFloat($('#price_range').data('min')) || 0;
                    var max = parseFloat($('#price_range').data('max')) || 1000;
                    priceSlider.noUiSlider.set([min, max]);
                }

                // Optional: reset displayed text
                $('#priceRangeVal').text('');
                $('.filter-item.active').removeClass('active');
            });
        ");
    }
});




// Temp code
