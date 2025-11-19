                        <?php
                        $current_cat = get_queried_object();
                        if ($current_cat->slug == 'apranga'):

                            // Get the term object by slug
                            $parent_term = get_term_by('slug', 'apranga', 'product_cat');

                            if ($parent_term && !is_wp_error($parent_term)):

                                $parent_id = $parent_term->term_id;
                        ?>
                                <h2 class="fil-head open"><?php _e('Lytis', 'ewsdev'); ?></h2>
                                <div class="fil-body open">
                                    <?php
                                    $taxonomy = 'product_cat';
                                    $args = array(
                                        'taxonomy'   => $taxonomy,
                                        'hide_empty' => 0,
                                        'parent'     => $parent_id
                                    );

                                    $all_categories = get_terms($args);

                                    $allowed_slugs = array('berniukai', 'mergaites', 'moterys-apranga', 'vyrai');

                                    echo '<ul>';
                                    foreach ($all_categories as $category) {
                                        // Only include categories in the allowed list
                                        if (!in_array($category->slug, $allowed_slugs)) {
                                            continue;
                                        }

                                        echo '<li>' . esc_html($category->name);

                                        // Get subcategories of the current category
                                        $sub_args = array(
                                            'taxonomy'   => $taxonomy,
                                            'hide_empty' => 0,
                                            'parent'     => $category->term_id
                                        );
                                        $sub_categories = get_terms($sub_args);

                                        if (!empty($sub_categories)) {
                                            echo '<ul>';
                                            foreach ($sub_categories as $sub_category) {
                                                echo '<li>' . esc_html($sub_category->name) . '</li>';
                                            }
                                            echo '</ul>';
                                        }

                                        echo '</li>';
                                    }
                                    echo '</ul>';
                                    ?>
                                </div>
                        <?php
                            endif;
                        endif;
                        ?>