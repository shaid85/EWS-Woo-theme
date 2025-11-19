<?php
/*
Template Name: Compare page
*/

/**
 * Compare Products template
 * URL pattern: /compare-products/?ids=131,133,135
 */
defined('ABSPATH') || exit;
get_header(); ?>

<div id="content_area" class="site-content">
    <div id="primary" class="content-area">
        <main id="main" class="site-main compare_page">
            <div class="container ">

                <?php
                $product_ids = isset($_GET['ids']) ? array_map('absint', explode(',', $_GET['ids'])) : [];
                $product_ids = array_slice($product_ids, 0, 4); // limit to max 4

                if (!$product_ids) {
                    echo '<div class="py-5">' . __('Palyginimui nepasirinkta jokių produktų.', 'textdomain') . '</div>';
                }
                $products = [];
                $all_attributes = [];
                foreach ($product_ids as $product_id) {
                    $product = wc_get_product($product_id);
                    if ($product) {
                        $products[] = $product;
                        $attributes = $product->get_attributes();

                        foreach ($attributes as $key => $attr) {
                            $slug = is_a($attr, 'WC_Product_Attribute') ? $attr->get_name() : $key;
                            $all_attributes[$slug] = wc_attribute_label($slug); // collect for global list
                        }
                    }
                }
                ?>

                <?php if (!empty($products) && !empty($all_attributes)) : ?>
                    <div class="compare-grid d-flex flex-column pb-5 mb-5">

                        <div class="row compare-row compare_box">
                            <div class="col-lg-3 pt-5 compare_side">
                                <h3 class="mb-2">
                                    Prekių palyginimas
                                </h3>
                                <div class="d-flex mb-3 d-none">
                                    <label class="custom-checkbox">
                                        <input type="checkbox" class="compare-cell" id="compare-differences">
                                        <span class="checkmark"></span>
                                        Rodyti tik lyginamų produktų skirtumus
                                    </label>
                                </div>
                                <div class="compare-actions d-flex justify-content-between">
                                    <a href="<?php echo site_url(); ?>/compare-products/" id="clear-compare" class="px-0">
                                        Ištrinti viską
                                    </a>
                                </div>
                                <style>
                                    a#clear-compare {
                                        color: #777;
                                        padding: 1px 15px;
                                        display: inline-block;
                                        margin-top: 10px;
                                    }
                                </style>
                            </div>
                            <div class="col-lg-9 pt-5 ">
                                <?php

                                /* 2. Query products in the exact order provided */
                                $query = new WP_Query([
                                    'post_type'           => 'product',
                                    'post_status'         => 'publish',
                                    'post__in'            => $product_ids,
                                    'orderby'             => 'post__in',  // preserves the original order 131,133,135 …
                                    'posts_per_page'      => 4,
                                    'ignore_sticky_posts' => true,
                                ]);

                                if (! $query->have_posts()) {
                                    echo '<p>' . __('No valid products found.', 'textdomain') . '</p>';
                                }

                                /* 3. Loop through products */
                                echo '<div class="row row-cols-md-' . count($product_ids) . '">';
                                $index = 0;
                                while ($query->have_posts()) : $query->the_post();
                                    $product = wc_get_product(get_the_ID()); ?>

                                    <div class="col-md-3 product_head position-relative <?php echo 'col_' . $index; ?>">
                                        <button class="remove_it" data-id="<?php echo $product->get_id(); ?>"></button>
                                        <?php echo ewsdev_product_part_html($product); ?>
                                        <!-- end single product -->
                                    </div>

                                <?php $index++;
                                endwhile;
                                echo '</div>'; // .row
                                wp_reset_postdata();
                                ?>

                            </div>
                        </div>
                        <!-- Price Row -->
                        <div class="row compare-row d-flex flex-row attr_data2">
                            <div class="col-lg-3 attribute-cell com_head">Kaina</div>
                            <?php $index = 0; ?>
                            <div class="col-lg-9">
                                <div class="row">
                                    <?php foreach ($products as $product): ?>
                                        <div class="col-md-3 attribute-cell p_value text-center <?php echo 'col_' . $index; ?>">
                                            <?php echo $product->get_price_html(); ?>
                                        </div>
                                        <?php $index++; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Attribute Rows -->
                        <?php foreach ($all_attributes as $slug => $label): ?>
                            <div class="row compare-row d-flex flex-row attr_data">
                                <div class="col-lg-3 attribute-cell com_head"><?php echo esc_html($label); ?></div>
                                <?php $index = 0; ?>
                                <div class="col-lg-9">
                                    <div class="row">
                                        <?php foreach ($products as $product): ?>
                                            <div class="col-md-3 attribute-cell p_value text-center <?php echo 'col_' . $index; ?>">
                                                <?php
                                                $value = $product->get_attribute($slug);
                                                echo $value ? esc_html($value) : '<em>-</em>';
                                                ?>
                                            </div>
                                            <?php $index++; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                <?php endif; ?>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const compareList = JSON.parse(localStorage.getItem('compareList')) || [];
                        console.log('Loaded compareList:', compareList);

                        document.querySelectorAll('.remove_it').forEach(button => {
                            button.addEventListener('click', function() {
                                const productId = this.dataset.id;
                                console.log('Remove clicked for ID:', productId);

                                // Filter out the product by comparing its "id" key
                                const updatedList = compareList.filter(item => String(item.id) !== String(productId));
                                console.log('Updated list:', updatedList);

                                // Save back to localStorage
                                localStorage.setItem('compareList', JSON.stringify(updatedList));
                                console.log('Saved updated list to localStorage');

                                // Prepare the URL with IDs only
                                const idList = updatedList.map(item => item.id);
                                console.log('ID list for URL:', idList);

                                if (idList.length > 0) {
                                    window.location.href = `/compare-products/?ids=${idList.join(',')}`;
                                } else {
                                    window.location.href = '/';
                                }
                            });
                        });
                    });
                    document.addEventListener('DOMContentLoaded', function() {
                        const toggle = document.getElementById('compare-differences');

                        function updateCompareDifferences() {
                            const rows = document.querySelectorAll('.attr_data');

                            rows.forEach(row => {
                                const cells = Array.from(row.querySelectorAll('.attribute-cell:not(.com_head)'));
                                const values = cells.map(cell => cell.textContent.trim().toLowerCase());
                                console.log(cells);
                                console.log(values);
                                const allSame = values.every(val => val === values[0]);
                                console.log('checed same: ', allSame);

                                if (toggle.checked && allSame) {
                                    console.log('inside if: ', toggle.checked);
                                    row.style.display = 'none';
                                    row.classList.remove('d-flex');
                                } else {
                                    console.log('inside else: ', toggle.checked);
                                    row.style.display = '';
                                    row.classList.add('d-flex');
                                }
                            });
                        }

                        // Initial check
                        updateCompareDifferences();

                        // Update on checkbox toggle
                        toggle.addEventListener('change', updateCompareDifferences);
                    });
                </script>

                <style>
                    button.love-btn.love_prd.nobtn {
                        display: none;
                    }
                </style>

                <script>
                    // Define the columns you want to enable group-hover on
                    const columns = ['col_0', 'col_1', 'col_2', 'col_3'];

                    columns.forEach(colClass => {
                        const colElements = document.querySelectorAll(`.${colClass}`);

                        colElements.forEach(elem => {
                            elem.addEventListener('mouseenter', () => {
                                colElements.forEach(el => el.classList.add('hovered'));
                            });

                            elem.addEventListener('mouseleave', () => {
                                colElements.forEach(el => el.classList.remove('hovered'));
                            });
                        });
                    });
                </script>

            </div> <!-- End container -->

            <?php
            $background_color = "#F8F9FA";
            ?>

            <section class="iconbox_section py-5 <?php echo acf_esc_html($add_custom_class); ?> <?php echo $info_box_style; ?>" style="background-color:<?php echo $background_color; ?>;">
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

        </main>
    </div>
</div>
<?php get_footer(); ?>