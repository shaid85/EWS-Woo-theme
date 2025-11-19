<?php

/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_mini_cart'); ?>

<?php if (WC()->cart && ! WC()->cart->is_empty()) : ?>

	<ul class="woocommerce-mini-cart cart_list product_list_widget xzsadasd<?php echo esc_attr($args['list_class']); ?>">
		<?php
		do_action('woocommerce_before_mini_cart_contents');

		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
			$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
			$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

			if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
				/**
				 * This filter is documented in woocommerce/templates/cart/cart.php.
				 *
				 * @since 2.1.0
				 */
				$brand_terms      = get_the_terms($_product->get_id(), 'product_brand');
				$product_name      = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
				$thumbnail         = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
				$product_price     = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
				$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
		?>
				<li class="woocommerce-mini-cart-item <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>">

					<?php if (empty($product_permalink)) : ?>
						<div class="pd_img">
							<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							?>
						</div>
						<div class="pd_data">

							<div class="product_name">
								<?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s" data-success_message="%s"></a>',
										esc_url(wc_get_cart_remove_url($cart_item_key)),
										/* translators: %s is the product name */
										esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
										esc_attr($product_id),
										esc_attr($cart_item_key),
										esc_attr($_product->get_sku()),
										/* translators: %s is the product name */
										esc_attr(sprintf(__('&ldquo;%s&rdquo; has been removed from your cart', 'woocommerce'), wp_strip_all_tags($product_name)))
									),
									$cart_item_key
								);
								?>
								<h3 class="text-14 mb-2 mb-md-3 mt-1">
									<?php if ($brand_terms && !is_wp_error($brand_terms)) {
										echo '<span class="brand-name">' . esc_html($brand_terms[0]->name) . '</span>';
									} ?>
								</h3>
								<h5><?php echo wp_kses_post($product_name); ?></h5>
							</div>
							<div class="pd_price">
								<div class="item_modi"></div>
								<?php // echo wc_get_formatted_cart_item_data($cart_item); 
								?>
								<?php echo apply_filters('woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf('%s &times; %s', $cart_item['quantity'], $product_price) . '</span>', $cart_item, $cart_item_key); ?>
							</div>
						</div>

					<?php else : ?>
						<div class="pd_img">
							<a href="<?php echo esc_url($product_permalink); ?>">
								<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
								?>
							</a>
						</div>
						<div class="pd_data">
							<div class="product_name">
								<?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s" data-success_message="%s"></a>',
										esc_url(wc_get_cart_remove_url($cart_item_key)),
										/* translators: %s is the product name */
										esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
										esc_attr($product_id),
										esc_attr($cart_item_key),
										esc_attr($_product->get_sku()),
										/* translators: %s is the product name */
										esc_attr(sprintf(__('&ldquo;%s&rdquo; has been removed from your cart', 'woocommerce'), wp_strip_all_tags($product_name)))
									),
									$cart_item_key
								);
								?>
								<h3 class="text-14 mb-2 mb-md-3 mt-1">
									<?php if ($brand_terms && !is_wp_error($brand_terms)) {
										echo '<span class="brand-name">' . esc_html($brand_terms[0]->name) . '</span>';
									} ?>
								</h3>
								<h5><?php echo wp_kses_post($product_name); ?></h5>
							</div>
							<div class="pd_price d-flex justify-content-between">
								<div class="item_modi"></div>
								<?php // echo wc_get_formatted_cart_item_data($cart_item);
								?>
								<?php echo apply_filters('woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf('%s &times; %s', $cart_item['quantity'], $product_price) . '</span>', $cart_item, $cart_item_key);
								?>
							</div>
						<?php endif; ?>
						</div>


				</li>
		<?php
			}
		}

		do_action('woocommerce_mini_cart_contents');
		?>
	</ul>

	<div class="totalpart">
		<p class="woocommerce-mini-cart__total total">
			<?php
			/**
			 * Hook: woocommerce_widget_shopping_cart_total.
			 *
			 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
			 */
			do_action('woocommerce_widget_shopping_cart_total');
			?>
		</p>


		<?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>

		<!-- Divider -->
		<hr class="divider my-4" />


		<p class="woocommerce-mini-cart__buttons buttons"><?php do_action('woocommerce_widget_shopping_cart_buttons'); ?></p>

		<?php do_action('woocommerce_widget_shopping_cart_after_buttons'); ?>
	</div>
<?php else : ?>

	<p class="woocommerce-mini-cart__empty-message p-4"><?php esc_html_e('No products in the cart.', 'woocommerce'); ?></p>

<?php endif; ?>

<?php do_action('woocommerce_after_mini_cart'); ?>

<script>
	function setMiniCartHeight() {
		const miniCart = document.getElementById("cart-popup");
		const header = document.querySelector(".site-header");
		const innerCart = document.querySelector(".cart-popup-inner");

		if (miniCart && header && innerCart) {
			let headerHeight = 5;

			// If sticky header is active, add additional height
			if (header.classList.contains("sticky")) {
				headerHeight -= 0;
			}

			const deviceHeight = window.innerHeight;
			const popupHeight = deviceHeight - headerHeight;

			// Set outer popup height
			miniCart.style.height = `${popupHeight}px`;

			// Set max-height of inner container minus padding (20px top + 20px bottom)
			const paddingOffset = 40;
			const innerMaxHeight = popupHeight - paddingOffset;

			// Set max-height and check if content overflows
			innerCart.style.maxHeight = `${innerMaxHeight}px`;
			innerCart.style.overflowY = (innerCart.scrollHeight > innerMaxHeight) ? "scroll" : "hidden";
		}
	}

	// Trigger on load, resize, scroll
	window.addEventListener("load", setMiniCartHeight);
	window.addEventListener("resize", setMiniCartHeight);
	window.addEventListener("scroll", setMiniCartHeight);
</script>