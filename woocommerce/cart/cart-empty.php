<?php

/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
// do_action('woocommerce_cart_is_empty');
?>
<div class="container pb-5 mb-md-5 mb-md-3">
	<div class="h-100" style="min-height: 400px;">
		<h2 class="woocommerce-products-header__title page-title d-flex gap-2 align-items-center mb-3">
			<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/search3.png" width="32" height="32" alt="icon" class="d-none search_icon " />
			Mano krepšelis
		</h2>
		<div class="d-none not_found">
			<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/error3.png" alt="icon" class="search_icon" />
			<aside>Jūsų krepšelis dar tuščias <strong><?php echo esc_html(get_search_query()); ?></strong></aside>
		</div>
		<!-- Divider -->
		<hr class="divider my-4" />
		<p class="my-4 py-3">Jūsų krepšelis dar tuščias</p>
		<a class="btn arrow_btn black_btn back" href="<?php echo site_url(); ?>/shop/"><span></span> Tęsti apsipirkimą</a>
	</div>
</div>
<?php
if (wc_get_page_id('shop') > 0) : ?>
	<p class="return-to-shop">

		<a class="d-none button wc-backward<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
			<?php
			/**
			 * Filter "Return To Shop" text.
			 *
			 * @since 4.6.0
			 * @param string $default_text Default text.
			 */
			echo esc_html(apply_filters('woocommerce_return_to_shop_text', __('Return to shop', 'woocommerce')));
			?>
		</a>
	</p>
<?php endif; ?>