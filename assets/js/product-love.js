(function ($) {
    const updateHeaderTotal = (count) => {
        $('#loveCount').text(count);
        if (count > '0') {
            $('#loveCount').removeClass('d-none');
        } else {
            $('#loveCount').addClass('d-none');
        }
    };
    function updateWishlistUrl() {
        if (!loveData.isLoggedIn) {
            const guest = JSON.parse(localStorage.getItem('guestLoves') || '[]');
            const $wishlistLink = $('.wishlist-link');

            if (guest.length > 0 && $wishlistLink.length) {
                const baseHref = $wishlistLink.prop('href').split('?')[0]; // Remove old query
                const newUrl = new URL(baseHref, window.location.origin);
                newUrl.searchParams.set('guest_ids', guest.join(','));

                $wishlistLink.prop('href', newUrl.toString());
            }
        }
    }

    $(document).ready(() => {
        const isLoggedIn = loveData.isLoggedIn;
        let loved = loveData.loved || [];

        // Guests: read from localStorage
        if (!isLoggedIn) {
            loved = JSON.parse(localStorage.getItem('guestLoves') || '[]');
        }
        // console.log(loved);

        // Mark products as loved
        loved.forEach(id => {
            $('[data-id="' + id + '"]').addClass('loved');
        });

        // Update header count
        updateHeaderTotal(loved.length);

        if (!isLoggedIn) {
            // Update header url
            updateWishlistUrl()
        }
    });

    $(document).on('click', '.love-btn', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const id = $btn.data('id');
        let loved = loveData.loved || [];
        const isWishlistPage = $('body').hasClass('page-wishlist');

        if (loveData.isLoggedIn) {
            $.post(loveData.ajax, {
                action: 'toggle_love',
                nonce: loveData.nonce,
                id: id
            }, res => {
                if (!res.success) return;

                const isLoved = $btn.hasClass('loved');
                $btn.toggleClass('loved');

                // Update global loveData + header count
                if (isLoved) {
                    loveData.loved = loveData.loved.filter(i => i !== id);
                } else {
                    loveData.loved.push(id);
                }

                updateHeaderTotal(loveData.loved.length);

            });

            // if (isWishlistPage) {
            //     location.reload();
            // }
        } else {
            let guest = JSON.parse(localStorage.getItem('guestLoves') || '[]');
            const isLoved = guest.includes(id);

            if (isLoved) {
                guest = guest.filter(v => v !== id);
                $btn.removeClass('loved');
            } else {
                guest.push(id);
                $btn.addClass('loved');
            }

            localStorage.setItem('guestLoves', JSON.stringify(guest));
            updateHeaderTotal(guest.length);
            updateWishlistUrl();

        }

        if (!loveData.isLoggedIn && isWishlistPage) {
            // guest: reload with IDs in URL
            const guest = JSON.parse(localStorage.getItem('guestLoves') || '[]');
            const url = new URL(window.location.href);
            url.searchParams.set('guest_ids', guest.join(','));
            window.location.href = url.toString();
        }
        if (loveData.isLoggedIn && isWishlistPage) {
            // LoggedIn: reload with URL
            const url = new URL(window.location.href);
            window.location.href = url.toString();
        }

    });

    // Set cookie for guest wishlist
    function syncGuestLovesToCookie() {
        const guest = localStorage.getItem('guestLoves') || '[]';
        document.cookie = 'guest_loves=' + encodeURIComponent(guest) + '; path=/; max-age=3600';
    }
    document.addEventListener('DOMContentLoaded', syncGuestLovesToCookie);

})(jQuery);
