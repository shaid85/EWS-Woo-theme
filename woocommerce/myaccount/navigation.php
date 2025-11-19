<?php

/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if (! defined('ABSPATH')) {
	exit;
}

do_action('woocommerce_before_account_navigation');
?>

<nav class="woocommerce-MyAccount-navigation custom_nav" aria-label="<?php esc_html_e('Account pages', 'woocommerce'); ?>">
	<ul>
		<li class="logout">
			<?php if (is_user_logged_in()) : ?>
				<div class="nav-user">
					<h3>Sveiki, <strong><?php echo esc_html(wp_get_current_user()->display_name); ?></strong></h3>
				</div>
			<?php endif; ?>
			<div class="logout-wrapper pt-2">
				<a href="<?php echo esc_url(wc_logout_url()); ?>" class="text-danger">Atsijungti <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/logout-icon.png" width="24" alt="icon-image" /></a>
			</div>
		</li>
	</ul>

	<ul class="menu_list">
		<?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
			<li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?>">
				<a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>" <?php echo wc_is_current_account_menu_item($endpoint) ? 'aria-current="page"' : ''; ?>>
					<?php echo esc_html($label); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action('woocommerce_after_account_navigation'); ?>