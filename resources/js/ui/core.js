$(document).ready(function () {

    $('.main__info').bind('swipedown', handler2);

    function handler2(event) {
        $('.main__info').fadeOut();
    };

    //initSlider();

    function fModal() {
        //modal
        $('.toggler').on('click', function (e) {
            e.preventDefault();
            var $this = $(e.currentTarget);
            var target = $this.data('target');
            $('.modal').removeClass('_active');
            $('body').removeClass('_modal-open');
            $('.modal-backdrop').fadeOut();

            $("#" + $(this).data("target")).toggleClass('_active');
            $("#" + $(this).data("target")).closest('.modal-backdrop').fadeIn();
            $("#" + $(this).data("target")).closest('body').toggleClass('_modal-open');
            //$('.mask-modal').fadeIn();
        });
    }

    //modal
    $('.toggler').on('click', function (e) {
        e.preventDefault();
        var $this = $(e.currentTarget);
        var target = $this.data('target');
        $('.modal').removeClass('_active');
        $('body').removeClass('_modal-open');
        $('.modal-backdrop').fadeOut();

        $("#" + $(this).data("target")).toggleClass('_active');
        $("#" + $(this).data("target")).closest('.modal-backdrop').fadeIn();
        $("#" + $(this).data("target")).closest('body').toggleClass('_modal-open');
        //$('.mask-modal').fadeIn();
    });

    $('.toggler.secondary').on('click', function (e) {
        e.preventDefault();
        var $this = $(e.currentTarget);
        var target = $this.data('target');
        //$('.modal').removeClass('_active');
        //$('body').removeClass('_modal-open');
        //$('.modal-backdrop').fadeOut();

        $("#" + $(this).data("target")).toggleClass('_active');
        $("#" + $(this).data("target")).closest('.modal-backdrop').fadeIn();
        $("#" + $(this).data("target")).closest('body').toggleClass('_modal-open');
        //$('.mask-modal').fadeIn();
    });

    $('.mask-modal').on('click', function (e) {
        e.preventDefault();

        $(this).parent().toggleClass('_active');
        $(this).parent().fadeOut();

        if ($('._active').length === 0) {
            $(this).parent().closest('body').removeClass('_modal-open');
        }

        destroySliders();

    });

    $('.js-circle-drop').on('click', function (e) {
        $('.circle-drop__menu').toggleClass("-show", 600, "easeOutSine");
    })

    // var toggle = $('#ss_toggle');
    // var menu = $('#ss_menu');
    // var rot;

    // $('#ss_toggle').on('click', function(ev) {
    //   rot = parseInt($(this).data('rot')) - 180;
    //   menu.css('transform', 'rotate(' + rot + 'deg)');
    //   menu.css('webkitTransform', 'rotate(' + rot + 'deg)');
    //   if ((rot / 180) % 2 == 0) {
    //     //Moving in
    //     toggle.parent().addClass('ss_active');
    //     toggle.addClass('close');
    //   } else {
    //     //Moving Out
    //     toggle.parent().removeClass('ss_active');
    //     toggle.removeClass('close');
    //   }
    //   $(this).data('rot', rot);
    // });

    // menu.on('transitionend webkitTransitionEnd oTransitionEnd', function() {
    //   if ((rot / 180) % 2 == 0) {
    //     $('#ss_menu div i').addClass('ss_animate');
    //   } else {
    //     $('#ss_menu div i').removeClass('ss_animate');
    //   }
    // });

});

function initSliders(){
    // Удаляем если есть, чтобы обновился нормально
    destroySliders();

    //carousel-tag
    $('.tag-slider').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        dots: false,
        autoWidth: true,
        items: 4,
    });
    //carousel
    $('.img-slider').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        dots: false,
        items: 1,
    });
}

function destroySliders(){
    $('.img-slider').owlCarousel('destroy');
}
