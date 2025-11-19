(function ($) {
    // Initial run on page load
    // $(document).ready(function () {
    //     runAjaxFilter();
    // });
    let ajaxRunCount = 0; // 🔹 counter

    function runAjaxFilter(page = 1) {
        ajaxRunCount++;
        console.log("runAjaxFilter called:", ajaxRunCount, "time(s)");

        let form = $('#ajax-filter-form')[0];
        let formData = new FormData(form);

        // Add the action parameter to the form data
        formData.append('action', 'filter_products');  // This should match the action hooked to admin-ajax
        formData.append('paged', page); // send page number to PHP

        //  🔸 keep archive context
        if (filterContext.taxonomy && filterContext.term_id) {
            formData.append('taxonomy', filterContext.taxonomy);
            formData.append('term_id', filterContext.term_id);
            // Only append discount if it's set and not empty
            if (filterContext.discount) {
                formData.append('discount', filterContext.discount);
            }
            // Only append search if it's set and not empty
            if (filterContext.search) {
                formData.append('search', filterContext.search);
            }

        }

        // Make the AJAX request
        $.ajax({
            url: filter_ajax.ajax_url,  // This uses the localized variable 'filter_ajax'
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $loader.removeClass("hidden");
                $productList.addClass('hidden-on-load');
                setTimeout(() => {
                    // now AJAX will continue after skeleton shows
                    $("#filter-loader")[0].offsetHeight;
                }, 50);

            },
            success: function (response) {
                // Update the page with the filtered products and count
                $('#product-results').html(response.html);
                $('#results-count').html(response.count);
                $('#priceRangeVal').text($('#price_range').val());
                $('#pagination-wrapper').html(response.pagination);// update pagination
                displayActiveFilters(formData);
                // ✅ Scroll to top of results
                $('html, body').animate({
                    scrollTop: $('#Top').offset().top - 200
                }, 400);
            },
            complete: function () {
                $loader.addClass("hidden");
                $productList.removeClass('hidden-on-load');
            },
            error: function (xhr, status, error) {
                console.log("AJAX Error: ", error);
            }
        });
        // End runAjaxFilter
    }

    function displayActiveFilters(formData) {
        let output = '';

        const labelMap = {
            'min_price': 'Min Price',
            'max_price': 'Max Price',
            'brands': 'Brand',
            'collections': 'Collection',
            'dydis': 'Size',
            'weight': 'Weight',
            'sizes': 'Size',
            'sub_cat': 'Sporto šaka',
            'aksesuaro-tipas': 'Aksesuaro tipas',
            // Add more as needed
        };

        let filter_length = 0;
        for (let pair of formData.entries()) {
            let key = pair[0];
            // console.log(key, '--', pair[1]);

            // Skip 'action' and 'sortby'
            if (!pair[1] || key === 'action' || key === 'sort_by' || key === 'paged' || key === 'taxonomy' || key === 'term_id' || key === 'discount' || key === 'min_price' || key === 'max_price' || key === 'search' || key === 'param_cat') continue;

            // Strip brackets from array keys like sizes[]
            key = key.replace(/\[\]$/, '');

            const label = labelMap[key] || key;

            // Trim the value to max 18 characters
            let fillName = pair[1];
            if (fillName.length > 16) {
                fillName = fillName.slice(0, 16) + '...'; // 15 + '...' = 18 total
            }
            // ${label}: ${fillName}
            output += `
                <span class="badge bg-secondary me-1 mb-1">
                    <aside>${label}:</aside> ${fillName}
                    <a href="#" class="text-white text-decoration-none ms-2 remove-filter" data-key="${pair[0]}">×</a>
                </span>
            `;
            filter_length += 1;
        }

        $('#active-filters').html(output);
        $('.nr-head span').html(filter_length);
        // End displayActiveFilters
    }


    $(document).on('click', '.remove-filter', function (e) {
        e.preventDefault();
        const key = $(this).data('key');

        // Reset inputs with the given key
        $(`#ajax-filter-form [name="${key}"]`).each(function () {
            if ($(this).is(':checkbox') || $(this).is(':radio')) {
                $(this).prop('checked', false).trigger('change');//added trigger here
            } else if ($(this).is('select')) {
                $(this).val('').trigger('change');
            } else {
                $(this).val('');
            }
        });

        // Trigger filter update
        // $('#ajax-filter-form').trigger('change');

        // Reset price slider if applicable
        if (key === 'min_price' || key === 'max_price') {
            const slider = document.getElementById('price-range-slider');
            if (slider && slider.noUiSlider) {
                slider.noUiSlider.set([10, 400]);
            }
        }

        runAjaxFilter();
        // end remove-filter
    });



    $('#clear-filters').on('click', function (e) {
        e.preventDefault();

        $('#ajax-filter-form')[0].reset();
        $('#ajax-filter-form input[type="checkbox"], #ajax-filter-form input[type="radio"]').prop('checked', false);
        $('#ajax-filter-form select').val('');

        // Reset price range slider
        const slider = document.getElementById('price-range-slider');
        if (slider && slider.noUiSlider) {
            slider.noUiSlider.set([10, 400]);
        }

        // Reset discount range slider
        // const Dslider = document.getElementById('discount-slider');
        // if (Dslider && Dslider.noUiSlider) {
        //     slider.noUiSlider.set([0]);
        // }

        runAjaxFilter();

        // hide .badge using jQuery
        $('#active-filters .badge').hide();
        // end clear-filters
    });

    $(document).on('click', '.top_nav li a,.custom-menu li a,.sub_catbox a,.cat_box a', function () {
        // Remove price cookies
        Cookies.remove('min_price');
        Cookies.remove('max_price');
        Cookies.remove('product_filter_data');
    });

    // Price range slider 
    document.addEventListener('DOMContentLoaded', function () {
        const priceSlider = document.getElementById('price-range-slider');

        if (priceSlider) {
            // categoryPriceRange.min
            const minInput = document.getElementById('min_price');
            const maxInput = document.getElementById('max_price');

            const minValue = Number(minInput.value)
            const maxValue = Number(maxInput.value)

            noUiSlider.create(priceSlider, {
                start: [categoryPriceRange.min, categoryPriceRange.max], // initial values
                connect: true,
                range: {
                    min: categoryPriceRange.min,
                    max: categoryPriceRange.max
                },
                step: 1,
                format: {
                    to: value => Math.round(value),
                    from: value => Number(value)
                }
            });

            // If this is search page, clear old cookies and ignore savedMin/savedMax
            if (document.body.classList.contains('search') || window.location.href.includes('?s=')) {
                Cookies.remove('min_price');
                Cookies.remove('max_price');
            }

            const savedMin = Cookies.get('min_price');
            const savedMax = Cookies.get('max_price');

            if (savedMin && savedMax && !(document.body.classList.contains('search') || window.location.href.includes('?s='))) {
                // Set inputs
                minInput.value = savedMin;
                maxInput.value = savedMax;

                // Apply to slider (after creation)
                priceSlider.noUiSlider.set([savedMin, savedMax]);
                // runAjaxFilter();
            } else {
                // Apply to slider (after creation)
                priceSlider.noUiSlider.set([categoryPriceRange.min, categoryPriceRange.max]);
                // runAjaxFilter();
            }

            // Update input fields on slider change
            priceSlider.noUiSlider.on('update', function (values, handle) {
                const value = Math.round(values[handle]);
                if (handle === 0) {
                    minInput.value = value;
                    Cookies.set('min_price', value, { expires: 1 });
                } else {
                    maxInput.value = value;
                    Cookies.set('max_price', value, { expires: 1 });
                }

            });


            // If user types in inputs directly
            minInput.addEventListener('change', function () {
                priceSlider.noUiSlider.set([this.value, null]);
                $('#ajax-filter-form').trigger('change');
            });

            maxInput.addEventListener('change', function () {
                priceSlider.noUiSlider.set([null, this.value]);
                $('#ajax-filter-form').trigger('change');
            });


            // Slider drag stops trigger AJAX
            priceSlider.noUiSlider.on('change', function (values) {
                $('#min_price').val(Math.round(values[0]));
                $('#max_price').val(Math.round(values[1]));
                runAjaxFilter();
            });

        }

        // end Price range slider

        // const discountSlider = document.getElementById('discount-slider');

        // if (discountSlider) {

        //     const discountInput = document.getElementById('discount_range');

        //     noUiSlider.create(discountSlider, {
        //         start: [dicountValue], // initial values
        //         connect: true,
        //         range: {
        //             min: 1,
        //             max: 100
        //         },
        //         step: 1,
        //         format: {
        //             to: value => Math.round(value),
        //             from: value => Number(value)
        //         }
        //     });

        //     // Update input fields on slider change
        //     discountSlider.noUiSlider.on('update', function (values, handle) {
        //         discountInput.value = values[handle];
        //     });
        //     // When slider value changes (after drag ends), update input and run AJAX
        //     discountSlider.noUiSlider.on('change', function (values) {
        //         discountInput.value = Math.round(values[0]);
        //         runAjaxFilter();
        //     });
        // }
        // end Discount range slider
    });

    // JavaScript to Trigger Form Submission with Sort
    document.getElementById('sort-by-select').addEventListener('change', function () {
        const sortValue = this.value;
        const hiddenSortInput = document.getElementById('sort_by_hidden');

        // Update hidden input in form
        if (hiddenSortInput) {
            hiddenSortInput.value = sortValue;
        }

        // Trigger change on the hidden input to fire AJAX (if your filters listen to input changes)
        const event = new Event('change', { bubbles: true });
        hiddenSortInput.dispatchEvent(event);
    });


    const COOKIE_NAME = 'product_filter_data';
    const $productList = $('#products-list');
    const $loader = $('#filter-loader');

    // Load filters from cookie on page load
    const savedFilters = Cookies.get(COOKIE_NAME);
    if (savedFilters) {
        const data = JSON.parse(savedFilters);

        // Hide product list and show loader immediately
        $productList.addClass('hidden-on-load');
        $loader.addClass('active');

        if (data.min_price) $('#min_price').val(data.min_price);
        if (data.max_price) $('#max_price').val(data.max_price);

        // if (data.attributes == dataClassValue) $('.fil-head').addClass('open');
        // if (data.attributes == dataClassValue) $('.fil-body').addClass('open');

        if (data.attributes) {
            $.each(data.attributes, function (key, values) {
                values.forEach(function (val) {
                    $(`input[name="${key}[]"][value="${val}"]`).prop('checked', true);
                });
            });
        }

        // Delay to let checkboxes apply before running filter
        setTimeout(() => {
            runAjaxFilter();
        }, 100); // slight delay to allow DOM to settle
    } else {
        // No filters — show the product list immediately
        $productList.removeClass('hidden-on-load');
        // runAjaxFilter();
    }

    // On filter change
    // $('#ajax-filter-form input').on('change', function () {
    //     saveFilterToCookie();
    //     runAjaxFilter();
    // });

    function saveFilterToCookie() {

        const filterData = {
            min_price: $('#min_price').val(),
            max_price: $('#max_price').val(),
            attributes: {}
        };

        // Collect all checked attribute filters
        $('#ajax-filter-form input[type="checkbox"]:checked').each(function () {
            const name = $(this).attr('name').replace('[]', '');
            const val = $(this).val();

            if (!filterData.attributes[name]) {
                filterData.attributes[name] = [];
            }
            filterData.attributes[name].push(val);
        });

        Cookies.set(COOKIE_NAME, JSON.stringify(filterData), { expires: 7 }); // 7 days
    }

    $('#ajax-filter-form').on('change', 'input, select', function (e) {
        e.preventDefault();
        saveFilterToCookie();
        runAjaxFilter();
    });

    $(document).on('click', '.filter_products a', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        runAjaxFilter(page); // ✅ pass clicked page number
    });
})(jQuery);








