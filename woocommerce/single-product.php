<?php

/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

get_header('shop'); ?>



<div class="container single_product_container ">
	<div class="cat_header text-left text-black p-40">
		<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action('woocommerce_before_main_content');

		// ===== util: get primary brand term (first) =====
		$brand_term = false;
		if (function_exists('wc_get_product_terms')) {
			$brand_terms = wc_get_product_terms(get_the_ID(), 'product_brand');
			if ($brand_terms) {
				$brand_term = $brand_terms[0];
			}
		}
		?>
	</div>
	<div class="single_product my-5 sp_wrapper">
		<div class="row gx-5">

			<!--  LEFT COLUMN : gallery  -->
			<div class="col-lg-8 mb-4">

				<?php
				$attachment_ids = $product->get_gallery_image_ids();
				array_unshift($attachment_ids, $product->get_image_id()); // main first 
				?>

				<!-- desktop grid -->
				<div class="d-none d-md-flex flex flex-wrap gap-2 pe-xl-4" id="desktop-gallery">
					<?php foreach ($attachment_ids as $id) : ?>
						<div class="product-image-wrapper mb-3">
							<?php echo wp_get_attachment_image($id, 'large', false, ['class' => 'img-fluid']); ?>
						</div>
					<?php endforeach; ?>
				</div>

				<!-- mobile Swiper slider -->
				<div class="d-block d-md-none">
					<div class="swiper myProdSwiper">
						<div class="swiper-wrapper">
							<?php foreach ($attachment_ids as $id) : ?>
								<div class="swiper-slide">
									<?php echo wp_get_attachment_image($id, 'large', false, ['class' => 'img-fluid']); ?>
								</div>
							<?php endforeach; ?>
						</div>
						<div class="swiper-pagination"></div>
					</div>
				</div>

			</div><!-- /col -->

			<!--  RIGHT COLUMN : product summary  -->
			<div class="col-lg-4">

				<?php while (have_posts()) : the_post();
					global $product; ?>

					<div class="brand_box d-flex align-items-center mb-4 justify-content-between">
						<?php if ($brand_term) : ?>
							<div class="brand_term">
								<?php
								$thumbnail_id = get_term_meta($brand_term->term_id, 'thumbnail_id', true);
								$image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';

								if ($image_url) :
									echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($brand_term->name) . '" />';
								else :
									echo esc_html($brand_term->name);
								endif;
								?>
							</div>
						<?php endif; ?>

						<div class="d-flex gap-2">
							<div class="icon-box d-none 8d-md-block">
								<a href="/compare-products/">
									<div class="icon-holder">
										<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/expand-icon.png" alt="icon" />
									</div>
								</a>
							</div>
							<div class="icon-box d-none d-md-block">
								<a href="/wishlist/" class="love-btn2 love-btn love_prd" data-id="<?php echo esc_attr($product->get_id()); ?>">
									<div class="icon-holder">
										<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/favorite2.png" class="default" alt="icon" />
										<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/favorite22.png" class="added" alt="icon" />
									</div>
								</a>
							</div>
						</div>

					</div>


					<h2 class="product_title mb-2"><?php the_title(); ?></h2>

					<div class="short-description "><?php the_excerpt(); ?></div>
					<?php // Display badge
					global $product;
					$percent = ews_get_discount_percent();
					?>
					<div class="badge_box pb-1">
						<span class="cs_badge top_badge bg_primary text-16">TOP</span>
						<?php if ($percent): ?>
							<span class="cs_badge discount_badge text-16">-<?php echo esc_html($percent); ?>%</span>
						<?php endif; ?>
					</div>

					<!-- Divider -->
					<hr class="divider my-4" />

					<!-- Size attribute -->
					<?php
					if ($product->is_type('simple')) {
						// 🔹 Simple product: just show buttons (no variations)
						$size_attr = $product->get_attribute('pa_dydis');
						if ($size_attr) {
							$sizes = explode(',', $size_attr);
					?>

							<?php if ($product->is_in_stock()) : ?>
								<div class="d-flex justify-content-between align-items-center mb-2">
									<p class="fw-semibold mb-2">Pasirinkite dydį:</p>
									<p class="d-none gray_text mb-0 text-14">Dydžių lentelė <img width="20" src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/what-right-icon.png" alt="info" /></p>
								</div>

								<div class="d-flex flex-wrap gap-2">
									<?php foreach ($sizes as $size) : $size = trim($size); ?>
										<button type="button" class="btn btn-outline-secondary size-button add_size" data-value="<?php echo esc_html($size); ?>"><?php echo esc_html($size); ?></button>
									<?php endforeach; ?>
								</div>

								<hr class="divider my-4" />
							<?php endif; ?>
							<script>
								document.addEventListener('DOMContentLoaded', function() {
									const buttons = document.querySelectorAll('.add_size');
									const sizeInput = document.getElementById('selected_size_input');

									buttons.forEach(button => {
										button.addEventListener('click', () => {
											sizeInput.value = button.dataset.value;

											// Visual feedback
											buttons.forEach(btn => btn.classList.remove('active'));
											button.classList.add('active');
										});
									});
								});
								document.addEventListener('DOMContentLoaded', function() {
									const form = document.getElementById('add-to-cart-form');
									const errorBox = document.getElementById('custom-error-message');

									form.addEventListener('submit', function(e) {
										const selectedSize = document.getElementById('selected_size_input').value;

										if (!selectedSize) {
											e.preventDefault();
											// Show a message to the user
											errorBox.textContent = "Pasirinkite dydį.";
											errorBox.style.display = 'block';
										} else {
											// Clear error if everything is valid
											errorBox.style.display = 'none';
										}
									});
								});
							</script>
						<?php
						}
					} else {
						// 🔹 Variable product: use variation logic for 'pa_avalynes-dydis'
						?>


					<?php

					}
					?>

					<?php
					global $product;
					if (has_term('tenisas', 'product_cat', $product->get_id())): ?>
						<?php if (get_field('prideti_stygas')): ?>
							<?php if ($product->is_in_stock()) : ?>
								<?php do_action('custom_before_add_to_cart_button');  ?>
							<?php endif; ?>
							<script>
								document.addEventListener('DOMContentLoaded', function() {
									const form = document.getElementById('add-to-cart-form');
									const errorBox = document.getElementById('custom-error-message2');

									form.addEventListener('submit', function(e) {
										const racketString = document.getElementById('racket_string_input').value;

										if (!racketString) {
											e.preventDefault();

											// Show a message to the user
											errorBox.textContent = "Pasirinkite raketės stygą.";
											errorBox.style.display = 'block';
										} else {
											// Clear error if everything is valid
											errorBox.style.display = 'none';
										}
									});
								});
							</script>
						<?php else: ?>
							<div class="default_string">
								<div class="inner_flex d-flex gap-2">
									<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/error.png" width="24" alt="icon">
									<aside>Gamyklinis stygų įtempimas.</aside>
								</div>
							</div>
						<?php endif; ?>

						<!-- Divider -->
						<hr class="divider my-4" />

					<?php endif; ?>

					<!-- price -->
					<div class="product-price mb-3">
						<?php if ($product->is_type('variable')) :
							$min = $product->get_variation_regular_price('min', true);
							$max = $product->get_variation_regular_price('max', true);
							$sale_min = $product->get_variation_sale_price('min', true);
							$sale_max = $product->get_variation_sale_price('max', true);

							// Check if any variation is on sale
							if ($sale_min < $min) {
								echo '<span class="sale-price">' . wc_price($sale_min) . ($sale_min !== $sale_max ? ' - ' . wc_price($sale_max) : '') . '</span>';
								echo '<span class="regular-price ms-2"><del>' . wc_price($min) . ($min !== $max ? ' - ' . wc_price($max) : '') . '</del></span>';
							} else {
								echo '<span class="sale-price">' . wc_price($min) . ($min !== $max ? ' - ' . wc_price($max) : '') . '</span>';
							}

						elseif ($product->is_on_sale()) : ?>
							<span class="sale-price"><?php echo wc_price($product->get_sale_price()); ?></span>
							<span class="regular-price ms-2"><del><?php echo wc_price($product->get_regular_price()); ?></del></span>
						<?php else : ?>
							<span class="sale-price"><?php echo wc_price($product->get_price()); ?></span>
						<?php endif; ?>
					</div>


					<!-- 🛑 Error message container (initially hidden) -->
					<div id="custom-error-message" style="color: red; margin-bottom: 1em; display: none;"></div>
					<div id="custom-error-message2" style="color: red; margin-bottom: 1em; display: none;"></div>

					<?php if ($product->is_type('simple')) : ?>
						<?php if ($product->is_in_stock()) : ?>
							<!-- qty + add‑to‑cart -->
							<form id="add-to-cart-form" class="cart d-flex gap-2 mb-3" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype="multipart/form-data">
								<!-- Hidden Fields for string added -->
								<input type="hidden" name="racket_string" id="racket_string_input" required>

								<!-- Hidden Fields for selected_size -->
								<input type="hidden" name="selected_size" id="selected_size_input" required>

								<?php
								woocommerce_quantity_input([
									'input_name'  => 'quantity',   // ✅ this is the key fix
									'input_value' => 1,
								]);
								?>
								<button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="single_add_to_cart_button button alt btn btn-primary">
									<?php echo esc_html($product->add_to_cart_text()); ?>
								</button>
							</form>
						<?php endif; ?>
					<?php elseif ($product->is_type('variable')) : ?>
						<?php if ($product->is_in_stock()) : ?>
							<!-- custom code here -->
							<div class="vr_product_data custom">
								<?php do_action('custom_single_product_summary');
								?>
							</div>
						<?php endif; ?>
						<script>
							document.addEventListener('DOMContentLoaded', function() {
								const select = document.querySelector('select[name="attribute_pa_avalynes-dydis88"]') ||
									document.querySelector('select[name="attribute_pa_dydis88"]');
								const variationForm = document.querySelector('form.variations_form');

								if (select && variationForm) {
									const wrapper = document.createElement('div');
									wrapper.classList.add('custom-size-buttons');

									Array.from(select.options).forEach(option => {
										if (option.value === '') return; // Skip placeholder

										const btn = document.createElement('button');
										btn.type = 'button';
										btn.className = 'btn btn-outline-secondary size-button';
										btn.textContent = option.text;
										btn.dataset.value = option.value;

										btn.addEventListener('click', function() {
											select.value = option.value;

											// Trigger WooCommerce's change + variation handling
											select.dispatchEvent(new Event('change', {
												bubbles: true
											}));
											variationForm.dispatchEvent(new Event('woocommerce_variation_has_changed'));
											jQuery(variationForm).trigger('check_variations');

											// Visual active class toggle
											wrapper.querySelectorAll('button').forEach(b => b.classList.remove('active'));
											btn.classList.add('active');
										});

										wrapper.appendChild(btn);
									});

									select.style.display = 'none';
									select.parentNode.insertBefore(wrapper, select);
								}
							});
						</script>
						<!-- Divider -->
						<hr class="divider my-4" />

					<?php endif; ?>

					<div class="woocommerce-notices-wrapper">
						<?php // wc_print_notices(); 
						?>
					</div>


					<!-- stock status -->
					<?php

					$status = $product->get_stock_status(); // instock | outofstock | onbackorder

					switch ($status) {
						case 'instock':
							echo '<p class="stock in-stock"><span class="circle_icon"></span>' . __('Turime sandelyje', 'woocommerce') . '</p>';
							break;

						case 'onbackorder':
							echo '<p class="stock on-backorder">' . __('Available on back-order', 'woocommerce') . '</p>';
							break;

						default: // outofstock
							echo '<p class="stock out-of-stock"><span class="circle_icon red"></span>' . __('Išparduota', 'woocommerce') . '</p>';
							break;
					}
					?>
					<p class="gray_text">Pristatymas: 3-4 d.d. </p>

					<!-- Divider -->
					<hr class="divider my-4" />

					<!-- Accordion -->
					<div class="faq_box">
						<div class="faq-item">
							<button class="faq-question fw-medium ps-0 py-1 border-0 ">Specifikacijos</button>
							<div class="faq-answer px-0 pb-0 border-0 ">
								<dl class="specs">
									<?php foreach ($product->get_attributes() as $attr) :
										if ($attr->is_taxonomy()) {
											$vals = wc_get_product_terms($product->get_id(), $attr->get_name(), ['fields' => 'names']);
											echo '<dt>' . wc_attribute_label($attr->get_name()) . '</dt><dd>' . implode(', ', $vals) . '</dd>';
										} else {
											echo '<dt>' . $attr->get_name() . '</dt><dd>' . $attr->get_options()[0] . '</dd>';
										}
									endforeach; ?>
								</dl>
							</div>
						</div>
						<!-- Add more FAQ items as needed -->
					</div>

					<!-- Divider -->
					<hr class="divider my-4" />

					<!-- Accordion -->
					<div class="faq_box">
						<div class="faq-item">
							<button class="faq-question fw-medium ps-0 py-1 border-0">Aprašymas</button>
							<div class="faq-answer px-0 pb-0 border-0">
								<div class="long-description pt-2 "><?php the_content(); ?></div>
							</div>
						</div>
						<!-- Add more FAQ items as needed -->
					</div>

					<!-- Divider -->
					<hr class="divider my-4" />

					<div class="icon_list ">
						<div class="info m-0 col-6 align-items-start">
							<div class="icon s2 align-items-start">
								<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/Delivery2.png" alt="icon-image">
							</div>
							<div class="text">
								<p class="gray_text text-12">Nemokamas pristatymas perkant nuo 99 EUR</p>
							</div>
						</div> <!--  end info -->
						<div class="info m-0 align-items-start pe-lg-5">
							<div class="icon s2 align-items-start">
								<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/Payment2.png" alt="icon-image">
							</div>
							<div class="text">
								<p class="gray_text text-12">Garantuotas saugus
									pirkimas</p>
							</div>
						</div> <!--  end info -->

					</div>
				<?php endwhile; // end loop 
				?>
			</div><!-- /col -->

		</div><!-- /row -->
	</div>
	<?php
	/**
	 * woocommerce_after_main_content hook.
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action('woocommerce_after_main_content');
	?>

	<?php
	/**
	 * woocommerce_sidebar hook.
	 *
	 * @hooked woocommerce_get_sidebar - 10
	 */
	// do_action('woocommerce_sidebar');
	?>

</div>
<!-- end container -->

<?php
$section_title = "Rekomenduojamos prekės";
$section_title2 = "Neseniai peržiūrėtos prekės";
$background_color = "#FAFAF5";
$background_color2 = "#fff";

$layout = 'single';
$index = '1';
$index2 = '2';

?>
<section class="popular_product  <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">
	<div class="container">
		<div class="head_box d-flex justify-content-between align-items-start ">
			<?php if ($section_title) : ?>
				<h2 class="ews_title mb-5">
					<?php echo esc_html($section_title); ?>
				</h2>
			<?php endif; ?>
			<a href="<?php echo site_url(); ?>/product" class="btn btn-primary bg-white text-black see_more mt-2">Daugiau</a>
		</div>
		<div class="swiper-container my-swiper sl-<?php echo $layout . '-' . $index; ?>">
			<div class="swiper-wrapper">
				<?php
				global $product;

				$related_ids = wc_get_related_products($product->get_id(), 15); // Get up to 15 related product IDs

				$args = array(
					'post_type'      => 'product',
					'posts_per_page' => 15,
					'post__in'       => $related_ids,
					'post__not_in'   => array($product->get_id()), // Exclude current product
					'orderby'        => 'post__in', // Preserve related product order
					'post_status'    => 'publish',
				);

				$loop = new WP_Query($args);

				if ($loop->have_posts()) :

					while ($loop->have_posts()) : $loop->the_post();
						global $product;
				?>
						<div class="swiper-slide ">
							<?php echo ewsdev_product_part_html($product, 54); ?>
							<!-- end single product -->
						</div>
					<?php
					endwhile;

				else :
					?>
					<div class="col">
						<h2 class="ews_title">
							Produktų nerasta.
						</h2>
					</div>
				<?php
				endif;

				wp_reset_postdata();
				?>
			</div><!-- swiper-wrapper -->
			<!-- Navigation -->
			<div class="container d-flex justify-content-between align-items-center mt-5 pt-4">
				<div class="swiper-progress-bar">
					<span class="swiper-progress"></span>
				</div>
				<div class="arrow">
					<div class="swiper-button-prev">
						<div class="icon-box">
							<div class="icon-holder">
								<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/arrow-left-black.png" alt="icon" />
							</div>
						</div>
					</div>
					<div class="swiper-button-next">
						<div class="icon-box">
							<div class="icon-holder">
								<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/arrow-right-black.png" alt="icon" />
							</div>
						</div>
					</div>

				</div>
			</div><!-- end container Navigation -->

		</div><!-- end swiper-container -->
	</div>

</section>
<script>
	// Product slider
	document.addEventListener("DOMContentLoaded", function() {
		document.querySelectorAll('.sl-<?php echo $layout . '-' . $index; ?>').forEach((slider) => {
			new Swiper(slider, {
				slidesPerView: 4, // Show 4 items at a time
				slidesPerGroup: 1, // Move 1 item per slide
				loop: true, // Disable looping
				autoplay: false, // No autoplay
				spaceBetween: 16,
				mousewheel: {
					forceToAxis: true,
					sensitivity: 1,
					releaseOnEdges: true, // Recommended for better UX
				},
				navigation: {
					nextEl: slider.querySelector('.sl-<?php echo $layout . '-' . $index; ?> .swiper-button-next'),
					prevEl: slider.querySelector('.sl-<?php echo $layout . '-' . $index; ?> .swiper-button-prev'),
				},
				breakpoints: {
					0: {
						slidesPerView: 1,
					},
					560: {
						slidesPerView: 2,
					},
					767: {
						slidesPerView: 3,
					},
					1024: {
						slidesPerView: 4,
					},
				},
				on: {
					slideChangeTransitionStart: function() {
						let progress = (this.realIndex + 1) / this.slides.length * 100;
						document.querySelector('.sl-<?php echo $layout . '-' . $index; ?> .swiper-progress').style.width = progress + "%";
					}
				}
			});
		});
	});
</script>

<!-- viewed_products -->
<section class="popular_product viewed_products <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color2; ?>;">
	<div class="container">
		<div class="head_box d-flex justify-content-between align-items-start ">
			<?php if ($section_title) : ?>
				<h2 class="ews_title mb-5">
					<?php echo esc_html($section_title2); ?>
				</h2>
			<?php endif; ?>
			<a href="<?php echo site_url(); ?>/product" class="btn btn-primary bg-white text-black see_more mt-2">Daugiau</a>
		</div>
		<div class="swiper-container my-swiper sl-<?php echo $layout . '-' . $index2; ?>">
			<div class="swiper-wrapper">
				<?php
				// Build $args from Viewed Products
				$viewed_products = ! empty($_COOKIE['woocommerce_recently_viewed'])
					? array_map('absint', explode(',', $_COOKIE['woocommerce_recently_viewed']))
					: [];

				$viewed_products = array_reverse($viewed_products); // Most recent first
				// print_r($viewed_products);

				if (!empty($viewed_products)) {
					$args = [
						'post_type'      => 'product',
						'post__in'       => $viewed_products,
						'orderby'        => 'post__in', // Maintain order
						'posts_per_page' => 8, // Limit as needed
					];
				} else {
					$args = [
						'post_type' => 'product',
						'post__in'  => [0], // No results fallback
						'post_status'    => 'publish',
					];
				}

				$recent_query = new WP_Query($args);

				if ($recent_query->have_posts()) :

					while ($recent_query->have_posts()) : $recent_query->the_post();
						global $product;
				?>
						<div class="swiper-slide ">
							<?php echo ewsdev_product_part_html($product, 54); ?>
							<!-- end single product -->
						</div>
					<?php
					endwhile;

				else :
					?>
					<div class="col">
						<h4 class="ews_title">
							Produktų nerasta.
						</h4>
					</div>
				<?php
				endif;

				wp_reset_postdata();
				?>
			</div><!-- swiper-wrapper -->
			<!-- Navigation -->
			<div class="container d-flex justify-content-between align-items-center mt-5 pt-4">
				<div class="swiper-progress-bar">
					<span class="swiper-progress"></span>
				</div>
				<div class="arrow">
					<div class="swiper-button-prev">
						<div class="icon-box">
							<div class="icon-holder">
								<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/arrow-left-black.png" alt="icon" />
							</div>
						</div>
					</div>
					<div class="swiper-button-next">
						<div class="icon-box">
							<div class="icon-holder">
								<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/arrow-right-black.png" alt="icon" />
							</div>
						</div>
					</div>

				</div>
			</div><!-- end container Navigation -->

		</div><!-- end swiper-container -->
	</div>

</section>
<script>
	// Product slider
	document.addEventListener("DOMContentLoaded", function() {
		document.querySelectorAll('.sl-<?php echo $layout . '-' . $index2; ?>').forEach((slider) => {
			new Swiper(slider, {
				slidesPerView: 4, // Show 4 items at a time
				slidesPerGroup: 1, // Move 1 item per slide
				loop: true, // Disable looping
				autoplay: false, // No autoplay
				spaceBetween: 16,
				mousewheel: {
					forceToAxis: true,
					sensitivity: 1,
					releaseOnEdges: true, // Recommended for better UX
				},
				navigation: {
					nextEl: slider.querySelector('.sl-<?php echo $layout . '-' . $index2; ?> .swiper-button-next'),
					prevEl: slider.querySelector('.sl-<?php echo $layout . '-' . $index2; ?> .swiper-button-prev'),
				},
				breakpoints: {
					0: {
						slidesPerView: 1,
					},
					560: {
						slidesPerView: 2,
					},
					767: {
						slidesPerView: 3,
					},
					1024: {
						slidesPerView: 4,
					},
				},
				on: {
					slideChangeTransitionStart: function() {
						let progress = (this.realIndex + 1) / this.slides.length * 100;
						document.querySelector('.sl-<?php echo $layout . '-' . $index2; ?> .swiper-progress').style.width = progress + "%";
					}
				}
			});
		});
	});
</script>
<style>
	@media (min-width: 1200px) {
		.row.gx-5 .col-lg-4 {
			width: 35.333333%;
			margin-left: -2%;
		}
	}
</style>

<?php
$background_color = "#F8F9FA";
?>

<section class="iconbox_section py-5 s2 in_page" style="background-color:<?php echo $background_color; ?>;">
	<div class="container">

		<div class="icon_list s2">
			<div class="info">
				<div class="icon">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/list-Grazinimas.png" alt="icon-image" />
				</div>
				<div class="text">
					<h3>Nemokamas grąžinimas</h3>
					<p>Visoje Lietuvoje</p>
				</div>
			</div> <!--  end info -->
			<div class="info">
				<div class="icon">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/llist-Saugus apmokejimas.png" alt="icon-image" />
				</div>
				<div class="text">
					<h3>Saugus atsiskaitymas</h3>
					<p>SLL mokėjimų apsauga</p>
				</div>
			</div> <!--  end info -->
			<div class="info">
				<div class="icon">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/list-Delivery.png" alt="icon-image" />
				</div>
				<div class="text">
					<h3>Nemokamas pristatymas</h3>
					<p>Perkant nuo 99 EUR</p>
				</div>
			</div> <!--  end info -->
			<div class="info">
				<div class="icon">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/list-Stygavimas.png" alt="icon-image" />
				</div>
				<div class="text">
					<h3>Individualus stygavimas</h3>
					<p>Maksimaliam našumui</p>
				</div>
			</div> <!--  end info -->
		</div>
	</div>
</section>

<div class="popup_string">
	<div class="str_inner position-relative">
		<span class="close_str close_btn"></span>
		<div class="container-fluid">
			<div class="row products">
				<?php
				$args = array(
					'post_type' => 'product',
					'posts_per_page' => -1,
					'product_cat' => 'stygos-raketei',
					'orderby' => 'date',
					'order' => 'DESC',
				);
				$loop = new WP_Query($args);

				if ($loop->have_posts()) {

					while ($loop->have_posts()) : $loop->the_post();
				?>
						<div class="col-lg-6 col-xl-4">
							<a href="#" class="open-popup" data-product-id="<?php echo get_the_ID(); ?>">
								<div class="string_box single-product d-flex align-items-center item" id="product_id_<?php echo esc_attr($product->get_id()); ?>">
									<!-- Product Image -->
									<div class="product-image image mb-0">
										<?php // Display badge
										global $product;
										$image_id = $product->get_image_id(); // Get the image ID
										$image_url = wp_get_attachment_image_url($image_id, 'medium'); // Get image URL with desired size

										$percent = ews_get_discount_percent($product);

										?>


										<?php echo woocommerce_get_product_thumbnail('medium'); ?>

									</div>
									<div class="p_details">
										<!-- Product Brand (From Taxonomy) -->
										<div class="product-brand mb-1">
											<?php
											if ($product) {
												$brand_terms = get_the_terms($product->get_id(), 'product_brand'); // Change 'product_brand' to your brand taxonomy slug
												if ($brand_terms && !is_wp_error($brand_terms)) {
													echo '<span class="brand-name">' . esc_html($brand_terms[0]->name) . '</span>';
												} else {
													echo 'nėra gamintojo';
												}
											}
											?>
										</div>
										<!-- Product Title -->
										<h3 class="product-title mb-1">
											<div class="hover_show">
												<?php the_title(); ?>
											</div>
											<div class="default_show">
												<?php
												$product_title = get_the_title();
												// Trim the title to 50 characters and add "..." if it's too long
												$trimmed_title = mb_strimwidth($product_title, 0, 42, '');
												echo $trimmed_title;
												?>
											</div>

										</h3>
										<!-- Product Price -->
										<div class="product-price mb-0">
											<?php if ($product->is_type('variable')) :
												$min = $product->get_variation_regular_price('min', true);
												$max = $product->get_variation_regular_price('max', true);
												$sale_min = $product->get_variation_sale_price('min', true);
												$sale_max = $product->get_variation_sale_price('max', true);

												// Check if any variation is on sale
												if ($sale_min < $min) {
													echo '<span class="sale-price">' . wc_price($sale_min) . ($sale_min !== $sale_max ? ' - ' . wc_price($sale_max) : '') . '</span>';
													echo '<span class="regular-price ms-2"><del>' . wc_price($min) . ($min !== $max ? ' - ' . wc_price($max) : '') . '</del></span>';
												} else {
													echo '<span class="sale-price">' . wc_price($min) . ($min !== $max ? ' - ' . wc_price($max) : '') . '</span>';
												}

											elseif ($product->is_on_sale()) : ?>
												<span class="sale-price"><?php echo wc_price($product->get_sale_price()); ?></span>
												<span class="regular-price ms-2"><del><?php echo wc_price($product->get_regular_price()); ?></del></span>
											<?php else : ?>
												<span class="sale-price"><?php echo wc_price($product->get_price()); ?></span>
											<?php endif; ?>
										</div>
									</div>
								</div><!-- end single-product -->
							</a>
						</div><!-- end col-md-4 -->
				<?php
					endwhile;
					echo '</ul>';
				} else {
					echo __('Šioje kategorijoje nerasta jokių produktų');
				}
				wp_reset_postdata();

				?>
			</div><!-- end row -->
		</div><!-- end container -->
	</div><!-- end str_inner -->

	<div id="product-popup" style="display: none;">
		<div class="popup-content">
			<h3 class="mb-3">Stygų įtempimas</h3>
			<span class="close-popup close_btn"></span>
			<div id="popup-details">
				<!-- Content will be loaded via JS -->
			</div>
		</div>
	</div>

</div><!-- end popup_string -->
<script>
	jQuery(document).ready(function($) {
		$('.open-popup').on('click', function(e) {
			e.preventDefault();
			const productId = $(this).data('product-id');

			// AJAX to load product details into popup
			$.ajax({
				url: '<?php echo admin_url("admin-ajax.php"); ?>',
				type: 'POST',
				data: {
					action: 'load_product_popup',
					product_id: productId
				},
				success: function(response) {
					$('#popup-details').html(response);
					$('#product-popup').fadeIn();

					// ✅ Call this to bind button logic to dynamically added content
					initAdjustButtons();

					// ✅ Now initialize the JS for the popup
					initPopupAddToCartForm();
				}
			});
		});

		$('.close-popup, #product-popup').on('click', function(e) {
			if (e.target !== this) return;
			$('#product-popup').fadeOut();
		});

		$('.close_str').on('click', function(e) {
			if (e.target !== this) return;
			$('.popup_string').fadeOut();
		});
	});
</script>

<script>
	function initAdjustButtons() {
		const buttons = document.querySelectorAll('.adjust_btn');
		const stringRange = document.querySelector('.string_range');

		if (!buttons.length || !stringRange) return;

		buttons.forEach(button => {
			button.addEventListener('click', function() {
				buttons.forEach(btn => btn.classList.remove('active'));
				this.classList.add('active');

				const value = this.dataset.value;

				if (value === 'No') {
					stringRange.style.display = 'block';
				} else {
					stringRange.style.display = 'none';
				}
			});
		});

		// Set initial state based on active button
		const defaultSelected = document.querySelector('.adjust_btn.active');
		if (defaultSelected?.dataset.value === 'No') {
			stringRange.style.display = 'block';
		} else {
			stringRange.style.display = 'none';
		}
	}
</script>


<style>
	span.close_str.close_btn {
		top: 15px;
		right: 15px;
	}

	.popup_string {
		display: none;
		background-color: rgba(0, 0, 0, 0.5);
		position: fixed;
		width: 100%;
		overflow-y: auto;
		height: 100%;
		padding: 30px 30px;
		top: 0;
		z-index: 1000;
		min-height: 100dvh;
	}

	.admin-bar .popup_string {
		top: 32px;
	}

	.str_inner {
		background-color: #fff;
		border-radius: 10px;
		padding: 40px 20px;
		min-height: calc(100dvh - 60px);
	}

	.admin-bar .str_inner {
		min-height: calc(100dvh - 92px);
	}

	.string_box {
		gap: 20px;
		border: 1px solid var(--bor--color-border2, #dfe1e3);
		border-radius: 8px;
		margin-bottom: 25px;
	}

	.popup_string a {
		color: #000 !important;
	}

	.string_box .image {
		width: 100px;
		padding: 10px 8px;
		border: 0;
		background-color: #F5F6F7;
		border-radius: 0;
	}

	/* single popup */
	#product-popup {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background: rgba(0, 0, 0, 0.7);
		display: flex;
		justify-content: center;
		align-items: center;
		z-index: 9999;
		/* max-height: 100%; */
	}

	.popup-content {
		background: #fff;
		padding: 20px;
		max-width: 1140px;
		width: 100%;
		position: relative;
		border-radius: 10px;
	}

	.close-popup {
		position: absolute;
		top: 15px;
		right: 15px;
		font-size: 20px;

	}

	.string_range {
		display: none;
	}

	#popup-details .product_image {
		background-color: #F5F6F7;
		border-radius: 8px;
		padding: 20px;
	}

	#popup-details .product_image img {
		max-height: 400px;
	}

	#popup-details h2.product_title {
		position: relative;
		font-size: 24px;
	}

	#popup-details .product-price {
		font-size: 24px;
	}

	#popup-details span.sale-price bdi {
		font-weight: 500;
	}

	#popup-details span.regular-price {
		font-size: 18px;
	}

	#popup-details span.regular-price bdi {
		font-weight: 400;
		font-size: 18px;
	}

	#popup-details button.popup_add_to_cart_button.button {
		background-color: #000;
		border: 0;
		font-weight: 400;
		border-radius: 5px;
		padding: 16px 25px;
		width: 100%;
	}
</style>

<script>
	function initPopupAddToCartForm() {
		const form = document.getElementById('popup-add-to-cart-form');

		if (!form) return;

		form.addEventListener('submit', function(e) {
			e.preventDefault();

			const formData = new FormData(form);

			const data = {
				action: 'popup_add_to_cart',
				product_id: formData.get('product_id'),
				quantity: formData.get('quantity'),
				main_tension: formData.get('main_tension'),
				cross_tension: formData.get('cross_tension'),
			};

			fetch(wc_add_to_cart_params.ajax_url, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					body: new URLSearchParams(data)
				})
				.then(res => res.json())
				.then(response => {
					if (response.success) {
						// alert('Product added to cart!');

						// ✅ Close popup (optional)
						document.querySelector('.popup_string')?.remove();

						// ✅ Update WooCommerce cart fragments (icon, counter, mini-cart)
						jQuery(document.body).trigger('wc_fragment_refresh');
					} else {
						alert('Error: ' + response.data);
					}
				})
				.catch(err => {
					console.error(err);
					alert('Something went wrong.');
				});
		});
	}
</script>
<?php

get_footer('shop');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
