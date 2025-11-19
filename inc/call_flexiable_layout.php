<?php
// ACF Flexiable content - create file with layout
function flexLayout($attr)
{
    $flex_field = $attr['name'] ?? false;
    if (!$flex_field) return;

    if (have_rows($flex_field)) :
        while (have_rows($flex_field)) : the_row();

            $layout = get_row_layout();
            $layout_file = get_stylesheet_directory() . "/page-templates/flexible-sections/{$layout}.php";

            // Auto-create file if missing
            if (!file_exists($layout_file)) {
                $default_content = "<section id=\"{$layout}\">\n    <!-- Add HTML code for '{$layout}' here -->\n</section>";
                file_put_contents($layout_file, $default_content);
            }

            // Load the layout
            include $layout_file;

        endwhile;
    else :
        get_template_part('content', 'noflex');
    endif;
}
add_shortcode('flexlayout', 'flexLayout');
