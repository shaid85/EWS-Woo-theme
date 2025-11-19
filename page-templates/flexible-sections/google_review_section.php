<?php
$section_title = get_sub_field('section_title');
$background_color = get_sub_field('background_color');
$add_custom_class = get_sub_field('add_custom_class');

$pgn_shortcode = get_sub_field('form_shortcode_here');
?>
<section id="google_review_section" class="two_col_box <?php echo acf_esc_html($add_custom_class); ?>" style="background-color:<?php echo $background_color; ?>;">

    <!-- Add HTML code for 'google_review_section' here -->
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <?php
                $left_box = get_sub_field('left_content_box');
                ?>
                <div class="content_box">
                    <!-- <script defer async src='https://cdn.trustindex.io/loader.js?bfaed9451189058a4276189c76a'></script>
                    <style>
                        a.ti-header.ti-header-grid.source-Google {
                            padding-left: 0 !important;
                        }
                    </style> -->
                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/reviews-badge.png" width="245" alt="image" class="google_badge mb-4" />
                    <?php if ($left_box['title']): ?>
                        <h2><?php echo esc_html($left_box['title']); ?></h2>
                    <?php endif; ?>
                    <?php if ($left_box['description']): ?>
                        <p><?php echo esc_html($left_box['description']); ?></p>
                    <?php endif; ?>
                    <?php if ($left_box['button']): ?>
                        <a class="btn black_btn mt-4 mb-3" href="<?php echo esc_url($left_box['button']['url']); ?>"><?php echo esc_html($left_box['button']['title']); ?></a>
                    <?php endif; ?>

                </div>
            </div>
            <div class="col-md-6">
                <div class="review_slider_wrap">
                    <div class="swiper-container review_slider">
                        <div class="swiper-wrapper">
                            <?php echo do_shortcode($pgn_shortcode); ?>

                        </div>
                        <div class="slider-controls">
                            <button id="prevBtn">↑ Up</button>
                            <button id="nextBtn">↓ Down</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<script>
    setTimeout(function() {
        // try vertical slide for google review 
        const slider = document.getElementsByClassName('ti-reviews-container-wrapper')[0];
        const slides = document.querySelectorAll('.ti-review-item');
        let index = 0;
        const slideHeight = 250;
        const maxIndex = slides.length - 2; // show 2 slides in 500px
        const autoplaySpeed = 3000; // 3 seconds

        // Manual controls
        // document.getElementById('nextBtn').addEventListener('click', nextSlide);
        // document.getElementById('prevBtn').addEventListener('click', prevSlide);

        function nextSlide() {
            if (index < maxIndex) {
                index++;
            } else {
                index = 0; // Reset to top
            }
            updateSlide();
        }

        function prevSlide() {
            if (index > 0) {
                index--;
            }
            updateSlide();
        }

        function updateSlide() {
            slider.style.transform = `translateY(-${index * (slideHeight / 3)}px)`;
        }

        // Autoplay
        setInterval(nextSlide, autoplaySpeed);

    }, 10000); // Small delay to ensure CF7 loads    
</script>