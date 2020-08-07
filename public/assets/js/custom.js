(function($) {
    'use strict';

    $(document).ready(function ($) {



    }); //end DOM ready


    window.onload = function() {

            var y = window.matchMedia("(max-width: 768px)")
            if (y.matches) {
                $('.new-class-changzz').click(function(){

                    $(".new-class-chang").toggle('slow');
                })
            }
        }

})(jQuery);

