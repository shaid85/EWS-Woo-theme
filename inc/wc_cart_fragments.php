<?php

/**
 * Show cart contents / total Ajax
 */
add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');

function woocommerce_header_add_to_cart_fragment($fragments)
{
    global $woocommerce;

    ob_start();

?>
    <?php
    $count = WC()->cart->get_cart_contents_count();
    if ($count > 0) : ?>
        <span id="cartCount" class="count head"><?php echo $count; ?></span>
    <?php else: ?>
        <span id="cartCount" class="count d-none"><?php echo $count; ?></span>
    <?php endif; ?>

<?php
    $fragments['span.count'] = ob_get_clean();
    return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment2');

function woocommerce_header_add_to_cart_fragment2($fragments)
{
    global $woocommerce;

    ob_start();

?>
    <div class="woomini">
        <?php woocommerce_mini_cart(); ?>
    </div>

<?php
    $fragments['div.woomini'] = ob_get_clean();
    return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment3');
function woocommerce_header_add_to_cart_fragment3($fragments)
{
    global $woocommerce;

    ob_start();

?>
    <div class="woomini">
        <?php woocommerce_mini_cart(); ?>
    </div>

<?php
    $fragments['div.woomini'] = ob_get_clean();
    return $fragments;
}
