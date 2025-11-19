<?php

/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined('ABSPATH') || exit;

?>
<div class="cart_totals <?php echo (WC()->customer->has_calculated_shipping()) ? 'calculated_shipping' : ''; ?>">

	<?php do_action('woocommerce_before_cart_totals'); ?>

	<h3><?php esc_html_e('Jūsų užsakymas', 'woocommerce'); ?></h3>

	<table cellspacing="0" class="shop_table shop_table_responsive">
		<?php if (wc_coupons_enabled()) { ?>
			<div class="custom-coupon-wrapper d-flex gap-2">
				<label for="custom_coupon_code" class="d-none"><?php esc_html_e('Coupon:', 'woocommerce'); ?></label>
				<input type="text" id="custom_coupon_code" placeholder="<?php esc_attr_e('Nuolaidos kodas', 'woocommerce'); ?>" />
				<button type="button" id="apply_custom_coupon" class="button"><?php esc_html_e('Taikyti', 'woocommerce'); ?></button>
			</div>
		<?php } ?>
		<?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
				<th><?php wc_cart_totals_coupon_label($coupon); ?></th>
				<td data-title="<?php echo esc_attr(wc_cart_totals_coupon_label($coupon, false)); ?>"><?php wc_cart_totals_coupon_html($coupon); ?></td>
			</tr>
		<?php endforeach; ?>
		<tr class="cart-subtotal">
			<th><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
			<td data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>


		<?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

			<?php do_action('woocommerce_cart_totals_before_shipping'); ?>

			<?php // wc_cart_totals_shipping_html(); 
			?>

			<?php do_action('woocommerce_cart_totals_after_shipping'); ?>

		<?php elseif (WC()->cart->needs_shipping() && 'yes' === get_option('woocommerce_enable_shipping_calc')) : ?>

			<tr class="shipping d-none">
				<th><?php esc_html_e('Shipping', 'woocommerce'); ?></th>
				<td data-title="<?php esc_attr_e('Shipping', 'woocommerce'); ?>"><?php woocommerce_shipping_calculator(); ?></td>
			</tr>

		<?php endif; ?>

		<?php
		// Set your free shipping threshold
		$free_shipping_min_amount = 99;

		// Get cart subtotal
		$cart_total = WC()->cart->subtotal;

		// Calculate amount remaining
		$remaining = $free_shipping_min_amount - $cart_total;

		if ($remaining > 0) {
		?>
			<tr class="fee">
				<th>Pristatymas</th>
				<td>
					<div class="not_shipping">Apskaičiuojama kitame žingsnyje</div>
				</td>
			</tr>
			<script>
				jQuery(function($) {
					function updateShippingFee() {
						const $selectedRadio = $('#shipping_method input[type="radio"]:checked');
						if ($selectedRadio.length) {
							const $li = $selectedRadio.closest('li');
							const $price = $li.find('.woocommerce-Price-amount').first();

							if ($price.length) {
								$('.shipping_fee').html($price.prop('outerHTML'));
							} else {
								$('.shipping_fee').html('');
							}
						}
					}

					// Initial load
					updateShippingFee();

					// Update when WooCommerce updates shipping methods
					$(document.body).on('updated_shipping_method updated_wc_div', updateShippingFee);

					// Update when user clicks
					$(document).on('change', '#shipping_method input[type="radio"]', updateShippingFee);
				});
			</script>

		<?php } else {	 ?>
			<tr class="fee">
				<th>Pristatymas</th>
				<td data-title="Pristatymas">Nemokamas </td>
			</tr>
		<?php } ?>

		<?php foreach (WC()->cart->get_fees() as $fee) : ?>
			<tr class="fee">
				<th><?php echo esc_html($fee->name); ?></th>
				<td data-title="<?php echo esc_attr($fee->name); ?>"><?php wc_cart_totals_fee_html($fee); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php
		if (wc_tax_enabled() && ! WC()->cart->display_prices_including_tax()) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if (WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()) {
				/* translators: %s location. */
				$estimated_text = sprintf(' <small>' . esc_html__('(estimated for %s)', 'woocommerce') . '</small>', WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]]);
			}

			if ('itemized' === get_option('woocommerce_tax_total_display')) {
				foreach (WC()->cart->get_tax_totals() as $code => $tax) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
						<th>PVM (21%) įskaičiuota</th>
						<td data-title="<?php echo esc_attr($tax->label); ?>"><?php echo wp_kses_post($tax->formatted_amount); ?></td>
					</tr>
				<?php
				}
			} else {
				?>
				<tr class="tax-total">
					<th><?php echo esc_html(WC()->countries->tax_or_vat()) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
						?></th>
					<td data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
		<?php
			}
		}
		?>

		<?php do_action('woocommerce_cart_totals_before_order_total'); ?>

		<tr class="order-total">
			<th><?php esc_html_e('Total', 'woocommerce'); ?></th>
			<td data-title="<?php esc_attr_e('Total', 'woocommerce'); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action('woocommerce_cart_totals_after_order_total'); ?>

	</table>

	<div class="wc-proceed-to-checkout">
		<?php // do_action('woocommerce_proceed_to_checkout'); 
		?>
		<a href="<?php echo site_url(); ?>/checkout/" class="checkout-button button alt wc-forward arrow_btn btn black_btn mt-1">
			Tęsti <span></span></a>
	</div>

	<?php do_action('woocommerce_after_cart_totals'); ?>


</div>