jQuery(function ($) {
    const baseUrl = racketWizard.baseUrl; // passed from PHP
    let current = 0;
    const $steps = $('#racket-wizard .step');
    const $dots = $('#wizard-progress li');
    const total = $steps.length;

    function updateProgress(i) {
        $dots.removeClass('active completed');
        $dots.each(function (index) {
            if (index < i) { $(this).addClass('completed'); }
        });
        $dots.eq(i).addClass('active');
    }

    function show(i) {
        if (i < 0 || i >= total) return;
        $steps.attr('hidden', true).eq(i).removeAttr('hidden');
        updateProgress(i);
        current = i;
        if (current === total - 1) {
            $('.gobackbtn').hide();
            $('#wizard-progress').hide();
        } else {
            $('.gobackbtn').show();
            $('#wizard-progress').show();
        }
    }

    show(0);

    // Next
    $(document).on('click', '#racket-wizard .next', function (e) {
        const $curr = $steps.eq(current);
        if ($curr.find('input[required]:checked').length) show(current + 1);
        else alert('Please choose an option.');
    });

    // Skip
    $(document).on('click', '#racket-wizard .skip', function (e) {
        show(current + 1);
    });

    // Previous
    $(document).on('click', '.goback .prev', function (e) {
        show(current - 1);
    });

    // Submit
    $(document).on('click', '#racket-wizard .finish', function (e) {

        const email = $('#step_email').val().trim();
        const checkbox = $('#confirm_mail_sub').prop('checked');

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) || !checkbox) {
            alert('Prašome įvesti galiojantį el. paštą ir sutikti su privatumo politika.');
            return;
        }

        const filterCat = $('input[name=filter_cat]:checked').val();
        const lytis = $('input[name=pa_lytis]:checked').val();
        const amzius = $('input[name=pa_amzius]:checked').val();
        const lygis = $('input[name=pa_lygis]:checked').val();
        const tikimasi = $('input[name="pa_ko-tikimasi-is-raketes[]"]:checked')
            .map(function () { return this.value; })
            .get();

        const filterData = {
            attributes: { lytis: [lytis], amzius: [amzius], lygis: [lygis], 'ko-tikimasi-is-raketes': tikimasi }
        };

        Cookies.set('product_filter_data', JSON.stringify(filterData), { expires: 7 });

        // Send email via AJAX
        $.post(racketWizard.ajaxUrl, {
            action: 'send_raketes_vedlys_email',
            email: email,
            lytis: lytis,
            amzius: amzius,
            lygis: lygis,
            tikimasi: tikimasi.join(', '),
            category: filterCat
        });

        $('body').append(`
            <div id='loading-popup' style='position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #FAFBFD; z-index: 9999; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 500;'>
                <div class='d-flex flex-column align-items-center'>
                    <img class='loading-spinner' src='/wp-content/themes/ewsdevtwo/assets/images/loading.png' width='48' alt='Loading...'>
                    <h2 class='mt-4'>Personalizuojami rezultatai</h2>
                </div>
            </div>
        `);

        const targetUrl = baseUrl + (filterCat === 'Padelis' ? 'padelis/?raketes-vedlys' : 'tenisas/?raketes-vedlys');
        setTimeout(() => window.location.href = targetUrl, 300);
    });
});
