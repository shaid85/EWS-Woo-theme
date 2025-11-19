<?php

function get_discounted_product_ids($min_discount = 30)
{
    $matching_ids = [];

    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    ];

    $query = new WP_Query($args);

    while ($query->have_posts()) {
        $query->the_post();
        $product = wc_get_product(get_the_ID());

        if (!$product instanceof WC_Product) continue;

        // Check simple
        if ($product->is_type('simple')) {
            $reg = (float) $product->get_regular_price();
            $sale = (float) $product->get_sale_price();

            if ($reg > 0 && $sale > 0) {
                $calc = 100 - ($sale / $reg * 100);
                if ($calc >= $min_discount) {
                    $matching_ids[] = $product->get_id();
                }
            }
        }

        // Check variable
        if ($product->is_type('variable')) {
            foreach ($product->get_available_variations() as $variation_data) {
                $variation = wc_get_product($variation_data['variation_id']);
                $reg = (float) $variation->get_regular_price();
                $sale = (float) $variation->get_sale_price();

                if ($reg > 0 && $sale > 0) {
                    $calc = 100 - ($sale / $reg * 100);
                    if ($calc >= $min_discount) {
                        $matching_ids[] = $product->get_id();
                        break;
                    }
                }
            }
        }
    }

    wp_reset_postdata();
    return array_unique($matching_ids);
}

add_filter('woocommerce_cart_totals_coupon_html', 'custom_coupon_html_output', 10, 2);

add_filter('woocommerce_cart_totals_coupon_html', 'custom_coupon_html_output', 10, 2);

function custom_coupon_html_output($coupon_html, $coupon)
{
    // Define list of possible [Remove] translations you want to replace
    $to_replace = [
        '[Remove]',     // English
        '[Pašalinti]',  // Lithuanian (for example)
        // Add more if needed
    ];

    // Replace all with 'Remove' (no brackets)
    $coupon_html = str_replace($to_replace, 'Pašalinti <span></span>', $coupon_html);

    return $coupon_html;
}



function custom_woocommerce_register_shortcode()
{
    ob_start();

    if (is_user_logged_in()) {
        wp_redirect(wc_get_page_permalink('myaccount'));
        exit;
    }

?>

    <div id="customer_login" class="login_box woocommerce mb-5">
        <div class="col-md-6 mx-auto login_box py-4">

            <h3><?php esc_html_e('Registracija', 'woocommerce'); ?></h3>
            <p>Susikurkite paskyrą čia</p>

            <!-- Divider -->
            <hr class="divider my-4" />

            <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>

                <?php do_action('woocommerce_register_form_start'); ?>

                <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label class="d-none" for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" placeholder="vartotojo vardas" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required />
                    </p>

                <?php endif; ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <input type="text" class="woocommerce-Input input-text" name="first_name" placeholder="Įveskite vardą" id="first_name" value="<?php if (!empty($_POST['first_name'])) echo esc_attr($_POST['first_name']); ?>" required />
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <input type="text" class="woocommerce-Input input-text" name="last_name" id="last_name" placeholder="Įveskite pavardę" value="<?php if (!empty($_POST['last_name'])) echo esc_attr($_POST['last_name']); ?>" required />
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label class="d-none" for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" placeholder="Jūsų elektroninis paštas" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required />
                </p>

                <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label class="d-none" for="reg_password"><?php esc_html_e('Įveskite slaptažodį', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" placeholder="Įveskite slaptažodį" id="reg_password" autocomplete="new-password" required />
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password2" placeholder="Pakartokite slaptažodį" id="reg_password2" autocomplete="new-password" required />
                    </p>
                    <div class="d-flex mb-3">
                        <label class="custom-checkbox">
                            <input type="checkbox" class="compare-cell" id="compare-differences" name="agree_email">
                            <span class="checkmark"></span>
                            Prenumeruoti naujienlaiškį?
                        </label>
                    </div>
                <?php else : ?>
                    <p><?php esc_html_e('A link to set a new password will be sent to your email address.', 'woocommerce'); ?></p>
                <?php endif; ?>

                <?php // do_action('woocommerce_register_form');
                ?>

                <!-- Divider -->
                <hr class="divider my-4" />

                <p class="woocommerce-form-row form-row mb-3">
                    <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                    <button type="submit" class="mt-0 woocommerce-Button woocommerce-button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
                </p>
                <p>Jau turite paskyrą? <a href="<?php echo site_url(); ?>/my-account/">Prisijunkite</a></p>

                <?php do_action('woocommerce_register_form_end'); ?>

            </form>
            <?php do_action('woocommerce_before_customer_login_form'); ?>
            <div id="omnisend-embedded-v2-680b75ea6a35d55a0034f846"></div>
        </div>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode('custom_woo_register', 'custom_woocommerce_register_shortcode');

// password_confirmation
add_action('woocommerce_register_post', 'validate_strong_password_rules', 20, 3);
function validate_strong_password_rules($username, $email, $errors)
{
    if (!empty($_POST['password']) && !empty($_POST['password2']) && ($_POST['password'] !== $_POST['password2'])) {
        $errors->add('password_mismatch', __('Passwords do not match.', 'woocommerce'));
    }

    if (!empty($_POST['password'])) {
        $password = $_POST['password'];

        // Minimum length
        if (strlen($password) < 8) {
            $errors->add('password_too_short', __('Password must be at least 8 characters long.', 'woocommerce'));
        }

        // Must contain at least one letter
        if (!preg_match('/[a-zA-Z]/', $password)) {
            $errors->add('password_no_letter', __('Password must include at least one letter.', 'woocommerce'));
        }

        // Must contain at least one number
        if (!preg_match('/\d/', $password)) {
            $errors->add('password_no_number', __('Password must include at least one number.', 'woocommerce'));
        }

        // Must contain at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            $errors->add('password_no_upper', __('Password must include at least one uppercase letter.', 'woocommerce'));
        }
    }

    return $errors;
}

// Validate first and last name
add_action('woocommerce_register_post', 'validate_name_fields', 10, 3);
function validate_name_fields($username, $email, $validation_errors)
{
    if (empty($_POST['first_name']) || empty($_POST['last_name'])) {
        $validation_errors->add('required_name', __('First and Last name are required!', 'woocommerce'));
    }
    return $validation_errors;
}
// Save first and last name
add_action('woocommerce_created_customer', 'save_name_fields');
function save_name_fields($customer_id)
{
    if (isset($_POST['first_name'])) {
        update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['first_name']));
    }
    if (isset($_POST['last_name'])) {
        update_user_meta($customer_id, 'last_name', sanitize_text_field($_POST['last_name']));
    }
}
add_action('wp_footer', 'add_repeat_password_check_script');
function add_repeat_password_check_script()
{
    if (is_page('registruotis') && !is_user_logged_in()) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form.register');
                if (!form) return;

                form.addEventListener('submit', function(e) {
                    const password = document.getElementById('reg_password').value;
                    const confirm = document.getElementById('reg_password2').value;
                    if (password !== confirm) {
                        e.preventDefault();
                        alert('Passwords do not match.');
                    }
                });
            });
        </script>
<?php endif;
}


/**
 * Trim a string to a specific length, optionally preserving words.
 *
 * @param string $text      The original text to trim.
 * @param int    $length    The max length to trim to.
 * @param string $suffix    The string to append (e.g. '...').
 * @param bool   $preserve_words Whether to avoid cutting words in half.
 * @return string
 */
function trim_product_title($text, $length = 30, $suffix = '...', $preserve_words = true)
{
    $text = strip_tags($text); // Remove HTML tags
    $text = trim($text);       // Remove extra spaces

    if (mb_strlen($text) <= $length) {
        return $text;
    }

    if ($preserve_words) {
        $trimmed = mb_substr($text, 0, $length);
        $last_space = mb_strrpos($trimmed, ' ');
        if ($last_space !== false) {
            $trimmed = mb_substr($trimmed, 0, $last_space);
        }
    } else {
        $trimmed = mb_substr($text, 0, $length);
    }

    return $trimmed . $suffix;
}
