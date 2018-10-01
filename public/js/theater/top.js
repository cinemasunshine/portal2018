$(function () {
    // スケジュール
    var scheduleSwiper = new Swiper('.schedule-slider .swiper-container', {
        spaceBetween: 0,
        slidesPerView: 7,
        // navigation: {
        //     nextEl: '.schedule .swiper-button-next',
        //     prevEl: '.schedule .swiper-button-prev',
        // },
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
    getSchedule();
    $(document).on('click', '.schedule-slider .swiper-slide a', selectSchedule);
    var scrollTimer = null;
    $(window).on('scroll', scrollProcess);
    $(window).on('resize', resizeProcess);

});

/**
 * リサイズ処理
 */
function resizeProcess() {
    $('.schedule-scroll').height('auto');
    $('.schedule-slider').removeClass('fixed-top');
    $('.selected-date-scroll').height('auto');
    $('.selected-date').removeClass('fixed-top');
}

/**
 * スクロール処理
 */
function scrollProcess() {
    var headerHeight = $('header').height();
    var scheduleSliderHeight = $('.schedule-slider').height();
    var scheduleHeight = $('.schedule').height();
    var selectedDateHeight = $('.selected-date').height();
    var scrollTop = $(window).scrollTop();
    var scheduleSliderTop = $('.schedule-scroll').offset().top;
    var selectDateTop = $('.selected-date-scroll').offset().top;
    if (scrollTop > (scheduleSliderTop - headerHeight)
        && scrollTop < (scheduleSliderTop - headerHeight + scheduleHeight - scheduleSliderHeight)) {
        if (isMobile() && !$('.schedule-slider').hasClass('fixed-top')) {
            $('.schedule-scroll').height(scheduleSliderHeight);
            $('.schedule-slider')
                .addClass('fixed-top')
                .css('top', headerHeight + 'px');
        }
    } else {
        $('.schedule-scroll').height('auto');
        $('.schedule-slider').removeClass('fixed-top');
    }
    if (scrollTop > (selectDateTop - headerHeight)
        && scrollTop < (selectDateTop - headerHeight + scheduleHeight - selectedDateHeight)) {
        if (!isMobile() && !$('.selected-date').hasClass('fixed-top')) {
            $('.selected-date-scroll').height(selectedDateHeight);
            $('.selected-date')
                .addClass('fixed-top')
                .css('top', (headerHeight + 1) + 'px');
        }
    } else {
        $('.selected-date-scroll').height('auto');
        $('.selected-date').removeClass('fixed-top');
    }
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
    getSchedule();
}

/**
 * スケジュール取得
 */
function getSchedule() {
    var theaterCode = $('input[name=theaterCode]').val();
    var date = $('.schedule-slider .active').attr('data-date');
    $('.selected-date span').text(moment(date).format('YYYY年MM月DD日(ddd)'));
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

    $('.schedule-sort-film').html('');
    $('.schedule-sort-film-sp').html('');
    $('.error').removeClass('d-block').addClass('d-none');

    var options = {
        url: '/json/schedule/' + theaterCode + '/' + date + '.json',
        type: 'GET',
        dataType: 'json',
        timeout: 10000,
    };
    var done = function (data, textStatus, jqXhr) {
        // 通信成功の処理
        // console.log(data);
        var schedule = data;
        var pcDomList = [];
        var spDomList = [];
        var filmList = scheduleSortByFilm(schedule);
        filmList.forEach(function (film) {
            var parent = createScheduleFilmDom(film.info);
            film.performances.forEach(function (performance) {
                var child = createScheduleFilmPerformanceDom(performance);
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
    $.ajax(options).done(done).fail(fail);
}

/**
 * 作品順へ変換
 */
function scheduleSortByFilm(schedule) {
    var result = [];
    schedule.forEach(function (targetSchedule) {
        var film = result.find(function (targetFilm) {
            return (targetFilm.info.movieShortCode === targetSchedule.movieShortCode
                && targetFilm.info.movieBranchCode === targetSchedule.movieBranchCode);
        });
        if (film === undefined) {
            result.push({
                info: targetSchedule,
                performances: [targetSchedule]
            });
        } else {
            film.performances.push(targetSchedule);
        }
    });

    return result;
}

/**
 * スケジュール作品Dom生成
 */
function createScheduleFilmDom(info) {
    var data = {
        name: info.name,
        comment: info.comment,
        runningTime: info.runningTime,
        cmTime: info.cmTime,
        movieShortCode: info.movieShortCode,
        movieBranchCode: info.movieBranchCode
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
    <a class="bg-light-gray p-3 pr-5 d-block collapsed" href="#" data-toggle="collapse" data-target="#collapse' + (data.movieShortCode + data.movieBranchCode) + '" aria-expanded="false">\
        <div class="mb-2"><strong>'+ data.name + '</strong></div>\
        <div class="small text-dark-gray d-flex align-items-center"><i class="mr-2 time-icon"></i><span class="mr-2">'+ (data.runningTime + data.cmTime) + '分</span></div>\
    </a>\
</div>\
<div class="collapse" id="collapse'+ (data.movieShortCode + data.movieBranchCode) + '">\
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
function createScheduleFilmPerformanceDom(performance) {
    var data = {
        name: performance.name,
        comment: performance.comment,
        runningTime: performance.runningTime,
        cmTime: performance.cmTime,
        movieShortCode: performance.movieShortCode,
        movieBranchCode: performance.movieBranchCode,
        startTime: performance.startTime,
        endTime: performance.endTime,
        screenName: performance.screenName,
        available: performance.available,
        url: performance.url,
        late: performance.late
    };
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
                    : '満席';
    var pcDom = $('<li class="mb-3">\
<a class="d-block position-relative py-2 mx-2 '+ lateClass + ' ' + pcAvailableColorClass + '" href="' + data.url + '">\
    <div class="mb-2"><strong class="large">'+ data.startTime + '</strong><span>～' + data.endTime + '</span></div>\
    <div class="small mb-2">'+ data.screenName + '</div>\
    <div class="d-flex align-items-center justify-content-center">' + pcAvailable + '</div>\
</a>\
</li>');
    var spAvailableColorClass = (data.available === 0)
        ? ''
        : (data.available === 1)
            ? 'bg-light-gray text-light-gray'
            : (data.available === 2)
                ? ''
                : (data.available === 4)
                    ? 'bg-light-gray text-dark-gray'
                    : 'bg-light-gray text-dark-gray';
    var spAvailable = (data.available === 0)
        ? '<a class="d-flex align-items-center justify-content-center py-2 bg-blue text-white" href="#">\
        <span class="mr-2 status-01">○</span><span>購入</span>\
    </a>'
        : (data.available === 1)
            ? '<span class="d-block">窓口</span>'
            : (data.available === 2)
                ? '<a class="d-flex align-items-center justify-content-center py-2 bg-yellow text-white" href="' + data.url + '">\
                <span class="mr-2 status-02">△</span><span>購入</span>\
            </a>'
                : (data.available === 4)
                    ? '<span class="d-block">窓口</span>'
                    : '<span class="d-block">満席</span>';
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