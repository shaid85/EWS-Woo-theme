<?php

/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined('ABSPATH') || exit;

/**
 * My Account navigation.
 *
 * @since 2.6.0
 */
do_action('woocommerce_account_navigation'); ?>

<div class="woocommerce-MyAccount-content">
	<?php
	/**
	 * My Account content.
	 *
	 * @since 2.6.0
	 */
	do_action('woocommerce_account_content');
	?>
</div>

<style>
	.address_box {
		border: 1px solid var(--color-border2, #d3d0d0);
		padding: 22px 22px;
		margin-bottom: 20px;
		border-radius: 8px;
	}

	.address_box .woocommerce-Address-title {
		display: flex;
		justify-content: space-between;
		border-bottom: 1px solid #ddd;
		padding-bottom: 15px;
		margin-bottom: 15px;
	}

	.address_box .woocommerce-Address-title h2 {
		font-weight: 600;
		margin: 0;
		line-height: 1.5;
	}

	.address_box .woocommerce-Address-title a {
		color: #000;
	}

	.address_box .woocommerce-Address-title::before,
	.address_box .woocommerce-Address-title::after {
		display: none !important;
	}

	.order_box {
		margin-top: 30px;
		background: #fff;
		padding: 20px;
		border: 1px solid #e0e0e0;
		border-radius: 5px;
	}

	.order-table {
		display: flex;
		flex-direction: column;
		border: 1px solid #ddd;
		border-radius: 8px;
		overflow: hidden;
	}

	.order-row {
		display: flex;
		padding: 10px 0;
		border-bottom: 1px solid #eee;
	}

	.order-head {
		font-weight: 600;
		background-color: #F5F6F7;
		border-bottom: 1px solid var(--color-border2, #ddd);
	}

	.order-col {
		flex: 1;
		padding: 0 10px;
		font-size: 14px;
	}

	.order-col a {
		color: #0071a1;
		text-decoration: none;
	}

	.order-col a:hover {
		text-decoration: underline;
	}
</style>