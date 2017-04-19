/**
 * Created by phoenix on 4/19/17.
 */

(function($) {

    // DropDown UserMenu
    $('#user_menu').hover(
        function() {
            $( '#user_menu ul' ).stop( true,true ).slideDown(200)
        }, function() {
            $( '#user_menu ul' ).stop( true,true ).slideUp(200)
        }
    );

})(jQuery)