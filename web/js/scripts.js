/**
 * Created by phoenix on 4/19/17.
 */

(function($) {

    $("a#single_image").fancybox();

    // DropDown UserMenu
    $('#user_menu').hover(
        function() {
            $( '#user_menu ul' ).stop( true,true ).slideDown(200)
        }, function() {
            $( '#user_menu ul' ).stop( true,true ).slideUp(200)
        }
    );

    // DropDown UserMenu
    $('#header_cart').hover(
        function() {
            $( '#header_cart .mini-cart-list' ).stop( true,true ).slideDown(200)
        }, function() {
            $( '#header_cart .mini-cart-list' ).stop( true,true ).slideUp(200)
        }
    );

})(jQuery)