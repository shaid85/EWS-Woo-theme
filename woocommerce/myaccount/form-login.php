<?php

/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.7.0
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

do_action('woocommerce_before_customer_login_form'); ?>

<?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>

	<div class="u-columns col2-set pt-2 pt-sm-4 mb-5" id="customer_login">

		<div class="u-column1 col-1 login_box mb-4 mb-md-0">

		<?php endif; ?>

		<h3><?php esc_html_e('Login', 'woocommerce'); ?></h3>
		<p><?php esc_html_e('Jeigu turite paskyrą, prisijunkite čia.', 'woocommerce'); ?></p>
		<!-- Divider -->
		<!-- <hr class="divider my-4" />

		<div class="py-0 text-center">
			<img src="https://tennis.urbanlabs.lt/wp-content/uploads/2025/08/media-fbgoo.png" width="130" height="60" alt="auth">
		</div> -->
		<!-- Divider -->
		<hr class="divider my-4" />
		<form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>

			<?php do_action('woocommerce_login_form_start'); ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label class="d-none" for="username"><?php esc_html_e('Username or email address', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" placeholder="Įveskite elektroninį paštą" id="username" autocomplete="email" value="<?php echo (! empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" />
				<?php // @codingStandardsIgnoreLine 
				?>
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label class="d-none" for="password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" placeholder="Įveskite slaptažodį" id="password" autocomplete="current-password" required aria-required="true" />
			</p>

			<?php do_action('woocommerce_login_form'); ?>

			<p class="form-row">
				<label class="d-none" class="mt-4 custom-checkbox woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /><span class="checkmark"></span> <span><?php esc_html_e('Remember me', 'woocommerce'); ?></span>
				</label>
				<?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
				<button type="submit" class="w-100 mt-2 woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="login" value="<?php esc_attr_e('Log in', 'woocommerce'); ?>"><?php esc_html_e('Log in', 'woocommerce'); ?></button>
			</p>
			<p class="woocommerce-LostPassword lost_password text-center">
				<a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'woocommerce'); ?></a>
			</p>

			<?php do_action('woocommerce_login_form_end'); ?>

		</form>

		<?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>

		</div>

		<div class="u-column2 col-2 login_box">

			<h3><?php esc_html_e('Neturite paskyros?', 'woocommerce'); ?></h3>
			<p><?php esc_html_e('Lengvai susikurkite paskyrą ir užsitikrinkite patogumą perkant vėl.', 'woocommerce'); ?></p>

			<!-- Divider -->
			<hr class="divider my-4" />

			<a href="<?php echo site_url(); ?>/registruotis/" class="btn register_btn d-block mb-4"><?php esc_html_e('Registruotis', 'woocommerce'); ?></a>
			<script>
				// document.querySelector('.register_btn').addEventListener('click', function(e) {
				// 	e.preventDefault();
				// 	const target = document.getElementById('Register');
				// 	if (target) {
				// 		target.scrollIntoView({
				// 			behavior: 'smooth'
				// 		});
				// 	}
				// });
			</script>
			<div class="reg_info_box cart_totals pb-4">
				<h3 class="mb-3 text-16">Kodėl verta registruotis?</h3>
				<ul class="d-flex flex-column gap-2">
					<!-- <li>Asmeniniai pasiūlymai tik Jums</li> -->
					<li>Asmeniniai pasiūlymai tik Jums</li>
					<li>Matykite visus savo užsakymus</li>
					<li>Visada žinosite savo užsakymo būseną</li>
				</ul>
			</div>

		</div>

	</div>



<?php endif; ?>

<?php do_action('woocommerce_after_customer_login_form'); ?>