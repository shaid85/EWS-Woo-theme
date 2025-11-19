<?php
/* ── product like button ────────────────────────── */

/* ── 1. Enqueue + Pass Data to JS ───────────────── */
add_action('wp_enqueue_scripts', function () {

    wp_enqueue_script(
        'product-love',
        get_stylesheet_directory_uri() . '/assets/js/product-love.js',
        ['jquery'],
        '1.0',
        true
    );

    $user_loves = [];

    if (is_user_logged_in()) {
        $raw_loves = get_user_meta(get_current_user_id(), '_loved_products', true);
        $user_loves = is_array($raw_loves) ? array_filter(array_map('absint', $raw_loves)) : [];
    }

    wp_localize_script('product-love', 'loveData', [
        'ajax'        => admin_url('admin-ajax.php'),
        'nonce'       => wp_create_nonce('love_nonce'),
        'loved'       => $user_loves, // Array of product IDs
        'isLoggedIn'  => is_user_logged_in(),
        'total'       => count($user_loves),
    ]);
});


/* ── 2. Ajax: Toggle Love for Logged-in Users ───── */
add_action('wp_ajax_toggle_love',        'toggle_love');
add_action('wp_ajax_nopriv_toggle_love', 'toggle_love'); // Optional: guest fallback

function toggle_love()
{
    // Uncomment this if using nonce in JS
    // check_ajax_referer('love_nonce', 'nonce');

    $id = absint($_POST['id'] ?? 0);
    if (! $id) {
        wp_send_json_error('invalid');
    }

    // Guest users can optionally be handled differently
    if (! is_user_logged_in()) {
        wp_send_json_error('guest'); // Or manage localStorage on frontend
    }

    $user_id = get_current_user_id();
    $raw_loves = (array) get_user_meta($user_id, '_loved_products', true);
    $loves = array_filter(array_map('absint', $raw_loves)); // Clean the list

    if (in_array($id, $loves, true)) {
        $loves = array_diff($loves, [$id]);
        $state = 'removed';
        $count = max(0, (int) get_post_meta($id, '_love_count', true) - 1);
    } else {
        $loves[] = $id;
        $state = 'added';
        $count = (int) get_post_meta($id, '_love_count', true) + 1;
    }

    update_user_meta($user_id, '_loved_products', $loves);
    update_post_meta($id, '_love_count', $count);

    wp_send_json_success([
        'state' => $state,
        'count' => $count
    ]);
}
