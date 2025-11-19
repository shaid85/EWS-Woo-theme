<?php

/**
 * "Order received" message.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/order-received.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.8.0
 *
 * @var WC_Order|false $order
 */

defined('ABSPATH') || exit;
?>
<div class="checkout_step py-4">
	<h3 class="mb-5"><?php esc_html_e('Atsiskatymas', 'woocommerce'); ?></h3>
	<ul class="simple-stepbar d-flex gap-4 mb-4 pb-md-2">
		<li class="d-flex align-items-center gap-4"><span class="step_n">1</span>Pristatymo ir mokėjimo informacija</li>
		<li class="d-flex align-items-center"><img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/arrow-east.png" width="20" alt="icon-image" /></li>
		<li class="active d-flex align-items-center gap-4"><span class="step_n">2</span>Patvirtinimas</li>
	</ul>
	<!-- Divider -->
	<hr class="divider my-4" />
</div>
<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received mb-4">
	<?php
	/**
	 * Filter the message shown after a checkout is complete.
	 *
	 * @since 2.2.0
	 *
	 * @param string         $message The message.
	 * @param WC_Order|false $order   The order created during checkout, or false if order data is not available.
	 */
	$message = apply_filters(
		'woocommerce_thankyou_order_received_text',
		esc_html(__('Dėkojame. Jūsų užsakymas priimtas.', 'woocommerce')),
		$order
	);

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $message;
	?>
</p>