$(function () {
    // スケジュール
    createScheduleDate();
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
    $(document).on('click', '.schedule-slider .swiper-slide a', selectSchedule);
    var scrollTimer = null;
    $(window).on('scroll', scrollProcess);
    $(window).on('resize', resizeProcess);

});

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
 * スケジュール日付部分作成
 */
function createScheduleDate() {
    var done = function (res, textStatus, jqXhr) {
        // 通信成功の処理
        // console.log(res);
        // エラー処理
        if (res.meta.error !== api.errorCode.OK) {
            var message = 'エラーが発生しました。';
            $('.error').html(message).removeClass('d-none').addClass('d-block');
            return;
        }
        // 先行販売表示
        var preSaleList = res.data.filter(function (data) {
            return (data.has_pre_sale);
        });
        if (preSaleList.length > 0) {
            $('.pre-sales-text').removeClass('d-none');
        }
        // スケジュール生成
        var dateDom = [];
        res.data.forEach(function (data, index) {
            var date = data.date;
            var month = data.date.split('-')[1];
            var day = data.date.split('-')[2];
            var ddd = moment(date.replace(/-/g, '')).format('ddd');
            var hasPreSale = data.has_pre_sale;
            var usable = data.usable;
            var className = (usable)
                ? (index === 0)
                    ? 'active border-light-blue bg-blue text-white'
                    : 'text-dark-gray'
                : 'text-gray bg-dark-gray not-event';
            var service = (day === '01')
                ? 'ファーストデイ'
                : (day === '15')
                    ? 'シネマサンシャインデイ'
                    : (ddd === '水')
                        ? 'レディースデイ'
                        : '&nbsp';
            var dom = '<div class="swiper-slide text-center">\
                <a href="#" class="d-block line-height-1 pt-3 pb-2 ' + className + '" data-date="' + date + '">\
                    <div class="mb-2">\
                        <strong class="large mr-1">' + month + ' / ' + day + '</strong>\
                        <strong class="small">(' + ddd + ')</strong>\
                    </div>\
                    <div class="x-small mb-1">' + service + '</div>\
                    <div class="x-small pre-sales">' + ((hasPreSale) ? '先行販売' : '&nbsp') + '</div>\
                </a>\
            </div>';
            dateDom.push(dom);
        });
        $('.schedule-slider .swiper-wrapper').append(dateDom.join('\n'));
        // スライダー生成
        var scheduleSwiper = new Swiper('.schedule-slider .swiper-container', {
            spaceBetween: 0,
            slidesPerView: 7,
            breakpoints: {
                320: { slidesPerView: 2 },
                767: { slidesPerView: 3 },
                1024: { slidesPerView: 6 }
            }
        });
        scheduleSwiper.on('resize', function () {
            var target = $('.schedule-slider .swiper-slide .active').parent();
            var index = $('.schedule-slider .swiper-slide').index(target);
            scheduleSwiper.slideTo(index, 0, false);
        });
        // 前回の選択
        var json = sessionStorage.getItem('selected');
        if (json !== null) {
            var selected = JSON.parse(json);
            var target = $('.schedule-slider .swiper-slide a[data-date='+ selected.date +']');
            if (target.length > 0) {
                target.trigger('click');
                return;
            }
        }
        // スケジュール取得
        getSchedule();
    };
    var fail = function (jqXhr, textStatus, errorThrown) {
        // 通信失敗の処理
        console.log(jqXhr, textStatus, errorThrown);
        var message = 'エラーが発生しました。';
        $('.error').html(message).removeClass('d-none').addClass('d-block');
    }
    // エラー初期化
    $('.error').removeClass('d-block').addClass('d-none');
    var theaterName = $('body').attr('data-theater');
    api.schedule.list(theaterName).done(done).fail(fail);
}


/**
 * スケジュール選択
 * @param {Event} event 
 */
function selectSchedule(event) {
    event.preventDefault();
    if ($(this).hasClass('active')) {
        return;
    }
    $('.schedule-slider .active')
        .addClass('text-dark-gray')
        .removeClass('active border-light-blue bg-blue text-white');
    $(this)
        .addClass('active border-light-blue bg-blue text-white')
        .removeClass('text-dark-gray');

    // 選択を保存
    var json = sessionStorage.getItem('selected');
    var selected = {};
    if (json !== null) {
        selected = JSON.parse(json);
    }
    selected.date = $(this).attr('data-date');
    sessionStorage.setItem('selected', JSON.stringify(selected));

    getSchedule();
}

/**
 * スケジュール取得
 */
function getSchedule() {
    var date = $('.schedule-slider .active').attr('data-date');
    var done = function (res, textStatus, jqXhr) {
        // 通信成功の処理
        // console.log(res);
        // エラー処理
        if (res.meta.error !== api.errorCode.OK) {
            var message = 'エラーが発生しました。';
            $('.error').html(message).removeClass('d-none').addClass('d-block');
            return;
        }
        var schedule = res.data;
        var pcDomList = [];
        var spDomList = [];
        schedule.forEach(function (film) {
            var parent = createScheduleFilmDom(film);
            var tmpPerformances = [];
            film.screen.forEach(function (screen) {
                screen.time.forEach(function (time) {
                    if (moment(date.replace(/-/g, '')).format('YYYYMMDD') === moment().format('YYYYMMDD')
                        && time.end < moment().format('HH:mm')) {
                        // 上映終了
                        return;
                    }
                    tmpPerformances.push({
                        screen: screen,
                        time: time
                    });
                });
            });
            var performances = tmpPerformances.sort(function (a, b) {
                if (a.time.start < b.time.start) return -1;
                if (a.time.start > b.time.start) return 1;
                return 0;
            });
            performances.forEach(function (performance) {
                var child = createScheduleFilmPerformanceDom(performance, film);
                parent.pc.find('.performances').append(child.pc);
                parent.sp.find('.performances').append(child.sp);
            });
            pcDomList.push(parent.pc);
            spDomList.push(parent.sp);
        });
        $('.schedule-sort-film').html(pcDomList);
        $('.schedule-sort-film-sp').html(spDomList);
    };
    var fail = function (jqXhr, textStatus, errorThrown) {
        // 通信失敗の処理
        // console.log(jqXhr, textStatus, errorThrown);
        var status = jqXhr.status;
        var message = (status === 404)
            ? 'スケジュールが見つかりません。'
            : 'エラーが発生しました。';
        $('.error').html(message).removeClass('d-none').addClass('d-block');
    }
    // スケジュール表示
    $('.selected-date span').text(moment(date.replace(/-/g, '')).format('YYYY年MM月DD日(ddd)'));
    // 先行販売
    var isPreSales = ($('.schedule-slider .active .pre-sales').text().trim().length > 0);
    var preSales = $('.selected-date .pre-sales');
    preSales
        .removeClass('d-inline-block')
        .addClass('d-none');
    if (isPreSales) {
        preSales
            .addClass('d-inline-block')
            .removeClass('d-none');
    }
    // エラー初期化
    $('.error').removeClass('d-block').addClass('d-none');
    // スケジュール初期化
    $('.schedule-sort-film').html('');
    $('.schedule-sort-film-sp').html('');
    var theaterName = $('body').attr('data-theater');
    api.schedule.date(theaterName, date).done(done).fail(fail);
}

/**
 * スケジュール作品Dom生成
 */
function createScheduleFilmDom(film) {
    var data = {
        name: film.name,
        comment: film.comment,
        runningTime: film.running_time,
        cmTime: film.cm_time,
        shortCode: film.short_code,
        branchCode: film.branch_code
    };
    var pcDom = $('<div class="border mb-3">\
    <div class="border-bottom bg-light-gray p-3">\
        <div class="mb-2"><strong>'+ data.name + '</strong></div>\
        <div class="small text-dark-gray mb-2 d-flex align-items-center"><i class="mr-2 time-icon"></i><span class="mr-2">'+ (data.runningTime + data.cmTime) + '分</span></div>\
        <div class="small text-dark-gray line-height-1">'+ data.comment + '</div>\
    </div>\
    <ul class="performances d-flex flex-wrap mb-0 px-3 pt-3 pb-0 text-center"></ul>\
</div>');
    var spDom = $('<div class="rounded mb-3 shadow-01">\
<div class="border-bottom">\
    <a class="bg-light-gray p-3 pr-5 d-block collapsed" href="#" data-toggle="collapse" data-target="#collapse' + (data.shortCode + data.branchCode) + '" aria-expanded="false">\
        <div class="mb-2"><strong>'+ data.name + '</strong></div>\
        <div class="small text-dark-gray d-flex align-items-center"><i class="mr-2 time-icon"></i><span class="mr-2">'+ (data.runningTime + data.cmTime) + '分</span></div>\
    </a>\
</div>\
<div class="collapse" id="collapse'+ (data.shortCode + data.branchCode) + '">\
    <div class="small text-dark-gray line-height-1 p-2 border-bottom">'+ data.comment + '</div>\
    <ul class="performances mb-0 p-2"></ul>\
</div>\
</div>');
    return {
        pc: pcDom,
        sp: spDom
    };
}

/**
 * スケジュール子Dom生成
 */
function createScheduleFilmPerformanceDom(performance, film) {
    var data = {
        name: film.name,
        comment: film.comment,
        runningTime: film.running_time,
        cmTime: film.cm_time,
        shortCode: film.short_code,
        branchCode: film.branch_code,
        startTime: performance.time.start,
        endTime: performance.time.end,
        screenName: performance.screen.name,
        available: performance.time.available,
        url: performance.time.url,
        late: performance.time.late
    };
    
    var target = '_self';
    
    if (data.url.match(/www1.cinemasunshine.jp/) !== null) {
        target = '_blank';
    }
    
    var lateClass = (data.late === 1)
        ? 'first'
        : (data.late === 2)
            ? 'late'
            : '';
    var pcAvailableColorClass = (data.available === 0)
        ? 'bg-blue text-white'
        : (data.available === 1)
            ? 'bg-light-gray text-dark-gray'
            : (data.available === 2)
                ? 'bg-yellow text-white'
                : (data.available === 4)
                    ? 'bg-light-gray text-dark-gray'
                    : 'bg-light-gray text-dark-gray';
    var pcAvailable = (data.available === 0)
        ? '<span class="mr-2 status-01">○</span><span>購入</span>'
        : (data.available === 1)
            ? '窓口'
            : (data.available === 2)
                ? '<span class="mr-2 status-02">△</span><span>購入</span>'
                : (data.available === 4)
                    ? '窓口'
                    : '予約不可';
    var pcDom = $('<li class="mb-3">\
<a class="d-block position-relative py-2 mx-2 '+ lateClass + ' ' + pcAvailableColorClass + '" href="' + data.url + '" target="' + target + '">\
    <div class="mb-2"><strong class="large">'+ data.startTime + '</strong><span>～' + data.endTime + '</span></div>\
    <div class="small mb-2">'+ data.screenName + '</div>\
    <div class="d-flex align-items-center justify-content-center">' + pcAvailable + '</div>\
</a>\
</li>');
    var spAvailableColorClass = (data.available === 0)
        ? ''
        : (data.available === 1)
            ? 'bg-light-gray text-dark-gray'
            : (data.available === 2)
                ? ''
                : (data.available === 4)
                    ? 'bg-light-gray text-dark-gray'
                    : 'bg-light-gray text-dark-gray';
    var spAvailable = (data.available === 0)
        ? '<a class="d-flex align-items-center justify-content-center py-3 bg-blue text-white" href="' + data.url + '" target="' + target + '>\
        <span class="mr-2 status-01">○</span><span>購入</span>\
    </a>'
        : (data.available === 1)
            ? '<span class="d-block">窓口</span>'
            : (data.available === 2)
                ? '<a class="d-flex align-items-center justify-content-center py-3 bg-yellow text-white" href="' + data.url + '">\
                <span class="mr-2 status-02">△</span><span>購入</span>\
            </a>'
                : (data.available === 4)
                    ? '<span class="d-block">窓口</span>'
                    : '<span class="d-block">予約不可</span>';
    var spDom = $('<li class="border-bottom d-flex align-items-center justify-content-between py-3 pl-2 ' + lateClass + ' ' + spAvailableColorClass + '">\
    <div class="line-height-1">\
        <div><strong class="x-large">'+ data.startTime + '</strong></div>\
        <div>～'+ data.endTime + '</div>\
    </div>\
    <div class="x-small mx-2">'+ data.screenName + '</div>\
    <div class="purchase-button text-center">' + spAvailable + '</div>\
</li>');
    return {
        pc: pcDom,
        sp: spDom
    };
}