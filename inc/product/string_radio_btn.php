<?php
// Show custom racket string option before Add to Cart button
add_action('custom_before_add_to_cart_button', 'add_racket_string_option');
function add_racket_string_option()
{
?>
    <div class="racket-string-option mb-3">

        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-outline-secondary size-button add_string" data-value="Pridėti stygas">
                <img src="<?php echo esc_url(get_stylesheet_directory_uri());
                            ?>/assets/images/add.png" alt="icon" />
                Pridėti stygas
            </button>
            <button type="button" class="btn btn-outline-secondary size-button add_string" data-value="Be stygų">
                Be stygų
            </button>
        </div>

    </div>

    <script>
        const mainRacketProductId = <?php echo get_the_ID(); ?>;

        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.add_string');
            const hiddenInput = document.getElementById('racket_string_input');
            const popup = document.querySelector('.popup_string');

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const value = this.dataset.value;

                    hiddenInput.value = value;

                    buttons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    // Check if the clicked button has the target value
                    if (value === 'Pridėti stygas') {
                        popup.style.display = 'block';
                        document.body.classList.add('no-scroll'); // Optional: disable background scroll
                    }
                });
            });

            // Optional: Close popup when clicking outside or pressing ESC
            popup?.addEventListener('click', function(e) {
                if (e.target === popup) {
                    popup.style.display = 'none';
                    document.body.classList.remove('no-scroll');
                }
            });

            // Optional: ESC key closes popup
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    popup.style.display = 'none';
                    document.body.classList.remove('no-scroll');
                }
            });
        });
    </script>
<?php
}

// Save custom fields as custom cart item data
add_filter('woocommerce_add_cart_item_data', 'save_racket_string_option_to_cart', 20, 2);
function save_racket_string_option_to_cart($cart_item_data, $product_id)
{
    if (isset($_POST['racket_string'])) {
        $cart_item_data['racket_string'] = sanitize_text_field($_POST['racket_string']);
    }
    if (isset($_POST['selected_size'])) {
        $cart_item_data['selected_size'] = sanitize_text_field($_POST['selected_size']);
    }
    return $cart_item_data;
}


add_filter('woocommerce_get_item_data', 'display_racket_string_in_cart', 20, 2);
function display_racket_string_in_cart($cart_data, $cart_item)
{
    if (!empty($cart_item['racket_string'])) {
        $cart_data[] = array(
            'key'  => __('Stygų parinktis', 'ewsdev'),
            'value' => sanitize_textarea_field($cart_item['racket_string']),
        );
    }
    if (!empty($cart_item['selected_size'])) {
        $cart_data[] = array(
            'key'  => __('Pasirinkite dydį', 'ewsdev'),
            'value' => sanitize_textarea_field($cart_item['selected_size']),
        );
    }
    return $cart_data;
}

// Save and display custom fields (custom order item metadata)
add_action('woocommerce_checkout_create_order_line_item', 'save_order_item_custom_meta_data6', 10, 4);
function save_order_item_custom_meta_data6($item, $cart_item_key, $values, $order)
{
    if (!empty($values['racket_string'])) {
        $item->update_meta_data(__('Stygų parinktis', 'ewsdev'), $values['racket_string']);
    }
    if (!empty($values['selected_size'])) {
        $item->update_meta_data(__('Pasirinkite dydį', 'ewsdev'), $values['selected_size']);
    }
}

// Add readable "meta key" label name replacement
add_filter('woocommerce_order_item_display_meta_key', 'filter_wc_order_item_display_meta_key6', 10, 3);
function filter_wc_order_item_display_meta_key6($display_key, $meta, $item)
{
    if ($item->get_type() === 'line_item') {
        if ($meta->key === 'racket_string') {
            $display_key = __('Stygų parinktis', 'ewsdev');
        }
    }
    if ($item->get_type() === 'line_item') {
        if ($meta->key === 'selected_size') {
            $display_key = __('Pasirinkite dydį', 'ewsdev');
        }
    }
    return $display_key;
}

// popup string
add_filter('woocommerce_add_cart_item_data', 'add_tension_fields_to_cart', 10, 3);
function add_tension_fields_to_cart($cart_item_data, $product_id, $variation_id)
{
    if (isset($_POST['main_tension']) && isset($_POST['cross_tension'])) {
        $cart_item_data['main_tension'] = sanitize_text_field($_POST['main_tension']);
        $cart_item_data['cross_tension'] = sanitize_text_field($_POST['cross_tension']);
    }
    return $cart_item_data;
}

add_filter('woocommerce_get_item_data', 'display_tension_fields_cart', 10, 2);
function display_tension_fields_cart($item_data, $cart_item)
{
    if (isset($cart_item['main_tension'])) {
        $main = $cart_item['main_tension'] === '20' ? 'Eksperto rekomendacija' : $cart_item['main_tension'] . ' lbs';
        $item_data[] = array(
            'key'   => 'Main Tension',
            'value' => $main,
        );
    }

    if (isset($cart_item['cross_tension'])) {
        $cross = $cart_item['cross_tension'] === '20' ? 'Eksperto rekomendacija' : $cart_item['cross_tension'] . ' lbs';
        $item_data[] = array(
            'key'   => 'Cross Tension',
            'value' => $cross,
        );
    }

    return $item_data;
}



add_action('wp_ajax_popup_add_to_cart', 'handle_popup_add_to_cart');
add_action('wp_ajax_nopriv_popup_add_to_cart', 'handle_popup_add_to_cart');

function handle_popup_add_to_cart()
{
    if (!isset($_POST['product_id'])) {
        wp_send_json_error('No product ID.');
    }

    $product_id     = intval($_POST['product_id']);
    $quantity       = intval($_POST['quantity']) ?: 1;
    $main_tension   = sanitize_text_field($_POST['main_tension'] ?? '');
    $cross_tension  = sanitize_text_field($_POST['cross_tension'] ?? '');

    $cart_item_data = [];

    if ($main_tension) {
        $cart_item_data['main_tension'] = $main_tension;
    }
    if ($cross_tension) {
        $cart_item_data['cross_tension'] = $cross_tension;
    }

    $added = WC()->cart->add_to_cart($product_id, $quantity, 0, [], $cart_item_data);

    if ($added) {
        wp_send_json_success('Product added to cart.');
    } else {
        wp_send_json_error('Failed to add to cart.');
    }
}
