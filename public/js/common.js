$(function () {
    // 大きいスライダーの作成
    var galleryTop = new Swiper('.gallery-top', {
        effect: ($('.gallery-top .swiper-slide').length > 1) ? 'slide' : 'fade',
        speed: 500,
        spaceBetween: 0,
        loop: true,
        centeredSlides: true,
        loopedSlides: 3,
        controller: false,
        autoplay: {
            delay: 2300,
            disableOnInteraction: false
        },
        pagination: {
            el: '.slider .pagination',
        }
        
    });
    // サムネイルの連動
    galleryTop.on('slideChangeTransitionEnd', function () {
        var index = galleryTop.realIndex;
        $('.thumbs .active').removeClass('active');
        $('.thumbs li').eq(index).addClass('active');
    });
    // サムネイルのクリック
    $(document).on('click', '.thumbs li a', function (event) {
        event.preventDefault();
        var target = $(this).parent();
        var index = $('.thumbs li').index(target);
        galleryTop.slideToLoop(index);
    });
    // 小さいスライダー作成
    // var galleryThumbs = new Swiper('.thumbs-sp', {
    //     spaceBetween: 10,
    //     loop: true,
    //     centeredSlides: true,
    //     slidesPerView: 'auto',
    //     touchRatio: 0.2,
    //     slideToClickedSlide: true,
    // });
    // galleryTop.controller.control = galleryThumbs;
    // galleryThumbs.controller.control = galleryTop;

    // キャンペーン
    var campaignSwiper = new Swiper('.campaign-slider', {
        slidesPerView: 3,
        spaceBetween: 20,
        // slidesPerGroup: 3,
        navigation: {
            nextEl: '.campaign .swiper-button-next',
            prevEl: '.campaign .swiper-button-prev',
        }
    });

    // SPキャンペーン
    var campaignSwipersp = new Swiper('.campaign-slider-sp', {
        width: 180,
        slidesPerView: 1,
        slidesPerGroup: 1,
        freeMode: true,
        spaceBetween: 20,
    });

    $(document).on('click', '.see-more a', function (event) {
        event.preventDefault();
        var contents = $(this).attr('data-target-contents');
        var button = $(this).attr('data-target-button');
        var speed = 200;
        $('*[data-contents=' + contents + ']')
            .removeClass('d-none')
            .css('display', 'none')
            .slideDown(speed);
        $('*[data-button=' + button + ']').removeClass('d-none');
        $(this).parent().addClass('d-none');
    });

    // トップへのスクロール
    $(document).ready(function () {
        var pagetop = $('.footer-top');
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                pagetop.fadeIn();
            } else {
                pagetop.fadeOut();
            }
        });
        pagetop.click(function () {
            $('body, html').animate({ scrollTop: 0 }, 500);
            return false;
        });
    });

    $(document).on('click', 'a[data-scroll-target]', scrollPageLink);
    $(document).on('click', '.header-menu', headerMenuToggle);

});

/**
 * ハンバーガーメニュー開閉
 */
function headerMenuToggle() {
    var menu = $('.sp-menu');
    var open = $('.header-menu .open');
    var close = $('.header-menu .close');
    if (menu.hasClass('d-none')) {
        // メニューが閉まっているとき
        menu.removeClass('d-none');
        open.addClass('d-none');
        close.removeClass('d-none');
    } else {
        // メニューが開いてるとき
        menu.addClass('d-none');
        open.removeClass('d-none');
        close.addClass('d-none');
    }
}


function isMobile() {
    return ($(window).width() < 768);
}

/**
 * ページリンクスクロール処理
 * @param {Event} event
 */
function scrollPageLink(event) {
    event.preventDefault();
    var targetSelecter = $(this).attr('data-scroll-target');
    var top = $(targetSelecter).offset().top - $('header').height() - 20;
    $('body, html').animate({ scrollTop: top }, 500);
}


function getParam(name, url) { 
    if (!url) url = window.location.href; 
    name = name.replace(/[\[\]]/g, "\\$&"); 
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"), results = regex.exec(url); 
    if (!results) return null; 
    if (!results[2]) return ''; 
    return decodeURIComponent(results[2].replace(/\+/g, " "));
 }