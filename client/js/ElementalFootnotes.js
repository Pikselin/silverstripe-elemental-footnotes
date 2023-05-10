/**
 * Find all anchor links with a prefix of "footnotes-link-" and add a custom CSS class
 */

(function ($) {
    // ready to go...
    $(document).ready(function () {
        $('main a[href*="#footnote-item-"]')
                //.addClass('footnote-item')
                .removeClass('ss-broken');
    });

})(jQuery);