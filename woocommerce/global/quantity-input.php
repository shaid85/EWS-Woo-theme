<?php

/**
 * Custom quantity field with - / + buttons.
 * Place in  your‑child‑theme/woocommerce/global/quantity-input.php
 *
 * @package WooCommerce/Templates
 */

defined('ABSPATH') || exit;

if ($max_value && $min_value === $max_value) : ?>
	<input type="hidden" id="<?php echo esc_attr($input_id); ?>" class="qty"
		name="<?php echo esc_attr($input_name); ?>"
		value="<?php echo esc_attr($min_value); ?>" />
<?php else : ?>
	<div class="quantity-wrapper custom-quantity-input d-flex align-items-center gap-0">
		<button type="button" class="qty-btn qty-minus">−</button>

		<input
			type="number"
			id="<?php echo esc_attr($input_id); ?>"
			class="input-text qty text"
			step="<?php echo esc_attr($step); ?>"
			min="<?php echo esc_attr($min_value); ?>"
			max="<?php echo esc_attr(0 < $max_value ? $max_value : ''); ?>"
			name="<?php echo esc_attr($input_name); ?>"
			value="<?php echo esc_attr($input_value); ?>"
			title="<?php echo esc_attr_x('Qty', 'Product quantity input tooltip', 'woocommerce'); ?>"
			inputmode="<?php echo esc_attr($inputmode); ?>" />

		<button type="button" class="qty-btn qty-plus">+</button>
	</div>
<?php endif; ?>