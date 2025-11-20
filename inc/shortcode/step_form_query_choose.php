<?php

/**
 * [racket_finder] – multi‑step form → GET /shop/ with filters
 */
add_shortcode('racket_finder', function () {

    $shop_url = wc_get_page_permalink('shop'); // usually /shop/

    ob_start(); ?>


    <div class="container gobackbtn pt-3 pt-md-5 pb-0">
        <div class="goback mb-0 mb-md-3 pt-3">
            <button class="prev"><img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/button-left-icon.png" alt=""> Grįžti</button>
        </div>
    </div>

    <form id="racket-wizard" action="<?php echo esc_url($shop_url); ?>" method="get" novalidate>
        <div class="d-flex8 align-content-center full-height ">
            <div class="step_form_content p-50">
                <div class="container">
                    <div class="inner_box ">

                        <!-- ── Progress bar ───────────────────────────────── -->
                        <ul id="wizard-progress" class="simple-stepbar mb-5">
                            <li class="active completed">1</li>
                            <li>2</li>
                            <li>3</li>
                            <li>4</li>
                            <li>5</li>
                            <!-- <li>6</li> -->
                        </ul>

                        <!-- Step 1 – Gender -->
                        <section class="step step-1 mb-md-5">
                            <div class="step_content mb-4 mb-md-5">
                                <h2>Pasirinkite sporto šaką</h2>
                                <!-- <p>Visos raketės yra Unisex tipo, tačiau vienas iš svarbiausių kriterijų renkantis teniso raketę yra jos svoris. Vyrams reikalinga sunkesnė raketė, moterims lengvesnė, manevringesnė raketė. Mažesnio svorio raketė leidžia lengviau valdyti raketę žaidybinėse situacijose.</p> -->
                            </div>

                            <div class="py-3">
                                <input type="radio" class="btn-check" id="option1" name="filter_cat" value="Tenisas" required>
                                <label class="btn arrow_btn no_arrow me-3" for="option1">Tenisas</label>
                                <input type="radio" class="btn-check" id="option2" name="filter_cat" value="Padelis" required>
                                <label class="btn arrow_btn no_arrow" for="option2">Padelis</label>
                            </div>

                            <div class="button_box">
                                <!-- <a class="skip arrow_btn no_arrow btn mt-5 me-2">Praleisti</a> -->
                                <button type="button" class="next arrow_btn btn black_btn mt-5">Sekantis<span></span></button>
                            </div>
                        </section>

                        <!-- Step 2 – Age -->
                        <section class="step step-2 mb-md-5" hidden>
                            <div class="step_content mb-4 mb-md-5">
                                <h2>Pasirinkite lytį</h2>
                                <!-- <p>Visos raketės yra Unisex tipo, tačiau vienas iš svarbiausių kriterijų renkantis teniso raketę yra jos svoris. Vyrams reikalinga sunkesnė raketė, moterims lengvesnė, manevringesnė raketė. Mažesnio svorio raketė leidžia lengviau valdyti raketę žaidybinėse situacijose.</p> -->
                            </div>

                            <div class="py-3">
                                <input type="radio" class="btn-check" id="option3" name="pa_lytis" value="vyras" required>
                                <label class="btn arrow_btn no_arrow me-3" for="option3">Vyras</label>
                                <input type="radio" class="btn-check" id="option4" name="pa_lytis" value="moteris" required>
                                <label class="btn arrow_btn no_arrow" for="option4">Moteris</label>
                            </div>

                            <div class="button_box">
                                <!-- <a class="skip arrow_btn no_arrow btn mt-5 me-2">Praleisti</a> -->
                                <button type="button" class="next arrow_btn btn black_btn mt-5">Sekantis<span></span></button>
                            </div>
                        </section>

                        <!-- Step 3 – Player level -->
                        <section class="step step-3 mb-md-5" hidden>
                            <div class="step_content mb-4 mb-md-5">
                                <h2>Amžius</h2>
                                <!-- <p>Renkantis teniso raketę labai svarbu atkreipti dėmesį į žaidėjo amžių. Vaikams ir paaugliams reikalingos trumpesnės, lengvesnės ir mažesnės raketės. Vyresnio amžiaus žaidėjams siūlome rinktis lengvesnę raketę su didesne galva.</p> -->
                            </div>

                            <div class="py-3">
                                <input type="radio" class="btn-check" id="option6" name="pa_amzius" value="suaugusiems" required>
                                <label class="btn arrow_btn no_arrow me-3" for="option6">Suagusiems</label>
                                <input type="radio" class="btn-check" id="option5" name="pa_amzius" value="jaunimui" required>
                                <label class="btn arrow_btn no_arrow " for="option5">Jaunimui</label>
                            </div>


                            <div class="button_box">
                                <!-- <a class="skip arrow_btn no_arrow btn mt-5 me-2">Praleisti</a> -->
                                <button type="button" class="next arrow_btn btn black_btn mt-5">Sekantis<span></span></button>
                            </div>
                        </section>

                        <!-- Step 4 – Expectation -->
                        <section class="step step-4 mb-md-5" hidden>
                            <h2 class="mb-4 mb-md-5">Žaidėjo lygis</h2>
                            <div class="d-flex radio_box py-3">
                                <input type="radio" class="btn-check" id="option7" name="pa_lygis" value="pradedantysis" required>
                                <label class="btn arrow_btn no_arrow" for="option7">
                                    <h4 class="mb-3">Pradedantysis</h4>
                                    <ul>
                                        <li>Pradedate žaisti tenisą arba žaidžiate retai;</li>
                                        <li>Mokotės pagrindinių smūgių (padavimas, forhendas; bekhendas);</li>
                                        <li>Daugiausia dėmesio skiriate kamuoliuko pataikymui į aikštelę ir smūgių technikai;</li>
                                        <li>Domitės rakete, kuri suteiktų maksimalę kontrolę ir lengvumą mokantis.</li>
                                    </ul>
                                </label>
                                <input type="radio" class="btn-check" id="option8" name="pa_lygis" value="vidutinis" required>
                                <label class="btn arrow_btn no_arrow" for="option8">
                                    <h4 class="mb-3">Vidutinis</h4>
                                    <ul>
                                        <li>Reguliariai žaidžiate tenisą ir esate susipažinę su pagrindiniais smūgiais;</li>
                                        <li>Jaučiatės patogiai atliekant pagrindinius smūgius;</li>
                                        <li>Gebate atlikti nuoseklius smūgius, tačiau dar tobulinate savo techniką ir smūgių galingumą;</li>
                                        <li>Ieškote raketės, kuri padėtų pagerinti tiek kontrolę, tiek smūgio jėgą.</li>
                                    </ul>
                                </label>
                                <input type="radio" class="btn-check" id="option9" name="pa_lygis" value="pazenges" required>
                                <label class="btn arrow_btn no_arrow" for="option9">
                                    <h4 class="mb-3">Pažengęs</h4>
                                    <ul>
                                        <li>Turite didelę teniso patirtį ir puikiai valdote visus smūgius;</li>
                                        <li>Reguliariai dalyvaujate mačuose ar turnyruose;</li>
                                        <li>Gebate žaisti įvairiomis smūgių technikom ir strategijomis;</li>
                                        <li>Ieškote raketės, kuri atitiktų jūsų aukštą žaidimo lygį ir suteiktų pranašumą jėgos ar kontrolės srityje.</li>
                                    </ul>
                                </label>
                            </div>

                            <div class="button_box">
                                <!-- <a class="skip arrow_btn no_arrow btn mt-5 me-2">Praleisti</a> -->
                                <button type="button" class="next arrow_btn btn black_btn mt-5">Sekantis<span></span></button>
                            </div>
                        </section>

                        <!-- Step 5 – Expectation -->
                        <section class="step step-5 mb-md-5" hidden>
                            <h2 class="mb-4 mb-md-5">Ko tikimasi iš raketės</h2>
                            <div class="d-flex radio_box py-3">
                                <input type="checkbox" class="btn-check" id="option10" name="pa_ko-tikimasi-is-raketes[]" value="komfortas" required>
                                <label class="btn arrow_btn no_arrow" for="option10">
                                    <h4 class="mb-3">Komfortas</h4>
                                    <ul>
                                        <li>Raketė, kuri sumažina vibraciją ir smūgio poveikį rankai, padeda išvengti nuovargio ir traumų.</li>
                                        <li>Svarbu žaidėjams, kurie ieško malonesnio žaidimo pojūčio.</li>
                                        <li>Dažnai turi specialias vibraciją slopinančias technologijas.</li>
                                    </ul>
                                </label>
                                <input type="checkbox" class="btn-check" id="option11" name="pa_ko-tikimasi-is-raketes[]" value="kontrole" required>
                                <label class="btn arrow_btn no_arrow" for="option11">
                                    <h4 class="mb-3">Kontrolė</h4>
                                    <ul>
                                        <li>Raketė, kuri padeda tiksliai nukreipti kamuoliuką ir išlaikyti jį aikštelėje.</li>
                                        <li>Svarbu žaidėjams, kurie nori tiksliai kontroliuoti kamuoliuko trajektoriją ir gilumą.</li>
                                        <li>Dažnai pasižymi mažesne galva ir lankstesniu rėmu.</li>
                                    </ul>
                                </label>
                                <input type="checkbox" class="btn-check" id="option12" name="pa_ko-tikimasi-is-raketes[]" value="jega" required>
                                <label class="btn arrow_btn no_arrow" for="option12">
                                    <h4 class="mb-3">Jėga</h4>
                                    <ul>
                                        <li>Raketė, kuri leidžia atlikti galingus smūgius su dideliu greičiu.</li>
                                        <li>Svarbu žaidėjams, kurie nori dominuoti žaidime agresyviais smūgiais.</li>
                                        <li>Dažnai pasižymi didesne galva, standesniu rėmu ir didesniu svoriu.</li>
                                    </ul>
                                </label>
                                <input type="checkbox" class="btn-check" id="option13" name="pa_ko-tikimasi-is-raketes[]" value="manevringumas" required>
                                <label class="btn arrow_btn no_arrow" for="option13">
                                    <h4 class="mb-3">Manevringumas</h4>
                                    <p>Šios raketės skirtos žaidėjams norintiems jausti stabilumą, lengviau kontroliuoti kamuolį aikštelėje. Renkantis raketę su padidinta kontrolę svarbu atkreipti dėmesį jog, šios raketės turi mažiau galios smūgiuojant, dažnai mažesnę raketės galvą.</p>
                                </label>
                            </div>

                            <div class="button_box">
                                <!-- <a class="skip arrow_btn no_arrow btn mt-5 me-2">Praleisti</a> -->
                                <button type="button" class="next arrow_btn btn black_btn mt-5">Sekantis<span></span></button>
                            </div>
                        </section>


                    </div><!-- End inner_box -->
                </div><!-- End container -->

                <!-- Step 6 – Email -->
                <section class="step step-6 mb-md-5 pb-md-5 w-100" hidden>
                    <div class="container-fluid p-0 p-md-0">
                        <div class="row align-items-center gx-0">
                            <div class="col-md-8">
                                <div class="px-3 px-md-0 mt-5 mt-md-0">
                                    <div class="inner6 ps-md-5 ps-0">
                                        <h2 class="mb-4">Paskutinis žingsnis iki tobulos raketės!</h2>
                                        <div class="step_email mb-2">
                                            <input type="email" id="step_email" name="user_email" placeholder="Įveskite el. paštą" class="mb-2">
                                        </div>
                                        <label class="custom-checkbox pb-4">
                                            <input type="checkbox" class="compare-product" id="confirm_mail_sub" required>
                                            <span class="checkmark"></span>
                                            Sutinku su <strong class="ms-1"><a href="/privatumo-politika/" class="text-black">privatumo politikos taisyklėmis.</a></strong>
                                        </label>

                                        <button class="finish arrow_btn btn black_btn mt-3" type="submit">Rodyti rezultatus<span></span></button>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4 position-relative">
                                <div class="stepimg2 myn-100 d-none d-md-block">
                                    <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/step-end-min.png" alt="image" class="step_image" />
                                </div>

                            </div>
                            <div class="p-0 pt-5">
                                <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/step-6.png" alt="image" class="mt-5 p-0 d-block d-md-none" />
                            </div>

                        </div>

                    </div>

                </section>
            </div><!-- End container -->



            </section>

        </div><!-- End step_form_content -->
        </div><!-- End flex -->

    </form>
    <!-- Omnisend embedded form placed outside the main form -->
    <div id="omnisend-embedded-v2-680b75ea6a35d55a0034f846"></div>


    <style>
        .page-raketes-vedlys .header_wrap,
        footer {
            display: none !important;
        }

        section.statter_sec.step_page.position-relative {
            background-color: #FAFBFD;
            height: 100vh;
        }

        .simple-stepbar {
            display: flex;
            justify-content: flex-start;
            gap: 65px;
            margin: 30px 0;
            padding: 0;
            list-style: none;
        }

        section #wizard-progress.simple-stepbar li {
            position: relative;
            width: 40px;
            height: 39px;
            background: #EEF0F2;
            color: #7F8182;
            font-weight: bold;
            font-size: 16px;
            border-radius: 50%;
            text-align: center;
            line-height: 38px;
            font-weight: 400;
            padding: 0;
        }

        section #wizard-progress.simple-stepbar li::before {
            position: absolute;
            content: "";
            display: block;
            width: 65px;
            height: 2px;
            background-color: #EEF0F2;
            top: 18px;
            left: 40px;
        }

        section #wizard-progress.simple-stepbar li:last-child::before {
            display: none;
        }

        section #wizard-progress.simple-stepbar li.active {
            background: #DCF134;
            color: #000;
        }

        section #wizard-progress.simple-stepbar li.completed,
        section #wizard-progress.simple-stepbar li.completed::before {
            background: #DCF134;
            color: #000;
        }

        .d-flex.align-content-center.full-height {
            min-height: 90vh;
        }

        .page-raketes-vedlys section.statter_sec.p-50 {
            background-color: #FAFBFD;
        }

        img.step_image {
            height: 100%;
            width: auto;
        }

        .stepimg {
            position: absolute;
            top: 0;
            right: 0;
            max-width: 40%;
            height: 100%;
        }

        .loading-spinner {
            display: block;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

<?php return ob_get_clean();
});


// AJAX handler in PHP - This receives the data and sends the email.

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('racket-wizard', get_stylesheet_directory_uri() . '/assets/js/racket-wizard.js', ['jquery'], null, true);
    wp_localize_script('racket-wizard', 'racketWizard', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'baseUrl' => esc_url(site_url('/kategorija/raketes/')),
    ]);
});

add_action('wp_ajax_send_raketes_vedlys_email', 'send_raketes_vedlys_email');
add_action('wp_ajax_nopriv_send_raketes_vedlys_email', 'send_raketes_vedlys_email');

function send_raketes_vedlys_email()
{

    $email    = sanitize_email($_POST['email']);
    $lytis    = sanitize_text_field($_POST['lytis']);
    $amzius   = sanitize_text_field($_POST['amzius']);
    $lygis    = sanitize_text_field($_POST['lygis']);
    $tikimasi = sanitize_text_field($_POST['tikimasi']);
    $category = sanitize_text_field($_POST['category']);

    $to = "shaid85@gmail.com"; //info@tennis-land.lt shaid85@gmail.com
    $subject = "Raketės vedlio forma";

    $message = "
Nauja Raketės vedlio forma:

Kliento el. paštas: $email

Kategorija: $category
Lytis: $lytis
Amžius: $amzius
Lygis: $lygis

Ko tikimasi iš raketės:
$tikimasi
    ";

    wp_mail($to, $subject, $message);

    wp_send_json_success("Email sent");
}
