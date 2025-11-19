// Custom js


// a tag stop scroll event
document.querySelectorAll('.no-scroll').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault(); // stop scrolling to top
    });
});

// site-header - sticky_overly
document.addEventListener("DOMContentLoaded", function () {
    let header = document.querySelector(".site-header");
    let header_wrap = document.querySelector(".header_wrap");

    window.addEventListener("scroll", function () {
        if (window.scrollY > 200) {
            header.classList.add("sticky");
            if (header_wrap.classList.contains('header_overly')) {
                header_wrap.classList.remove("header_overly");
                header_wrap.classList.add("sticky_overly");
            }
        } else {
            header.classList.remove("sticky");
            if (header_wrap.classList.contains('sticky_overly')) {
                header_wrap.classList.add("header_overly");
                header_wrap.classList.remove("sticky_overly");
            }
        }
    });
});

// Mobile menu open  
const checkbox = document.getElementById('menu-toggle');
const mobile_menu = document.getElementById('mobile_menu');
const menu = document.querySelector('.main-menu');

checkbox.addEventListener('change', () => {
    if (checkbox.checked) {
        menu.classList.add('open');
        mobile_menu.classList.add('open');
    } else {
        menu.classList.remove('open');
        mobile_menu.classList.remove('open');
    }
});
// Mobile menu Dropdown open
document.querySelectorAll('.top_nav .has-sub > a').forEach(link => {
    link.addEventListener('click', function (e) {
        // e.preventDefault(); // prevent navigation
        const parent = this.parentElement;

        // Close other open menus if needed
        document.querySelectorAll('.top_nav .has-sub').forEach(item => {
            if (item !== parent) {
                item.classList.remove('open');
                item.querySelector('.dropdown_box').style.maxHeight = null;
            }
        });

        const submenu = parent.querySelector('.dropdown_box');
        const isOpen = parent.classList.contains('open');

        if (isOpen) {
            submenu.style.maxHeight = null;
            parent.classList.remove('open');
        } else {
            submenu.style.maxHeight = submenu.scrollHeight + "px";
            parent.classList.add('open');
        }
    });
});
document.querySelectorAll('.cat_head > h3').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        const parent = this.parentElement;
        const submenu = parent.querySelector('ul'); // this cat_head’s submenu
        const topLabel = parent.closest('ul'); // one level higher <ul>
        const isOpen = parent.classList.contains('open');

        // Close other open menus
        document.querySelectorAll('.cat_head').forEach(item => {
            if (item !== parent) {
                item.classList.remove('open');
                const ul = item.querySelector('ul');
                if (ul) ul.style.maxHeight = null;
            }
        });

        // Toggle
        if (isOpen) {
            submenu.style.maxHeight = null;
            parent.classList.remove('open');
        } else {
            submenu.style.maxHeight = submenu.scrollHeight + 30 + "px";
            parent.classList.add('open');
            // Act on top level UL
            topLabel.style.maxHeight = "none";
        }
    });
});



// product search
jQuery(document).ready(function ($) {
    $('#product-search').on('click', function () {
        // Your code here
        $('#search-results').fadeIn();
    });
    $('#product-search').on('keyup', function () {
        let searchText = $(this).val();

        if (searchText.length >= 1) {
            $.ajax({
                url: ajaxurl, // WordPress AJAX URL
                type: 'POST',
                data: {
                    action: 'ajax_product_search',
                    query: searchText
                },
                success: function (response) {
                    $('#search-results').html(response).fadeIn();
                }
            });
        } else {
            $('#search-results').fadeOut();
        }
    });

    let hideTimeout;

    $('.search-popup').on('mouseleave', function () {
        hideTimeout = setTimeout(function () {
            $('#search-results').fadeOut();
        }, 200); // 200ms delay
    });

    $('.search-popup').on('mouseenter', function () {
        clearTimeout(hideTimeout); // Cancel fadeOut if mouse comes back quickly
    });


    // Hide search results when clicking outside
    // $(document).on('click', function (event) {
    //     if (!$(event.target).closest('.woocommerce-product-search').length) {
    //         $('#search-results').fadeOut();
    //     }
    // });
});


document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
        const link = document.getElementById('sr_submit');
        const form = document.getElementById('search-form');
        // console.log(link, 'btn load', form);
        if (link && form) {


            link.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent actual navigation
                if (form.querySelector('#product-search').value.trim()) {
                    form.submit();
                }
            });
        }
    }, 1000); // Small delay to ensure CF7 loads
});


document.addEventListener("DOMContentLoaded", function () {
    var heroSlider = new Swiper(".hero-slider", {
        loop: true,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true, // Allow users to click dots to navigate
        },
        effect: "slide",
    });
});



// full Container slide not item
const screenWidth = window.innerWidth;
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.my-swiper2').forEach((slider) => {
        new Swiper(slider, {
            slidesPerView: 'auto', // Auto width based on content
            spaceBetween: 20, // Space between slides
            freeMode: false, // Enable draggable slide
            grabCursor: false, // Show grab cursor
            mousewheel: {
                forceToAxis: true,
                sensitivity: 1,
            },
            centeredSlides: false,
            watchOverflow: true,
            loop: false,
            slidesOffsetAfter: -screenWidth - 240,
            // direction: "vertical",
            // breakpoints: {
            //     320: {
            //       slidesOffsetAfter: -320,
            //     },
            //     768: {
            //       slidesOffsetAfter: -768,
            //     },
            //     1024: {
            //       slidesOffsetAfter: -1024,
            //     },
            //     1440: {
            //       slidesOffsetAfter: -1440,
            //     },
            navigation: {
                nextEl: slider.querySelector('.my-swiper2 .swiper-button-next'),
                prevEl: slider.querySelector('.my-swiper2 .swiper-button-prev'),
            },
            on: {
                slideChangeTransitionStart: function () {
                    let progress = (this.realIndex + 1) / this.slides.length * 100;
                    document.querySelector('.my-swiper2 .swiper-progress').style.width = progress + "%";
                }
            }
        });
    });
});



document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.brand-slider').forEach((slider) => {
        new Swiper(slider, {
            slidesPerView: 4,   // Show 4 items at a time
            slidesPerGroup: 1,  // Move 1 item per slide
            loop: true,        // Disable looping
            autoplay: false,    // No autoplay
            spaceBetween: 16,
            mousewheel: true, // Enable mouse scroll
            breakpoints: {
                0: {
                    slidesPerView: 2,
                },
                560: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
                1200: {
                    slidesPerView: 4,
                },
            },
            navigation: {
                nextEl: slider.querySelector('.brand-slider .swiper-button-next'),
                prevEl: slider.querySelector('.brand-slider .swiper-button-prev'),
            },
            on: {
                slideChangeTransitionStart: function () {
                    let progress = (this.realIndex + 1) / this.slides.length * 100;
                    document.querySelector('.brand-slider .swiper-progress').style.width = progress + "%";
                }
            }
        });
    });
});





// Modify subscribe label text
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
        let checkboxLabel = document.querySelector('.subs_form label span,.con_form_st label span'); // Adjust class as needed

        if (checkboxLabel) {
            checkboxLabel.innerHTML = 'Sutinku su <strong><a href="https://tennis.urbanlabs.lt/privatumo-politika/">privatumo politikos taisyklėmis.</a></strong>';
            // console.log("Checkbox label updated successfully.");
        } else {
            console.log("Checkbox label not found.");
        }
    }, 1000); // Small delay to ensure CF7 loads
});


// Accordion
// Accordion inside form
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.faq-question').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();

            const answer = button.nextElementSibling;
            const isOpen = answer.classList.contains('open');

            // Close all
            document.querySelectorAll('.faq-answer').forEach(a => {
                a.style.maxHeight = null;
                a.classList.remove('open');
                a.previousElementSibling.classList.remove('open');
            });

            // Toggle current
            if (!isOpen) {
                answer.classList.add('open');
                button.classList.add('open');
                answer.style.maxHeight = answer.scrollHeight + 24 + "px";
            }
        });
    });

    // // Open all accordions by default
    // document.querySelectorAll('.faq-answer').forEach(answer => {
    //     const question = answer.previousElementSibling;
    //     answer.classList.add('open');
    //     question.classList.add('open');
    //     answer.style.maxHeight = answer.scrollHeight + 24 + "px"; // Add spacing if needed
    // });

});


// accordion-group  toggle
document.querySelectorAll('.accordion-group').forEach(group => {
    const questions = group.querySelectorAll('.fil-head');
    const answers = group.querySelectorAll('.fil-body');

    // Open the first by default
    const firstAnswer = answers[0];
    const firstQuestion = questions[0];

    if (firstAnswer && firstQuestion) {
        const height = firstAnswer.scrollHeight + 24;
        // if (height > 330) {
        //     firstAnswer.style.maxHeight = '330px';
        //     firstAnswer.style.overflowY = 'auto';
        //     firstAnswer.style.marginBottom = '12px';
        //     firstQuestion.classList.add('open')
        //     firstAnswer.classList.add('open')
        // } else {
        // firstQuestion.classList.add('open')
        // firstAnswer.classList.add('open')
        // firstAnswer.style.maxHeight = firstAnswer.scrollHeight + 24 + "px";
        // }
    }
    if (firstAnswer.classList.contains('mh_none')) {
        firstAnswer.style.maxHeight = 'none';
    } else if (firstAnswer.classList.contains('open')) {
        firstAnswer.style.maxHeight = firstAnswer.scrollHeight + 24 + "px";
    }

    // Add click listener
    questions.forEach((question, index) => {
        question.addEventListener('click', () => {
            const answer = answers[index];
            const isOpen = answer.classList.contains('open');
            const mh_none = answer.classList.contains('mh_none');

            // Close all in this group
            answers.forEach(a => {
                a.style.maxHeight = null;
                a.classList.remove('open');
                a.style.marginBottom = '0';
            });
            questions.forEach(q => q.classList.remove('open'));

            // Toggle clicked one
            if (!isOpen) {
                const height = firstAnswer.scrollHeight + 24;
                // if (height > 330) {
                //     firstAnswer.style.maxHeight = '330px';
                //     firstAnswer.style.overflowY = 'auto';
                //     firstAnswer.style.marginBottom = '12px';
                // } else {
                if (firstAnswer.classList.contains('mh_none')) {
                    firstAnswer.style.maxHeight = 'none';
                } else {
                    answer.style.maxHeight = answer.scrollHeight + 24 + "px";
                }

                answer.classList.add('open');
                question.classList.add('open');
            }
        });
    });
});







/// Woo
jQuery(document.body).trigger('wc_fragment_refresh');

setTimeout(function () {
    jQuery(document).on('click', '.remove', function (e) {
        e.preventDefault();
        var product_id = jQuery(this).data('product_id');
        var cart_item_key = jQuery(this).attr('data-cart_item_key');
        var product_container = jQuery(this).closest('.woocommerce-mini-cart-item');

        jQuery.ajax({
            type: 'GET',
            url: jQuery(this).attr('href'),
            success: function () {
                // Update cart fragments
                jQuery(document.body).trigger('wc_fragment_refresh');
            }
        });
    });

}, 3000); // Small delay to ensure CF7 loads


// Compare popup
// Store Compare List in LocalStorage
document.addEventListener('DOMContentLoaded', function () {
    let compareList = JSON.parse(localStorage.getItem('compareList')) || [];

    // On load, check pre-selected checkboxes
    document.querySelectorAll('.compare-product').forEach(cb => {
        const id = cb.dataset.productId;
        if (compareList.includes(id)) {
            cb.checked = true;
        }
    });

    // Handle checkbox toggle
    document.querySelectorAll('.compare-product').forEach(cb => {
        cb.addEventListener('change', function () {
            const productId = this.dataset.productId;
            const title = this.dataset.title;
            const imageurl = this.dataset.imageurl;

            if (!productId || !title || !imageurl) {
                console.warn('Missing product data:', productId, title, imageurl);
                return;
            }

            let compareList = JSON.parse(localStorage.getItem('compareList')) || [];

            // Check for existing
            const exists = compareList.find(p => p.id === productId);

            if (this.checked && !exists) {
                compareList.push({
                    id: productId,
                    title: title,
                    imageurl: imageurl
                });
            } else if (!this.checked && exists) {
                compareList = compareList.filter(p => p.id !== productId);
            }

            localStorage.setItem('compareList', JSON.stringify(compareList));
            updatePopup();
        });
    });

});


// JavaScript to Show Selected Items and Allow Removal
document.addEventListener('DOMContentLoaded', function () {
    const popup = document.getElementById('compare_box');
    const listContainer = document.getElementById('compare-list-items');
    const compareBtn = document.getElementById('compare-now-btn');
    const clearBtn = document.getElementById('clear-compare');
    const additem = document.getElementById('additem');

    function updatePopup() {
        const compareList = JSON.parse(localStorage.getItem('compareList')) || [];

        listContainer.innerHTML = '';

        if (compareList.length === 1) {
            popup.style.display = 'none';
            return;
        }

        if (compareList.length > 1) {
            popup.style.display = 'flex';
        }

        // Enforce the max selection rule
        const allCheckboxes = document.querySelectorAll('.compare-product');
        if (compareList.length >= 4) {
            allCheckboxes.forEach(cb => {
                if (!cb.checked) cb.disabled = true;
            });
            if (additem) additem.style.display = 'none';
        } else {
            if (additem) additem.style.display = 'block'; // in case it was hidden before
        }

        compareList.forEach(product => {
            const li = document.createElement('li');

            li.innerHTML = `
        <div class="cp_img"><img src="${product.imageurl}" alt="${product.title}">
        
        <button data-id="${product.id}"></button></div>
      `;

            // Remove item button
            li.querySelector('button').addEventListener('click', () => {
                const updatedList = compareList.filter(p => p.id !== product.id);
                localStorage.setItem('compareList', JSON.stringify(updatedList));

                // Uncheck the checkbox on the page if visible
                document.querySelectorAll(`.compare-product[data-product-id="${product.id}"]`)
                    .forEach(cb => cb.checked = false);

                updatePopup();
                updateCheckboxState();
            });

            listContainer.appendChild(li);
        });

        // Update Compare button URL
        const ids = compareList.map(p => p.id);
        compareBtn.href = `/prekiu-palyginimas/?ids=${ids.join(',')}`;
    }

    // When checkboxes are toggled
    document.querySelectorAll('.compare-product').forEach(cb => {
        cb.addEventListener('change', function () {
            const product = {
                id: this.dataset.productId,
                title: this.dataset.title,
                imageurl: this.dataset.imageurl
            };

            let compareList = JSON.parse(localStorage.getItem('compareList')) || [];

            // Only proceed if all data is available
            if (!product.id || !product.title || !product.imageurl) {
                console.warn('Missing product data:', product);
                return;
            }


            const exists = compareList.find(p => p.id === product.id);

            if (this.checked && !exists) {
                if (compareList.length >= 4) {
                    alert('You can compare up to 4 products only.');
                    this.checked = false;
                    return;
                }

                compareList.push(product);
            } else if (!this.checked && exists) {
                compareList = compareList.filter(p => p.id !== product.id);
            }


            localStorage.setItem('compareList', JSON.stringify(compareList));
            updatePopup();
            updateCheckboxState();
        });
    });

    // Disable Other Checkboxes When Limit Reached 
    function updateCheckboxState() {
        const compareList = JSON.parse(localStorage.getItem('compareList')) || [];
        const allCheckboxes = document.querySelectorAll('.compare-product');
        const additem = document.getElementById('additem');

        if (compareList.length >= 4) {
            allCheckboxes.forEach(cb => {
                if (!cb.checked) cb.disabled = true;
            });
            if (additem) additem.style.display = 'none';
        } else {
            allCheckboxes.forEach(cb => cb.disabled = false);
        }
    }


    // Clear All
    clearBtn.addEventListener('click', () => {
        localStorage.removeItem('compareList');

        // Uncheck all checkboxes
        document.querySelectorAll('.compare-product').forEach(cb => cb.checked = false);

        updatePopup();
        updateCheckboxState();
    });


    // Initial load
    // updatePopup();
});

// Close Compare popup
document.addEventListener('DOMContentLoaded', function () {
    const cartIcon = document.querySelector('.cmp-btn');
    const cartPopup = document.getElementById('compare_box');
    const closeCart = document.getElementById('close-cmpop');
    const additem = document.getElementById('additem');

    // cartIcon.addEventListener('click', function (e) {
    //     e.preventDefault();
    //     cartPopup.style.display = 'block';
    // });

    closeCart.addEventListener('click', function (e) {
        e.preventDefault();
        cartPopup.style.display = 'none';
    });
    additem.addEventListener('click', function (e) {
        e.preventDefault();
        cartPopup.style.display = 'none';
    });
});


// Auto-Check Compare Boxes
document.addEventListener('DOMContentLoaded', () => {
    restoreCheckedCompareCheckboxes();
});
function restoreCheckedCompareCheckboxes() {
    const compareList = JSON.parse(localStorage.getItem('compareList')) || [];
    const storedIds = compareList.map(p => String(p.id)); // make sure IDs are strings

    document.querySelectorAll('.compare-product').forEach(cb => {
        const productId = cb.dataset.productId;
        if (storedIds.includes(productId)) {
            cb.checked = true;
        }
    });
}

// custom select dropdown style - css+js
jQuery(document).ready(function ($) {
    $('.custom select').niceSelect();

});
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
        const niceSelect = document.querySelector('.nice-select.form-select');
        // console.log('niceSelect', niceSelect);

        if (niceSelect) {
            niceSelect.addEventListener('click', function (e) {
                const selected = e.target.closest('.option');
                if (selected) {
                    const value = selected.getAttribute('data-value');
                    const originalSelect = document.getElementById('sort-by-select');
                    if (originalSelect) {
                        originalSelect.value = value;
                        originalSelect.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }
            });
        }
    }, 100); // 100ms delay
});

