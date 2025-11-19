<?php
// ajax script
function custom_ajaxurl()
{
    echo '<script type="text/javascript">var ajaxurl = "' . admin_url('admin-ajax.php') . '";</script>';
}
add_action('wp_head', 'custom_ajaxurl');


add_action('wp_ajax_ajax_product_search', 'ajax_product_search');
add_action('wp_ajax_nopriv_ajax_product_search', 'ajax_product_search');

function ajax_product_search()
{
    $query = sanitize_text_field($_POST['query']);

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 4,
        's'              => $query
    );

    $search_query = new WP_Query($args);
?>
    <div class="search-results-list">

        <div class="offer mb-3 mb-xl-0">
            <h3 class="fs-6"><?php echo acf_esc_html(get_field('srb_title', 'option')); ?></h3>
            <?php echo do_shortcode('[wp_save_searches]');
            ?>
        </div>
        <div class="sp_wrap">
            <?php
            if ($search_query->have_posts()) {
                while ($search_query->have_posts()) {
                    $search_query->the_post();
                    global $product;
            ?>
                    <div class="single-product">
                        <!-- Product Image -->
                        <div class="product-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php echo woocommerce_get_product_thumbnail('medium'); ?>
                            </a>
                        </div>
                        <div class="right_side">
                            <!-- Product Brand (From Taxonomy) -->
                            <div class="product-brand text-14">
                                <?php
                                $brand_terms = get_the_terms($product->get_id(), 'product_brand'); // Change 'product_brand' to your brand taxonomy slug
                                if ($brand_terms && !is_wp_error($brand_terms)) {
                                    echo '<span class="brand-name">' . esc_html($brand_terms[0]->name) . '</span>';
                                }
                                ?>
                            </div>

                            <!-- Product Title -->
                            <h3 class="product-title"><a class="text-12" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

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
                        </div>


                    </div>
                <?php
                }
            } else {
                ?>
                <h5>Prekių nerasta</h5>
            <?php
            }
            ?>
        </div>
        <div class="s_viewall d-flex col-12">
            <button type="button" id="sr_submit" class="nobtn">Visi rezultatai <span class="arrow"></span></button>
        </div>
    </div>
    <?php

    wp_die();
    ?>

<?php
}

// Track search keywords
function track_wp_search_keywords()
{
    if (is_search() && isset($_GET['s'])) {
        $search_query = sanitize_text_field($_GET['s']);
        $searches = get_option('wp_recent_searches', []);

        if (!in_array($search_query, $searches)) {
            array_unshift($searches, $search_query); // Add new at top
            $searches = array_slice($searches, 0, 6); // Keep last 10 only
            update_option('wp_recent_searches', $searches);
        }
    }
}
add_action('template_redirect', 'track_wp_search_keywords');

// Display the Search Keywords List
function show_wp_recent_searches($max_item = 6)
{
    if (empty($max_item)) {
        $max_item = 6;
    }
    $searches = get_option('wp_recent_searches', []);
    // Limit to 4 items
    $limited_searches = array_slice($searches, 0, $max_item);

    if (!empty($searches)) {
        echo '<ul class="wp-recent-searches">';
        foreach ($limited_searches as $search) {
            echo '<li><a href="' . esc_url(home_url('/?s=' . urlencode($search))) . '&post_type=product">' . esc_html($search) . '</a></li>';
        }
        echo '</ul>';
    } else {
        echo '<ul class="wp-recent-searches">';
        echo '<li>Empty recent search.</li>';
        echo '</ul>';
    }
}
// register as a shortcode: wp_recent_searches
add_shortcode('wp_recent_searches', 'show_wp_recent_searches');

// Display save Search Keywords List
function show_wp_save_searches()
{ ?>
    <ul>
        <?php
        if (have_rows('offer_search_keyword', 'option')):

            while (have_rows('offer_search_keyword', 'option')) : the_row();

                $keyword_product = get_sub_field('keyword_product');
                // Convert to search query URL
                $search_url = home_url('/?s=' . urlencode($keyword_product) . '&post_type=product');
        ?>
                <li><a href="<?php echo $search_url; ?>"><?php echo acf_esc_html(get_sub_field('keyword_product')); ?></a></li>
        <?php
            endwhile;
        else :
        // Do something...
        endif; ?>
    </ul>
<?php
}
// register as a shortcode: wp_recent_searches
add_shortcode('wp_save_searches', 'show_wp_save_searches');

// Search bar
function custom_woocommerce_product_search_form()
{
    $form = '<form role="search" method="get" class="woocommerce-product-search" action="' . esc_url(home_url('/')) . '">
        <input type="search" class="search-field" placeholder="' . esc_attr__('Search products...', 'woocommerce') . '" value="' . get_search_query() . '" name="s" />
        <input type="hidden" name="post_type" value="product" />
        <button type="submit" class="search-submit">' . esc_html__('Search', 'woocommerce') . '</button>
    </form>';
    return $form;
}
add_shortcode('custom_wc_search', 'custom_woocommerce_product_search_form');
