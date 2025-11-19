<?php
// use do_action('custom_single_product_summary'); 
add_action('custom_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);

// Remove the default payment section from the default location
remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);

// Add it somewhere else manually — custom hook or action do_action('ews_custom_payment_hook'); 
add_action('ews_custom_payment_hook', 'woocommerce_checkout_payment', 10);

// Remove the default 
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);

// Remove the default 
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);

// add woocommerce_breadcrumb to page.php
add_action('ews_before_page_content', 'woocommerce_breadcrumb', 20, 0);

add_filter('woocommerce_checkout_fields', 'add_custom_billing_field');
function add_custom_billing_field($fields)
{
    $fields['billing']['billing_mail_agree'] = [
        'type'     => 'checkbox',
        'label'    => 'Sutinku, kad Tenisland mano kontaktais siųstų informacinius pranešimus.',
        'required' => false,
        'class'    => ['form-row-wide'],
        'priority' => 11,
    ];
    $fields['billing']['custom_heading'] = [
        'type' => 'text',
        'label' => 'Pristatymo adresas',
        'required' => false,
        'class' => ['form-row-wide', 'fake-heading'],
        'priority' => 12,
    ];
    $fields['billing']['billing_for_company'] = [
        'type'     => 'checkbox',
        'label'    => 'Perka įmonė?',
        'required' => false,
        'class'    => ['form-row-first'],
        'priority' => 140,
    ];
    $fields['billing']['billing_company'] = [
        'type'     => 'text',
        'label'    => 'Company name',
        'required' => false,
        'placeholder'    => 'Įmonės pavadinimas*',
        'class'    => ['form-row-first'],
        'priority' => 142,
    ];
    $fields['billing']['billing_company_code'] = [
        'type'     => 'text',
        'label'    => 'Company code',
        'required' => false,
        'placeholder'    => 'Įmonės kodas*',
        'class'    => ['form-row-last'],
        'priority' => 143,
    ];
    $fields['billing']['billing_vat_code'] = [
        'type'     => 'text',
        'label'    => 'VAT Code',
        'required' => false,
        'placeholder'    => 'PVM kodas',
        'class'    => ['form-row-first'],
        'priority' => 144,
    ];
    $fields['billing']['billing_c_street'] = [
        'type'     => 'text',
        'label'    => 'Street name',
        'required' => false,
        'placeholder'    => 'Gatvės pavadinimas, namo numeris*',
        'class'    => ['form-row-last'],
        'priority' => 145,
    ];
    $fields['billing']['billing_c_apartment'] = [
        'type'     => 'text',
        'label'    => 'Apartment, block, etc',
        'required' => false,
        'placeholder'    => 'Butas, blokas ir pan.',
        'class'    => ['form-row-first'],
        'priority' => 146,
    ];
    $fields['billing']['billing_c_city'] = [
        'type'     => 'text',
        'label'    => 'City',
        'required' => false,
        'placeholder'    => 'Miestas*',
        'class'    => ['form-row-last'],
        'priority' => 147,
    ];
    $fields['billing']['billing_c_country'] = [
        'type'     => 'text',
        'label'    => 'country',
        'required' => false,
        'placeholder'    => 'Apskritis',
        'class'    => ['form-row-first'],
        'priority' => 148,
    ];
    $fields['billing']['billing_cp_pcode'] = [
        'type'     => 'text',
        'label'    => 'Pašto kodas*',
        'required' => false,
        'placeholder'    => 'Pašto kodas*',
        'class'    => ['form-row-last'],
        'priority' => 149,
    ];
    $fields['billing']['billing_cp_none'] = [
        'type'     => 'textarea',
        'label'    => 'Pastabos kurjeriui',
        'required' => false,
        'placeholder'    => 'Pastabos kurjeriui',
        'class'    => ['form-row-wide'],
        'priority' => 150,
        'custom_attributes' => [
            'rows' => 4
        ],
    ];
    $fields['billing']['custom_gap'] = [
        'type' => 'text',
        'label' => 'hr',
        'required' => false,
        'class' => ['form-row-wide', 'hide_divider'],
        'priority' => 138,
    ];


    return $fields;
}


// custom checkbox style
add_filter('woocommerce_form_field', 'custom_render_custom_billing_checkboxes', 10, 4);
function custom_render_custom_billing_checkboxes($field, $key, $args, $value)
{
    // Define custom checkbox fields and their labels
    $custom_checkboxes = [
        'billing_mail_agree'       => 'Sutinku, kad Tenisland mano kontaktais siųstų informacinius pranešimus.',
        'billing_for_company'      => 'Perka įmonė?',
    ];

    if (array_key_exists($key, $custom_checkboxes)) {
        $checked = checked($value, 0, false); // Checkbox checked if value is 1
        $field = '
        <p class="form-row form-row-wide custom-checkbox-wrapper" id="' . esc_attr($key) . '_field">
            <label class="custom-checkbox woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                <input 
                    type="checkbox" 
                    class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" 
                    name="' . esc_attr($key) . '" 
                    id="' . esc_attr($key) . '" 
                    value="1" ' . $checked . ' />
                <span class="checkmark"></span>
                <span>' . esc_html($custom_checkboxes[$key]) . '</span>
            </label>
        </p>';
    }

    return $field;
}

add_filter('woocommerce_checkout_fields', 'remove_billing_fields');
function remove_billing_fields($fields)
{
    // Remove default state field
    // unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_state']);
    unset($fields['shipping']['shipping_state']);

    return $fields;
}

// Checkout form mofify
add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields', 999);
function custom_override_checkout_fields($fields)
{
    // Set proper numeric priorities (no strings, no duplicates)
    $fields['billing']['billing_email']['priority'] = 10;
    $fields['billing']['billing_first_name']['priority'] = 20;
    $fields['billing']['billing_last_name']['priority'] = 30;
    $fields['billing']['billing_phone']['priority'] = 40;
    $fields['billing']['billing_address_1']['priority'] = 50;
    $fields['billing']['billing_address_2']['priority'] = 60;
    $fields['billing']['billing_city']['priority'] = 70;
    $fields['billing']['billing_country']['priority'] = 90;
    $fields['billing']['billing_postcode']['priority'] = 80;


    // $fields['billing']['billing_state']['priority'] = 120;

    // Add layout classes
    $fields['billing']['billing_phone']['class'] = ['form-row-first'];
    $fields['billing']['billing_address_1']['class'] = ['form-row-last'];
    $fields['billing']['billing_address_2']['class'] = ['form-row-first'];
    $fields['billing']['billing_country']['class'] = ['form-row-first'];
    $fields['billing']['billing_city']['class'] = ['form-row-last'];
    $fields['billing']['billing_postcode']['class'] = ['form-row-first'];
    // $fields['billing']['billing_state']['class'] = ['form-row-last'];

    // Add placeholders
    $fields['billing']['billing_first_name']['placeholder'] = 'Vardas*';
    $fields['billing']['billing_last_name']['placeholder'] = 'Pavardė*';
    $fields['billing']['billing_email']['placeholder'] = 'Jūsų el. paštas*';
    $fields['billing']['billing_phone']['placeholder'] = 'Tel. numeris*';
    $fields['billing']['billing_address_1']['placeholder'] = 'Gatvės pavadinimas, namo numeris';
    $fields['billing']['billing_address_2']['placeholder'] = 'Butas, blokas ir pan.';
    $fields['billing']['billing_city']['placeholder'] = 'Miestas*';
    $fields['billing']['billing_postcode']['placeholder'] = 'Pašto kodas*';
    // $fields['billing']['billing_state']['placeholder'] = 'Valstybė*';
    $fields['billing']['billing_country']['label'] = 'Apskritis';

    // Make phone required
    $fields['billing']['billing_phone']['required'] = true;

    return $fields;
}


?>

<?php
add_action('woocommerce_checkout_update_order_meta', 'save_custom_billing_field');
function save_custom_billing_field($order_id)
{
    if (!empty($_POST['billing_company'])) {
        update_post_meta($order_id, '_billing_company', sanitize_text_field($_POST['billing_company']));
    }
    if (!empty($_POST['billing_company_code'])) {
        update_post_meta($order_id, '_billing_company_code', sanitize_text_field($_POST['billing_company_code']));
    }
    if (!empty($_POST['billing_vat_code'])) {
        update_post_meta($order_id, '_billing_vat_code', sanitize_text_field($_POST['billing_vat_code']));
    }
    if (!empty($_POST['billing_mail_agree'])) {
        update_post_meta($order_id, '_billing_mail_agree', sanitize_text_field($_POST['billing_mail_agree']));
    }
    if (!empty($_POST['billing_c_street'])) {
        update_post_meta($order_id, '_billing_c_street', sanitize_text_field($_POST['billing_c_street']));
    }
    if (!empty($_POST['billing_c_apartment'])) {
        update_post_meta($order_id, '_billing_c_apartment', sanitize_text_field($_POST['billing_c_apartment']));
    }
    if (!empty($_POST['billing_c_city'])) {
        update_post_meta($order_id, '_billing_c_city', sanitize_text_field($_POST['billing_c_city']));
    }
    if (!empty($_POST['billing_c_country'])) {
        update_post_meta($order_id, '_billing_c_country', sanitize_text_field($_POST['billing_c_country']));
    }
    if (!empty($_POST['billing_cp_pcode'])) {
        update_post_meta($order_id, '_billing_cp_pcode', sanitize_text_field($_POST['billing_cp_pcode']));
    }
    if (!empty($_POST['billing_cp_none'])) {
        update_post_meta($order_id, '_billing_cp_none', sanitize_text_field($_POST['billing_cp_none']));
    }
}


add_action('woocommerce_thankyou', 'display_custom_checkout_fields_thankyou', 20);
function display_custom_checkout_fields_thankyou($order_id)
{
    $order = wc_get_order($order_id);

    echo '<div class="woocommerce-customer-details addition"><h3 class="woocommerce-column__title">Papildoma informacija</h3><ul class="custom-checkout-fields">';

    $fields = [
        '_billing_mail_agree'    => 'Contact email Checkbox',
        '_billing_company'       => 'Įmonės pavadinimas',
        '_billing_company_code'  => 'Įmonės kodas',
        '_billing_vat_code'      => 'PVM kodas',
        '_billing_c_street'      => 'Gatvės pavadinimas, namo numeris',
        '_billing_c_apartment'   => 'Butas, blokas ir pan.',
        '_billing_c_city'        => 'Miestas',
        '_billing_c_country'     => 'Apskritis',
        '_billing_cp_pcode'      => 'Pašto kodas',
        '_billing_cp_none'       => 'Pastabos kurjeriui',
    ];

    foreach ($fields as $key => $label) {
        $value = get_post_meta($order_id, $key, true);
        if ($value == '1' || '0') {
            $value == '1' ? $value = 'Yes' : $value = 'No';
        }

        if (!empty($value)) {
            echo '<li><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</li>';
        }
    }

    echo '</ul></div>';
}


add_action('woocommerce_admin_order_data_after_billing_address', 'show_custom_fields_in_admin_order', 10, 1);
function show_custom_fields_in_admin_order($order)
{
    echo '<div class="woocommerce-customer-details addition"><h3 class="woocommerce-column__title">Papildoma informacija</h4>';

    $fields = [
        '_billing_mail_agree'    => 'Contact email Checkbox',
        '_billing_company'       => 'Įmonės pavadinimas',
        '_billing_company_code'  => 'Įmonės kodas',
        '_billing_vat_code'      => 'PVM kodas',
        '_billing_c_street'      => 'Gatvės pavadinimas, namo numeris',
        '_billing_c_apartment'   => 'Butas, blokas ir pan.',
        '_billing_c_city'        => 'Miestas',
        '_billing_c_country'     => 'Apskritis',
        '_billing_cp_pcode'      => 'Pašto kodas',
        '_billing_cp_none'       => 'Pastabos kurjeriui',
    ];

    foreach ($fields as $key => $label) {
        $value = get_post_meta($order->get_id(), $key, true);
        if ($value == '1' || '0') {
            $value == '1' ? $value = 'Yes' : $value = 'No';
        }
        if (!empty($value)) {
            echo '<p><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</p>';
        }
    }

    echo '</div>';
}

// Set Lithuania as default country
// add_filter('default_checkout_billing_country', 'set_default_country');
// add_filter('default_checkout_shipping_country', 'set_default_country');

// function set_default_country()
// {
//     return 'LT'; // Lithuania
// }


// my account
add_filter('woocommerce_account_menu_items', 'remove_woocommerce_logout_link');
function remove_woocommerce_logout_link($items)
{
    unset($items['customer-logout']);
    unset($items['downloads']);
    return $items;
}

// Manually Track Recently Viewed Products
add_action('template_redirect', function () {
    if (!is_singular('product')) {
        return;
    }

    $product_id = get_the_ID();
    if (!$product_id) {
        return;
    }

    $viewed = !empty($_COOKIE['woocommerce_recently_viewed'])
        ? explode(',', $_COOKIE['woocommerce_recently_viewed'])
        : [];

    // Remove current product if already in list
    $viewed = array_diff($viewed, [$product_id]);

    // Add current product to end
    $viewed[] = $product_id;

    // Keep last 15 only
    $viewed = array_slice($viewed, -15);

    // Set the cookie
    wc_setcookie('woocommerce_recently_viewed', implode(',', $viewed));
});




function get_custom_menu_with_kategorija($menu_id_or_slug, $args = [], $last_slug = '')
{
    // Get menu items
    $items = wp_get_nav_menu_items($menu_id_or_slug);

    if (!$items) {
        return ''; // No menu found
    }

    // Modify items
    foreach ($items as $item) {
        // $url = $custom_url;

        // Get last slug from path
        // $path = parse_url($url, PHP_URL_PATH);
        // $path_parts = explode('/', trim($path, '/'));
        // $last_slug = end($path_parts);

        if (empty($last_slug)) continue;

        // $separator = (strpos($url, '?') !== false) ? '&' : '?';
        $item->url .= '?' . 'kategorija=' . $last_slug;
    }

    // Set default arguments and merge
    $default_args = [
        'menu' => $menu_id_or_slug,
        'container' => '',
        'menu_class' => 'custom_cat',
        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        'echo' => false,
    ];
    $args = array_merge($default_args, $args);

    // Temporarily override the global menu items with our modified ones
    add_filter('wp_get_nav_menu_items', function () use ($items) {
        return $items;
    }, 10, 2);

    // Generate the menu HTML
    $menu_html = wp_nav_menu($args);

    // Remove our temporary filter
    remove_all_filters('wp_get_nav_menu_items');

    return $menu_html;
}



add_action('woocommerce_account_dashboard', 'custom_show_addresses_on_dashboard', 20);

function custom_show_addresses_on_dashboard()
{
    $current_user = wp_get_current_user();

    // Print Billing and Shipping addresses like the edit-address page
    wc_get_template('myaccount/my-address.php', array(
        'current_user' => $current_user,
        'load_address' => 'billing'
    ));
}


add_action('woocommerce_account_dashboard', 'custom_show_recent_orders_flexbox', 25);

function custom_show_recent_orders_flexbox()
{
    if (!is_user_logged_in()) return;

    $customer_user_id = get_current_user_id();

    $customer_orders = wc_get_orders(array(
        'limit'        => 5,
        'customer_id'  => $customer_user_id,
        'orderby'      => 'date',
        'order'        => 'DESC',
        'status'       => array('wc-completed', 'wc-processing', 'wc-on-hold')
    ));

    echo '<div class="order_box2">';

    if (!empty($customer_orders)) {
        echo '<div class="address_box order_box">';
        echo '<header class="woocommerce-Address-title title"><h2 class="text-14">Naujausi užsakymai</h2></header>';

        // Table Head
        echo '<div class="order-table">';
        echo '<div class="order-row order-head">';
        echo '<div class="order-col">Užsakymo numeris</div>';
        echo '<div class="order-col">Date</div>';
        echo '<div class="order-col">Suma</div>';
        echo '<div class="order-col">Statusas</div>';
        // echo '<div class="order-col">Action</div>';
        echo '</div>';

        // Table Rows
        foreach ($customer_orders as $order) {
            $order_id     = $order->get_id();
            $order_date   = $order->get_date_created()->date('F j, Y');
            $order_total  = $order->get_formatted_order_total();
            $order_status = wc_get_order_status_name($order->get_status());
            $order_url    = esc_url($order->get_view_order_url());

            echo '<div class="order-row">';
            echo '<div class="order-col">#' . $order_id . '</div>';
            echo '<div class="order-col">' . $order_date . '</div>';
            echo '<div class="order-col">' . $order_total . '</div>';
            echo '<div class="order-col">' . $order_status . '</div>';
            // echo '<div class="order-col"><a href="' . $order_url . '">View</a></div>';
            echo '</div>';
        }

        echo '</div>'; // .order-table
    } else {
        echo '<p>Užsakymų neturite.</p>';
    }

    echo '</div>'; // .order_box
}


add_action('woocommerce_cart_calculate_fees', 'conditionally_add_string_fee');
function conditionally_add_string_fee()
{
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    foreach (WC()->cart->get_cart() as $cart_item) {
        if (isset($cart_item['racket_string']) && $cart_item['racket_string'] === 'Pridėti stygas') {
            WC()->cart->add_fee('Pridėti stygas', 15.00);
            break; // Add fee only once even if multiple items have it
        }
    }
}



add_action('wp_ajax_load_product_popup', 'load_product_popup');
add_action('wp_ajax_nopriv_load_product_popup', 'load_product_popup');

function load_product_popup()
{
    $product_id = intval($_POST['product_id']);
    $product = wc_get_product($product_id);

    if (!$product) {
        echo 'Product not found';
        wp_die();
    }

?>
    <div class="popup-inner ">
        <div class="row item">
            <div class="col-md-5">
                <div class="product_image">
                    <img src="<?php echo get_the_post_thumbnail_url($product_id, 'medium'); ?>" alt="">
                </div>
            </div>
            <div class="col-md-7">
                <div class="pro_details">
                    <h2 class="product_title mb-2">
                        <div class="hover_show">
                            <?php echo get_the_title($product_id); ?>
                        </div>
                        <div class="default_show">
                            <?php
                            $product_title2 = get_the_title($product_id);
                            // Trim the title to 50 characters and add "..." if it's too long
                            $trimmed_title2 = trim_product_title($product_title2, 46, '');
                            echo $trimmed_title2;
                            ?>
                        </div>
                    </h2>
                    <!-- Product Price -->
                    <div class="product-price mb-3">
                        <?php if ($product->is_type('variable')) :
                            $min = $product->get_variation_regular_price('min', true);
                            $max = $product->get_variation_regular_price('max', true);
                            $sale_min = $product->get_variation_sale_price('min', true);
                            $sale_max = $product->get_variation_sale_price('max', true);

                            // Check if any variation is on sale
                            if ($sale_min < $min) {
                                echo '<span class="sale-price">' . wc_price($sale_min) . ($sale_min !== $sale_max ? ' - ' . wc_price($sale_max) : '') . '</span>';
                                echo '<span class="regular-price ms-2"><del>' . wc_price($min) . ($min !== $max ? ' - ' . wc_price($max) : '') . '</del></span>';
                            } else {
                                echo '<span class="sale-price">' . wc_price($min) . ($min !== $max ? ' - ' . wc_price($max) : '') . '</span>';
                            }

                        elseif ($product->is_on_sale()) : ?>
                            <span class="sale-price"><?php echo wc_price($product->get_sale_price()); ?></span>
                            <span class="regular-price ms-2"><del><?php echo wc_price($product->get_regular_price()); ?></del></span>
                        <?php else : ?>
                            <span class="sale-price"><?php echo wc_price($product->get_price()); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="adjust_string racket-string-option mb-3">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-secondary adjust_btn active" data-value="Yes">
                                Sutempti pagal eksperto rekomendacijas
                            </button>
                            <button type="button" class="btn btn-outline-secondary adjust_btn " data-value="No">
                                Renkuosi pats tempimo jėgą
                            </button>
                        </div>
                    </div>

                    <!-- Divider -->
                    <hr class="divider my-4" />


                    <div class="string_range">
                        <h3 class="text-16 fw-semibold">Stygų įtempimo jėga</h3>

                        <div class="d-flex align-items-center gap-3 mb-2">
                            <input type="range" name="main_tension" id="main_tension_<?php echo $product_id; ?>" min="20" max="30" value="25" step="0.5">
                            <label class="mb-0"><span id="main_val_<?php echo $product_id; ?>">25</span> kg
                                <aside>Horizontaliai</aside>
                            </label>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <input type="range" name="cross_tension" id="cross_tension_<?php echo $product_id; ?>" min="20" max="30" value="25" step="0.5">
                            <label class="mb-0">
                                <span id="cross_val_<?php echo $product_id; ?>">25</span> kg
                                <aside>Vertikaliai</aside>
                            </label>
                        </div>

                        <!-- Divider -->
                        <hr class="divider my-4" />
                    </div>



                    <form id="popup-add-to-cart-form">

                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="main_tension" id="main_tension" value="20">
                        <input type="hidden" name="cross_tension" id="cross_tension" value="20">

                        <?php if (! $product->is_in_stock()) : ?>
                            <button type="button" class="popup_add_to_cart_button button alt"
                                onclick="window.location.href='<?php echo esc_url(get_permalink($product_id)); ?>'">
                                <?php esc_html_e('Daugiau', 'ewsdev'); ?>
                            </button>

                        <?php elseif ($product->is_type('simple')) : ?>
                            <button type="submit" class="popup_add_to_cart_button button alt">
                                <?php esc_html_e('Į krepšelį', 'ewsdev'); ?>
                            </button>

                        <?php else : ?>
                            <button type="button" class="popup_add_to_cart_button button alt"
                                onclick="window.location.href='<?php echo esc_url(get_permalink($product_id)); ?>'">
                                <?php esc_html_e('Pasirinkite funkcijas', 'ewsdev'); ?>
                            </button>
                        <?php endif; ?>

                        <div class="service_cost">
                            <div class="inner_flex d-flex gap-2 justify-content-center">
                                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/error.png" width="24" alt="icon">
                                <aside class="text-14">Prie užsakymo bus automatiškai pridėta tempimo paslauga: 15 €</aside>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>


        <script>
            jQuery(function($) {
                $('#main_tension_<?php echo $product_id; ?>').on('input', function() {
                    $('#main_val_<?php echo $product_id; ?>').text(this.value);
                    $('#main_input_<?php echo $product_id; ?>').val(this.value);
                });
                $('#cross_tension_<?php echo $product_id; ?>').on('input', function() {
                    $('#cross_val_<?php echo $product_id; ?>').text(this.value);
                    $('#cross_input_<?php echo $product_id; ?>').val(this.value);
                });
            });
        </script>




        <style>
            .default_show {
                max-width: none;
            }

            .adjust_btn {
                border: 1px solid var(--color-border2, #dfe1e3);
                font-weight: 400;
                border-radius: 5px;
                padding: 11px 25px;
            }

            .adjust_btn:hover {
                background-color: transparent;
                color: #000;
            }

            .adjust_btn.active {
                background-color: #000 !important;
            }

            .adjust_btn.active:hover {
                background-color: #000 !important;
                color: #fff;
            }

            .service_cost {
                margin-top: 15px;
                border: 1px solid #F9C620;
                border-radius: 8px;
                padding: 8px;
                background-color: #FDF7C5;
            }

            .string_range input {
                width: calc(100% - 120px);
            }

            .string_range label {
                flex: 0 0 auto;
                width: 100px;
                color: #000;
                font-size: 18px;
                font-weight: 500;
            }

            .string_range aside {
                font-size: 14px;
                color: #7D7D7D;
                line-height: 20px;
                font-weight: 400;
            }

            /* range style */
            /* Base styles */
            input[type="range"] {
                -webkit-appearance: none;
                width: 100%;
                height: 6px;
                background: transparent;
            }

            /* Chrome / Safari */
            input[type="range"]::-webkit-slider-runnable-track {
                height: 6px;
                border-radius: 3px;
                background: linear-gradient(to right, black 0%, black var(--value, 50%), #ccc var(--value, 50%), #ccc 100%);
            }

            /* Thumb in Chrome/Safari */
            input[type="range"]::-webkit-slider-thumb {
                -webkit-appearance: none;
                height: 16px;
                width: 16px;
                border-radius: 50%;
                background: black;
                margin-top: -5px;
                position: relative;
                z-index: 2;
            }

            /* Firefox track (inactive) */
            input[type="range"]::-moz-range-track {
                background: #ccc;
                height: 6px;
                border-radius: 3px;
            }

            /* Firefox active track */
            input[type="range"]::-moz-range-progress {
                background: black;
                height: 6px;
                border-radius: 3px;
            }

            /* Firefox thumb */
            input[type="range"]::-moz-range-thumb {
                height: 16px;
                width: 16px;
                border-radius: 50%;
                background: black;
                border: none;
            }
        </style>
        <script>
            document.querySelectorAll('input[type="range"]').forEach(slider => {
                function updateTrackStyle() {
                    const min = parseFloat(slider.min) || 0;
                    const max = parseFloat(slider.max) || 100;
                    const val = parseFloat(slider.value);

                    const percentage = ((val - min) / (max - min)) * 100;
                    slider.style.setProperty('--value', `${percentage}%`);
                }

                // Initial update
                updateTrackStyle();

                // Update on input
                slider.addEventListener('input', updateTrackStyle);
            });
        </script>

    </div>
<?php

    wp_die();
}
