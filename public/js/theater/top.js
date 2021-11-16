$(function () {
    // スケジュール
    if ($('#schedule').length) {
        $(window).on('scroll', scrollProcess);
        $(window).on('resize', resizeProcess);
    }
    // 開場時間
    var openingTimeSwiper = new Swiper('.opening-time .swiper-container', {
        spaceBetween: 20,
        slidesPerView: 'auto',
        navigation: {
            nextEl: '.opening-time .swiper-button-next',
            prevEl: '.opening-time .swiper-button-prev',
        },
        breakpoints: {
            767: { spaceBetween: 0 }
        }
    });
    openingTimeSwiper.on('resize', function () {
        openingTimeSwiper.slideTo(0, 0, false);
    });
    var scrollTimer = null;
    // お知らせ表示
    initInformations();
    // スケジュールレンダリング
    scheduleRender();
    $('#schedule').removeClass('d-none');
});

/**
 * お知らせ表示
 */
function initInformations() {
    //if ($('body').attr('data-theater') === 'gdcs') {
    //    return;
    // }
    var informationId = $('#information').val();
    var informations = localStorage.getItem('informations');
    informations = (informations) ? JSON.parse(informations) : [];
    var findResult = informations.find(function (id) { return (informationId === id); });
    if (findResult !== undefined) {
        return;
    }
    // お知らせ表示
    $('#informationModal').modal('show');
    $('#informationModal').on('hidden.bs.modal', saveInformations);
}

/**
 * お知らせ保存
 */
function saveInformations() {
    var isChecked = $('#information').prop('checked');
    if (isChecked) {
        var informationId = $('#information').val();
        var informations = localStorage.getItem('informations');
        informations = (informations) ? JSON.parse(informations) : [];
        var findResult = informations.find(function (id) { return (informationId === id); });
        if (findResult === undefined) {
            informations.push(informationId);
        }
        localStorage.setItem('informations', JSON.stringify(informations));
    }
}

/**
 * リサイズ処理
 */
function resizeProcess() {
    var scrollClass = 'fixed-top';
    $('.schedule-scroll').height('auto');
    $('.schedule-slider').removeClass(scrollClass);
}

/**
 * スクロール処理
 */
function scrollProcess() {
    var scrollClass = 'fixed-top';
    var headerHeight = $('header').height() + $('.sub-header').height();
    var scheduleSliderHeight = $('.schedule-slider').height();
    var scheduleHeight = $('.schedule').height();
    var selectedDateHeight = $('.selected-date').height();
    var scrollTop = $(window).scrollTop();
    var scheduleSliderTop = $('.schedule-scroll').offset().top;
    var selectDateTop = $('.selected-date-scroll').offset().top;
    if (scrollTop > (scheduleSliderTop - headerHeight)
        && scrollTop < (scheduleSliderTop - headerHeight + scheduleHeight - scheduleSliderHeight)) {
        if (!$('.schedule-slider').hasClass(scrollClass)) {
            $('.schedule-scroll').height(scheduleSliderHeight);
            $('.schedule-slider')
                .addClass(scrollClass)
                .css('top', headerHeight + 'px');
        }
    } else {
        $('.schedule-scroll').height('auto');
        $('.schedule-slider').removeClass(scrollClass);
    }
}


/**
 * サインイン判定
 */
function isSignIn() {
    return $('#signIn').length > 0;
}
