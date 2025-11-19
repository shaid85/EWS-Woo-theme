<?php
// Register admin menu
add_action('admin_menu', function () {
    add_submenu_page(
        'woocommerce',
        'Attribute Order',
        'Attribute Order',
        'manage_woocommerce',
        'attribute-order',
        'my_attribute_order_admin_page'
    );
});

// Admin page HTML
function my_attribute_order_admin_page()
{
    if (!current_user_can('manage_woocommerce')) return;

    // Handle saving via AJAX
?>
    <div class="wrap">
        <h1>Reorder Product Attributes</h1>
        <p>Drag and drop the attributes below to set display order.</p>
        <ul id="attribute-sort-list">
            <?php
            $saved_order = get_option('my_attribute_sort_order', []);
            $all_attrs = wc_get_attribute_taxonomies();

            // Sort attributes using saved order
            usort($all_attrs, function ($a, $b) use ($saved_order) {
                $a_index = array_search($a->attribute_name, $saved_order);
                $b_index = array_search($b->attribute_name, $saved_order);
                return ($a_index === false ? PHP_INT_MAX : $a_index) - ($b_index === false ? PHP_INT_MAX : $b_index);
            });

            foreach ($all_attrs as $attr) {
                echo '<li class="attribute-item" data-name="' . esc_attr($attr->attribute_name) . '">' . esc_html($attr->attribute_label) . '</li>';
            }
            ?>
        </ul>
        <button id="save-attribute-order" class="button button-primary">Save Order</button>
        <p id="save-status"></p>
    </div>

    <style>
        #attribute-sort-list {
            list-style: none;
            margin: 20px 0;
            padding: 0;
            max-width: 400px;
        }

        .attribute-item {
            background: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 5px;
            cursor: move;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const list = document.getElementById('attribute-sort-list');
            new Sortable(list, {
                animation: 150
            });

            document.getElementById('save-attribute-order').addEventListener('click', function() {
                const items = document.querySelectorAll('.attribute-item');
                const order = Array.from(items).map(item => item.dataset.name);

                fetch(ajaxurl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            action: 'save_attribute_order',
                            order: JSON.stringify(order),
                            _ajax_nonce: '<?php echo wp_create_nonce('save_attribute_order_nonce'); ?>'
                        })
                    })
                    .then(res => res.text())
                    .then(result => {
                        document.getElementById('save-status').innerText = result;
                    });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<?php
}

// Handle AJAX save
add_action('wp_ajax_save_attribute_order', function () {
    check_ajax_referer('save_attribute_order_nonce');

    $order = json_decode(stripslashes($_POST['order']), true);
    if (is_array($order)) {
        update_option('my_attribute_sort_order', $order);
        wp_send_json_success('Attribute order saved.');
    }

    wp_send_json_error('Invalid order data.');
});
