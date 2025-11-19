<?php
add_action('rest_api_init', function () {
    register_rest_route('compare/v1', '/data', [
        'methods' => 'GET',
        'callback' => function ($request) {
            $ids = explode(',', $request['ids']);
            $products = [];

            foreach ($ids as $id) {
                $product = wc_get_product($id);
                if (!$product) continue;

                $products[] = [
                    'id' => $id,
                    'title' => $product->get_name(),
                    'price' => $product->get_price_html(),
                    'image' => wp_get_attachment_image_url($product->get_image_id(), 'medium'),
                    'description' => wp_strip_all_tags($product->get_description()),
                    'short_desc'  => wp_strip_all_tags($product->get_short_description()),
                ];
            }

            return $products;
        },
        'permission_callback' => '__return_true'
    ]);
});
