<?php

/**
 * Reusable Product part
 */

function ewsdev_product_part_html($product, $title_lenght = 52)
{
    ob_start();
?>
    <div class="single-product item" id="product_id_<?php echo esc_attr($product->get_id()); ?>">
        <!-- Product Image -->
        <div class="product-image">
            <?php // Display badge
            global $product;
            $image_id = $product->get_image_id(); // Get the image ID
            $image_url = wp_get_attachment_image_url($image_id, 'medium'); // Get image URL with desired size

            $percent = ews_get_discount_percent($product);

            ?>
            <div class="badge_box ">
                <?php
                if (!$product->is_in_stock()) {
                    echo '<span class="cs_badge sold-out-badge text-14">Išparduota</span>';
                }
                ?>

                <?php if ($product && wc_get_product()->is_featured()) :
                ?>
                    <span class="cs_badge top_badge bg_primary text-14">TOP</span>
                <?php endif; ?>
                <?php if ($percent): ?>
                    <span class="cs_badge discount_badge text-14">-<?php echo esc_html($percent); ?>%</span>
                <?php endif; ?>
            </div>

            <a href="<?php the_permalink(); ?>">
                <?php echo woocommerce_get_product_thumbnail('medium'); ?>
            </a>
            <button class="love-btn love_prd nobtn " data-id="<?php echo esc_attr($product->get_id()); ?>"></button>

        </div>

        <!-- Product Brand (From Taxonomy) -->
        <div class="product-brand">
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
        <h3 class="product-title">
            <a href="<?php the_permalink(); ?>">
                <div class="hover_show">
                    <?php the_title(); ?>
                </div>
                <div class="default_show">
                    <?php
                    $product_title = get_the_title();
                    // Trim the title to 50 characters and add "..." if it's too long
                    $trimmed_title = mb_strimwidth($product_title, 0, $title_lenght, '');
                    echo $trimmed_title;
                    ?>
                </div>
            </a>
        </h3>

        <!-- Product Price -->
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

        <!-- Add to Cart Button -->
        <div class="add-to-cart">
            <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>

        <!-- Compare Checkbox -->
        <label class="compare-checkbox custom-checkbox">
            <input type="checkbox" class="compare-product" data-product-id="<?php echo esc_attr($product->get_id()); ?>" data-title="<?php echo get_the_title(); ?>" data-imageurl="<?php echo $image_url; ?>">
            <span class="checkmark"></span>
            Palyginti
        </label>
    </div>

<?php
    return ob_get_clean();
}
