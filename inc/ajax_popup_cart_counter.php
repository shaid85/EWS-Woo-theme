<?php
// [popup_minicart]
function ajax_popup_mini_cart_counter()
{

    global $woocommerce;

    ob_start();

?>

    <!-- Cart Popup -->
    <div id="cart-popup" style="display: none;">
        <div class="cart-popup-inner WooCommerce mini-cart h-100">
            <div class="minicart_title">
                <h4><?php echo __('Mano krepšelis', 'ewsdev'); ?></h4>
                <div class="icon-box">
                    <a id="close-cart" class="cart-contents no-scroll" href="#">
                        <div class="icon-holder bg icon-close">

                        </div>
                    </a>
                </div>
            </div>
            <div class="woomini">
                <?php woocommerce_mini_cart(); ?>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartIcon = document.querySelector('.cart-contents');
            const cartPopup = document.getElementById('cart-popup');
            const closeCart = document.getElementById('close-cart');

            cartIcon.addEventListener('click', function(e) {
                e.preventDefault();
                cartPopup.style.display = 'block';
            });

            closeCart.addEventListener('click', function() {
                cartPopup.style.display = 'none';
            });
        });
    </script>


    <?php
    return ob_get_clean();
}
add_shortcode('popup_minicart', 'ajax_popup_mini_cart_counter');



add_action('woocommerce_widget_shopping_cart_before_buttons', 'show_free_shipping_notice');

function show_free_shipping_notice()
{
    // Set your free shipping threshold
    $free_shipping_min_amount = 99;

    // Get cart subtotal
    $cart_total = WC()->cart->subtotal;

    // Calculate amount remaining
    $remaining = $free_shipping_min_amount - $cart_total;

    if ($remaining > 0) {
    ?>
        <div class="shipping_card">
            <div class="info m-0">
                <div class="icon">
                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/Delivery22.png" alt="icon-image">
                </div>
                <div class="text">
                    <div class="gray_text text-14">
                        <div class="woocommerce-message">
                            Iki nemokamo pristatymo Jums liko <?php echo wc_price($remaining, ['currency' => get_woocommerce_currency(), 'decimals' => 2]); ?>
                        </div>
                        <span>Nemokamas pristatymas nuo 99 €.</span>
                    </div>
                </div>
            </div> <!--  end info -->
        </div>
    <?php
    } else {
    ?>
        <div class="shipping_card success">
            <div class="info m-0">
                <div class="icon">
                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/Delivery22.png" alt="icon-image">
                </div>
                <div class="text">
                    <div class="gray_text text-14">
                        <div class="woocommerce-message">Jums taikomas nemokamas pristatymas!</div>
                        <!-- <span>Nemokamas pristatymas nuo 99 €.</span> -->
                    </div>
                </div>
            </div> <!--  end info -->
        </div>
<?php
    }
}
