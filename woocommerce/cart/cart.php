<?php

/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined('ABSPATH') || exit;

// do_action('woocommerce_before_cart'); 
?>
<div class="container gx-0">
	<div class="row">
		<div class="col-lg-8 mb-4 mb-md-0">
			<div class="head_cart d-flex align-items-center mb-2 mb-md-4 pb-2">
				<h2 class="cart_title">Mano krepšelis</h2>
				<p class="cart-total-items ms-md-4 ms-3">
					<?php
					$total_items = WC()->cart->get_cart_contents_count();

					if ($total_items === 1) {
						// exactly 1
						$label = 'prekė';
					} elseif ($total_items >= 2 && $total_items <= 9) {
						// 2–9
						$label = 'prekės';
					} else {
						// 10 and above
						$label = 'prekių';
					}

					echo $total_items . ' ' . $label;
					?>

				</p>

			</div>

			<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
				<?php do_action('woocommerce_before_cart_table'); ?>

				<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
					<thead>
						<tr>

							<th class="product-thumbnail"><span class="screen-reader-text"><?php esc_html_e('Thumbnail image', 'woocommerce'); ?></span></th>
							<th class="product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
							<th class="product-price"><?php esc_html_e('Price', 'woocommerce'); ?></th>
							<th class="product-quantity"><?php esc_html_e('Quantity', 'woocommerce'); ?></th>
							<th class="product-subtotal"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
							<th class="product-remove"><span class="screen-reader-text"><?php esc_html_e('Remove item', 'woocommerce'); ?></span></th>
						</tr>
					</thead>
					<tbody>
						<?php do_action('woocommerce_before_cart_contents'); ?>
						<!-- Custom cart.php loaded -->
						<?php
						foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
							$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
							$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
							/**
							 * Filter the product name.
							 *
							 * @since 2.1.0
							 * @param string $product_name Name of the product in the cart.
							 * @param array $cart_item The product in the cart.
							 * @param string $cart_item_key Key for the product in the cart.
							 */
							$product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);

							if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
								$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
						?>
								<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">


									<td class="product-thumbnail">
										<?php
										/**
										 * Filter the product thumbnail displayed in the WooCommerce cart.
										 *
										 * This filter allows developers to customize the HTML output of the product
										 * thumbnail. It passes the product image along with cart item data
										 * for potential modifications before being displayed in the cart.
										 *
										 * @param string $thumbnail     The HTML for the product image.
										 * @param array  $cart_item     The cart item data.
										 * @param string $cart_item_key Unique key for the cart item.
										 *
										 * @since 2.1.0
										 */
										$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

										if (! $product_permalink) {
											echo $thumbnail; // PHPCS: XSS ok.
										} else {
											printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
										}
										?>
									</td>

									<td class="product-name" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
										<?php
										if (! $product_permalink) {
											echo wp_kses_post($product_name . '&nbsp;');
										} else {
											/**
											 * This filter is documented above.
											 *
											 * @since 2.1.0
											 */
											echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
										}

										do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

										// Meta data.
										echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.

										// Backorder notification.
										if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
											echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
										}
										?>

									</td>

									<td class="product-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
										<?php
										echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
										?>
									</td>

									<td class="product-quantity" data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
										<?php
										if ($_product->is_sold_individually()) {
											$min_quantity = 1;
											$max_quantity = 1;
										} else {
											$min_quantity = 0;
											$max_quantity = $_product->get_max_purchase_quantity();
										}

										$product_quantity = woocommerce_quantity_input(
											array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $max_quantity,
												'min_value'    => $min_quantity,
												'product_name' => $product_name,
											),
											$_product,
											false
										);

										echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
										?>
									</td>

									<td class="product-subtotal" data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
										<?php
										echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
										?>
									</td>
									<td class="product-remove">
										<?php
										echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											'woocommerce_cart_item_remove_link',
											sprintf(
												'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><img src="' . get_stylesheet_directory_uri()
													. '/assets/images/close.png" alt="icon" /></a>',
												esc_url(wc_get_cart_remove_url($cart_item_key)),
												/* translators: %s is the product name */
												esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
												esc_attr($product_id),
												esc_attr($_product->get_sku())
											),
											$cart_item_key
										);
										?>
									</td>
								</tr>
						<?php

							}
						}
						?>

						<?php do_action('woocommerce_cart_contents'); ?>

						<tr>
							<td colspan="6" class="actions h-0">
								<?php if (wc_coupons_enabled()) { ?>
									<div class="d-none coupon">
										<label for="coupon_code" class="screen-reader-text"><?php esc_html_e('Coupon:', 'woocommerce'); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" /> <button type="submit" class="button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> wc-forward" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_html_e('Apply coupon', 'woocommerce'); ?></button>
										<?php do_action('woocommerce_cart_coupon'); ?>
									</div>
								<?php } ?>
								<button type="submit" class="button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> wc-forward" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>

								<?php do_action('woocommerce_cart_actions'); ?>

								<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
							</td>
						</tr>

						<?php do_action('woocommerce_after_cart_contents'); ?>

					</tbody>
				</table>
				<?php do_action('woocommerce_after_cart_table'); ?>
			</form>
			<!-- Divider -->
			<hr class="divider my-0 border-0" />
			<a class="btn arrow_btn white_btn back bold" href="<?php echo site_url(); ?>/parduotuve/"><span></span> Tęsti apsipirkimą</a>
		</div>
		<!-- End col -->
		<div class="col-lg-4">
			<div class="your_order">
				<?php do_action('woocommerce_before_cart_collaterals'); ?>

				<div class="cart-collaterals">
					<?php
					/**
					 * Cart collaterals hook.
					 *
					 * @hooked woocommerce_cross_sell_display
					 * @hooked woocommerce_cart_totals - 10
					 */
					do_action('woocommerce_cart_collaterals');
					?>
				</div>
			</div><!-- your_order -->

			<div class="icon_list mt-4">
				<div class="info m-0 col-6 align-items-start">
					<div class="icon s2 align-items-start">
						<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/Delivery2.png" alt="icon-image">
					</div>
					<div class="text">
						<p class="gray_text text-12">Nemokamas pristatymas perkant nuo 99 EUR</p>
					</div>
				</div> <!--  end info -->
				<div class="info m-0 align-items-start pe-lg-0">
					<div class="icon s2 align-items-start">
						<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/Payment2.png" alt="icon-image">
					</div>
					<div class="text">
						<p class="gray_text text-12">Garantuotas saugus
							pirkimas</p>
					</div>
				</div> <!--  end info -->

			</div>
		</div><!-- End col 4-->
	</div><!-- End row -->
	<?php // do_action('woocommerce_after_cart');

	// do_action('woocommerce_before_cart'); 
	?>

</div><!-- End Container -->
<script>
	jQuery(document).ready(function($) {
		function triggerUpdateCart() {
			const $updateBtn = $('.woocommerce-cart-form [name="update_cart"]');

			// Ensure it's enabled
			if ($updateBtn.prop('disabled')) {
				$updateBtn.prop('disabled', false);
			}

			$updateBtn.trigger('click');
		}

		// Handle + / - buttons
		$('body').on('click', '.quantity .plus, .quantity .minus', function() {
			setTimeout(triggerUpdateCart, 300); // wait a moment to allow input update
		});

		// Handle manual quantity input changes too
		$('body').on('change', '.woocommerce-cart-form .qty', function() {
			triggerUpdateCart();
		});
	});

	jQuery(document).ready(function($) {
		$('#apply_custom_coupon').on('click', function(e) {
			e.preventDefault();

			var couponCode = $('#custom_coupon_code').val().trim();

			if (!couponCode) {
				alert('Please enter a coupon code.');
				return;
			}

			// Mimic WooCommerce's native AJAX coupon apply
			var $form = $('form.woocommerce-cart-form');

			// Set the coupon code input inside the form
			let $couponInput = $form.find('input[name="coupon_code"]');

			if ($couponInput.length === 0) {
				$couponInput = $('<input />', {
					type: 'hidden',
					name: 'coupon_code',
					value: couponCode
				});
				$form.append($couponInput);
			} else {
				$couponInput.val(couponCode);
			}

			// Find the actual WooCommerce apply_coupon button and trigger click
			const $applyBtn = $form.find('button[name="apply_coupon"]');

			if ($applyBtn.length) {
				// Ensure it's not disabled
				$applyBtn.prop('disabled', false);
				$applyBtn.trigger('click');
			} else {
				// Fallback: create one and click it
				const $newBtn = $('<button>', {
					type: 'submit',
					name: 'apply_coupon',
					style: 'display: none;'
				}).appendTo($form);
				$newBtn.trigger('click');
			}
		});
	});
</script>
<?php
add_action('wp_footer', 'hide_update_cart_button');
function hide_update_cart_button()
{
	if (is_cart()) {
?>
		<style>
			.woocommerce .actions .button[name="update_cart"] {
				display: none !important;
			}
		</style>
<?php
	}
}
