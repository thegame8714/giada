var slider = {

    init: function() {

        if(jQuery('.work_wrapper').length > 0) {

            var slider = jQuery('.work_wrapper').bxSlider({
                mode: 'fade',
                controls: false,
                pagerCustom: '.slider-pager-wrapper',
                auto: true,
                useCSS: false
            });


           jQuery('.work_container').hover(function() {
               slider.stopAuto();

           },function() {
               slider.startAuto();

           });

        }


    }

};

jQuery(document).ready(function($) {

    slider.init();

    jQuery(window).on('load resize', function() {

        //jQuery('#primary-nav ul').css('left',jQuery(window).width());

        //console.log(ul_position.left + ' width: ' + jQuery(window).width());
    });

    var $main_img = $('.work_image img').attr('src');

    $('.menu-burger').hover(function() {

        $('#primary-nav ul').toggleClass('active');


    }); // end of menu-burger a hover

    $('.menu-burger .burger').on('click',function(e) {

        e.preventDefault();

        return false;


    }); // end of menu-burger a click

    $('#primary-nav ul li a').on('click',function() {

        var href = $(this).attr('href');


        $('html,body').animate({
                scrollTop: $(href).offset().top},
            'slow');


    });

    $('.work_gallery_single_image img').hover(function() {

        $('.work_image img').attr('src',$(this).attr('src'));


    });


}); //end of document ready