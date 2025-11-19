                        <!-- Brands -->
                        <div class="col-md-12 mb-3">
                            <!-- Accordion -->
                            <div class="filter_box">
                                <div class="accordion-group">
                                    <h2 class="fil-head open"><?php _e('Gamintojas', 'ewsdev'); ?></h2>
                                    <div class="fil-body open">
                                        <?php
                                        // $brands = get_terms(['taxonomy' => 'product_brand', 'hide_empty' => true]);
                                        /*  Only brands in the current archive  */
                                        $brands = my_get_attr_terms_with_local_counts('product_brand');

                                        if (!is_wp_error($brands) && !empty($brands)) :
                                            foreach ($brands as $item) {
                                                $brand = $item['term'];
                                                $count = $item['count'];
                                                echo '<div class="form-check">
                  <input class="form-check-input" type="checkbox" name="brands[]" value="' . esc_attr($brand->slug) . '" id="brand_' . $brand->term_id . '">
                  <label class="form-check-label" for="brand_' . $brand->term_id . '">' . esc_html($brand->name) . ' (' . $count . ')</label>
                </div>';
                                            }
                                        ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div><!-- End filter_box -->
                        </div>