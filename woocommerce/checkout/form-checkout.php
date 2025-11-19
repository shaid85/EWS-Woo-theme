<?php

/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

if (! defined('ABSPATH')) {
	exit;
}

// do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in()) {
	echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
	return;
}

?>

<div class="container login_box cupon_box">
	<?php do_action('woocommerce_before_checkout_form');
	?>
</div>
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__('Checkout', 'woocommerce'); ?>">
	<div class="container">
		<div class="row">
			<div class="col-md-8 pe-md-3">
				<div class="checkout_step pb-4">
					<h2 class="mb-5"><?php esc_html_e('Atsiskatymas', 'woocommerce'); ?></h2>
					<ul class="simple-stepbar d-flex gap-4 mb-4 pb-md-2">
						<li class="active d-flex align-items-center gap-4"><span class="step_n">1</span>
							<aside>Pristatymo ir mokėjimo informacija</aside>
						</li>
						<li class="d-flex align-items-center"><img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/arrow-east.png" width="20" alt="icon-image" /></li>
						<li class="d-flex align-items-center gap-4"><span class="step_n">2</span>
							<aside>Patvirtinimas</aside>
						</li>
					</ul>
					<!-- Divider -->
					<hr class="divider my-4" />
				</div>
				<?php if ($checkout->get_checkout_fields()) : ?>

					<?php do_action('woocommerce_checkout_before_customer_details'); ?>

					<div class="col2-set" id="customer_details">
						<div class="checkout_input">
							<?php do_action('woocommerce_checkout_billing'); ?>
							<?php do_action('woocommerce_checkout_shipping'); ?>
						</div>
					</div>

					<?php do_action('woocommerce_checkout_after_customer_details'); ?>

				<?php endif; ?>

				<?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

				<div class="container8 checkout-payment-methods py-4 py-md-5">
					<?php do_action('ews_custom_payment_hook'); ?>
				</div>

			</div>

			<div class="col-md-4 d-none d-md-block">
				<div class="fixed_box">
					<div class="side_bar_details cart_totals mt-1">
						<h3 id="order_review_heading"><?php esc_html_e('Jūsų užsakymas', 'woocommerce'); ?></h3>

						<?php do_action('woocommerce_checkout_before_order_review'); ?>

						<div id="order_review" class="woocommerce-checkout-review-order">
							<?php do_action('woocommerce_checkout_order_review'); ?>
						</div>

						<?php do_action('woocommerce_checkout_after_order_review'); ?>

					</div>

					<div class="icon_list mt-4">
						<div class="info m-0">
							<div class="icon s2">
								<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/Delivery2.png" alt="icon-image">
							</div>
							<div class="text">
								<p class="gray_text text-12">Nemokamas pristatymas perkant nuo 99 EUR</p>
							</div>
						</div> <!--  end info -->
						<div class="info m-0">
							<div class="icon s2">
								<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/Payment2.png" alt="icon-image">
							</div>
							<div class="text">
								<p class="gray_text text-12">Garantuotas saugus
									pirkimas</p>
							</div>
						</div> <!--  end info -->

					</div>
				</div>

			</div>
		</div>
	</div>


</form>

<script>
	jQuery(document).ready(function($) {
		function toggleCompanyFields() {
			if ($('#billing_for_company').is(':checked')) {
				$('#billing_company_field, #billing_company_code_field, #billing_vat_code_field,p#billing_c_apartment_field,p#billing_c_street_field,p#billing_c_country_field,p#billing_c_city_field,p#billing_cp_pcode_field').show();
			} else {
				$('#billing_company_field, #billing_company_code_field, #billing_vat_code_field,p#billing_c_apartment_field,p#billing_c_street_field,p#billing_c_country_field,p#billing_c_city_field,p#billing_cp_pcode_field').hide();
			}
		}

		// Run on page load
		toggleCompanyFields();

		// Run on checkbox change
		$('#billing_for_company').on('change', toggleCompanyFields);
	});
</script>

<style>
	.woocommerce .fake-heading .woocommerce-input-wrapper,
	.woocommerce .fake-heading .optional {
		display: none;
	}

	.woocommerce .fake-heading label {
		font-size: 24px;
		font-weight: 600;
		margin-bottom: 0.5rem;
	}

	p#order_comments_field .optional {
		display: none;
	}


	p#custom_heading_field label,
	/* p#billing_country_field label, */
	#billing_mail_agree_field label {
		display: block !important;
	}

	#billing_mail_agree_field label,
	#billing_for_company_field label {
		display: flex !important;
	}

	.checkout_input .custom-checkbox input:checked+.checkmark {
		background-color: var(--black);
	}

	.checkout_input .custom-checkbox input:checked+.checkmark::after {
		content: "";
		position: absolute;
		left: 8px;
		top: 5px;
		width: 6px;
		height: 11px;
		border: solid white;
		border-width: 0 1px 1px 0;
		transform: rotate(45deg);
	}

	.woocommerce form #billing_mail_agree_field .input-checkbox {
		width: 20px;
		height: 20px;
		accent-color: black;
		/* Changes the tick color */
	}

	#order_review .product-name span.woocommerce-Price-amount.amount {
		font-weight: 600;
	}

	textarea#billing_cp_none {
		height: auto;
	}

	p#custom_gap_field {
		visibility: hidden;
		height: 0;
		margin: 0 !important;
		padding: 0 !important;
	}

	p#billing_country_field strong {
		font-weight: 500;
	}

	p#billing_country_field {
		display: none;
	}

	.shop_table tr.montonio-pickup-point {
		display: none !important;
	}

	.montonio-pickup-point-select-wrapper .required {
		color: red;
	}

	.woocommerce-invalid .choices {
		border: 1px solid red;
	}

	.checkout-inline-error-message {
		color: red;
		font-size: 0.875em;
		margin-top: 5px;
	}

	.choices {
		margin-top: 8px;
	}
</style>

<script>
	jQuery(function($) {
		function fixCheckoutFieldClasses() {
			// Example: Set correct classes again
			$('#billing_email_field').removeClass().addClass('form-row form-row-wide validate-required validate-email');
			$('#billing_mail_agree_field').removeClass().addClass('form-row form-row-wide custom-checkbox-wrapper');
			$('#custom_heading_field').removeClass().addClass('form-row form-row-wide fake-heading');

			$('#billing_first_name_field').removeClass().addClass('form-row form-row-first validate-required');
			$('#billing_last_name_field').removeClass().addClass('form-row form-row-last validate-required');

			$('#billing_phone_field').removeClass().addClass('form-row form-row-first validate-phone  validate-required');
			$('#billing_address_1_field').removeClass().addClass('form-row form-row-last validate-required');
			$('#billing_address_2_field').removeClass().addClass('form-row form-row-first');

			$('#billing_city_field').removeClass().addClass('form-row form-row-last validate-required');
			$('#billing_country_field').removeClass().addClass('form-row form-row-first validate-required');
			$('#billing_postcode_field').removeClass().addClass('form-row form-row-first validate-postcode  validate-required');
			$('#billing_state_field').removeClass().addClass('form-row form-row-last validate-required validate-state');
		}

		// On page load
		fixCheckoutFieldClasses();

		// On country field change
		$('#billing_country').on('change', function() {
			fixCheckoutFieldClasses();
		});

		// On checkout updates (after country/state change)
		$('body').on('updated_checkout', function() {
			fixCheckoutFieldClasses();
		});
	});
</script>
<script>
	// document.addEventListener('DOMContentLoaded', function() {
	// 	const CHECK_INTERVAL = 300;
	// 	const MAX_WAIT = 10000;
	// 	let waited = 0;

	// 	function setupMontonioValidation() {
	// 		const select = document.getElementById('montonio-shipping-pickup-point-dropdown');
	// 		if (!select) return;

	// 		const choicesWrapper = select.closest('.choices');
	// 		if (!choicesWrapper) return;

	// 		// Prevent duplicate wrapping
	// 		if (choicesWrapper.closest('.form-row')) return;

	// 		// Create WooCommerce-style wrapper
	// 		const formRow = document.createElement('p');
	// 		formRow.className = 'form-row form-row-wide validate-required';
	// 		formRow.id = 'montonio_pickup_point_field';

	// 		const label = document.createElement('label');
	// 		label.setAttribute('for', select.id);
	// 		label.innerHTML = '';

	// 		const wrapper = document.createElement('span');
	// 		wrapper.className = 'woocommerce-input-wrapper';

	// 		choicesWrapper.parentNode.insertBefore(formRow, choicesWrapper);
	// 		formRow.appendChild(label);
	// 		formRow.appendChild(wrapper);
	// 		wrapper.appendChild(choicesWrapper);

	// 		const clonedInput = choicesWrapper.querySelector('.choices__input--cloned');
	// 		const placeholderOption = choicesWrapper.querySelector('.choices__item--choice.choices__placeholder');
	// 		const placeholderId = placeholderOption?.id || '';
	// 		const errorMsgClass = 'checkout-inline-error-message';

	// 		jQuery('form.checkout').off('checkout_place_order.montonio').on('checkout_place_order.montonio', function() {
	// 			const activeDescendant = clonedInput?.getAttribute('aria-activedescendant');

	// 			const existingError = formRow.querySelector('.' + errorMsgClass);
	// 			if (existingError) existingError.remove();

	// 			if (activeDescendant === placeholderId || !activeDescendant) {
	// 				formRow.classList.add('woocommerce-invalid', 'woocommerce-invalid-required-field');

	// 				const error = document.createElement('p');
	// 				error.className = errorMsgClass;
	// 				error.textContent = 'Pasirinkite atsiėmimo būdą.';
	// 				formRow.appendChild(error);

	// 				return false;
	// 			} else {
	// 				formRow.classList.remove('woocommerce-invalid', 'woocommerce-invalid-required-field');
	// 				return true;
	// 			}
	// 		});
	// 	}

	// 	// Run initially on DOM load
	// 	const interval = setInterval(() => {
	// 		if (document.getElementById('montonio-shipping-pickup-point-dropdown')) {
	// 			clearInterval(interval);
	// 			setupMontonioValidation();
	// 		}

	// 		waited += CHECK_INTERVAL;
	// 		if (waited >= MAX_WAIT) clearInterval(interval);
	// 	}, CHECK_INTERVAL);

	// 	// Run again on WooCommerce checkout AJAX updates
	// 	jQuery(document.body).on('updated_checkout', function() {
	// 		// Re-run after delay to allow DOM replacement
	// 		setTimeout(() => {
	// 			setupMontonioValidation();
	// 		}, 300); // May need adjusting depending on speed
	// 	});
	// });
</script>





<?php do_action('woocommerce_after_checkout_form', $checkout); ?>