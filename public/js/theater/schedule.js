var SCHEDULE_API;
var MP_TICKET_ENTRANCE;
var MP_TICKET;
var APP_ENV;
var API_TIMEOUT = 60 * 1000;
var PRE_SALE_DIFFERENCE_DAY = 2;
var SCHEDULE_STATUS_THRESHOLD_VALUE = 20;
var SELLER;

/**
 * パフォーマンス
 * @class
 */
var Performance = (function () {
    function Performance(params) {
        this.date = params.date;
        this.movie = params.movie;
        this.screen = params.screen;
        this.time = params.time;
        this.member = (params.member === undefined) ? false : params.member;
    }
    /**
     * 予約ステータス情報取得
     */
    Performance.prototype.getAvailability = function () {
        var value = this.time.seat_count.cnt_reserve_free / this.time.seat_count.cnt_reserve_max * 100;
        var availability = [
            { symbolText: '×', symbolClassName: 'status-03', icon: '/images/fixed/status_03.svg', className: 'bg-light-gray text-dark-gray', text: '満席' },
            { symbolText: '△', symbolClassName: 'status-02', icon: '/images/fixed/status_02.svg', className: 'bg-yellow text-white', text: '購入' },
            { symbolText: '○', symbolClassName: 'status-01', icon: '/images/fixed/status_01.svg', className: 'bg-blue text-white', text: '購入' }
        ];
        var threshold = SCHEDULE_STATUS_THRESHOLD_VALUE;
        return (value === 0)
            ? availability[0] : (value <= threshold)
                ? availability[1] : availability[2];
    };
    /**
     * 販売可能判定
     */
    Performance.prototype.isSalse = function () {
        return !this.isBeforePeriod()
            && !this.isAfterPeriod()
            && !this.isWindow()
            && this.time.seat_count.cnt_reserve_free > 0;
    };
    /**
     * 予約期間前判定
     */
    Performance.prototype.isBeforePeriod = function () {
        var rsvStartDate = (this.member)
            ? moment(this.time.member_rsv_start_day + ' ' + this.time.member_rsv_start_time, 'YYYYMMDD HHmm')
            : moment(this.time.rsv_start_day + ' ' + this.time.rsv_start_time, 'YYYYMMDD HHmm');
        return rsvStartDate > moment();
    };
    /**
     * 予約期間後判定（上映開始10分以降）
     */
    Performance.prototype.isAfterPeriod = function () {
        var startDate = moment(this.date + ' ' + this.time.start_time, 'YYYYMMDD HHmm');
        return moment(startDate).add(10, 'minutes') < moment();
    };
    /**
     * 窓口判定（上映開始60分前から上映開始10分後）
     */
    Performance.prototype.isWindow = function () {
        var startDate = moment(this.date + ' ' + this.time.start_time, 'YYYYMMDD HHmm');
        var now = moment();
        var WINDOW_TIME_FROM_VALUE = 0;
        var WINDOW_TIME_FROM_UNIT = 'minutes';
        var WINDOW_TIME_THROUGH_VALUE = 10;
        var WINDOW_TIME_THROUGH_UNIT = 'minutes';
        return (this.time.seat_count.cnt_reserve_free > 0
            && moment(startDate).add(WINDOW_TIME_FROM_VALUE, WINDOW_TIME_FROM_UNIT) < now
            && moment(startDate).add(WINDOW_TIME_THROUGH_VALUE, WINDOW_TIME_THROUGH_UNIT) > now);
    };
    /**
     * 上映時間取得
     */
    Performance.prototype.getTime = function (type) {
        return (type === 'start')
            ? this.time.start_time.slice(0, 2) + ":" + this.time.start_time.slice(2, 4)
            : this.time.end_time.slice(0, 2) + ":" + this.time.end_time.slice(2, 4);
    };
    /**
     * 表示判定
     */
    Performance.prototype.isDisplay = function () {
        var now = moment();
        var displayStartDate = moment(this.time.online_display_start_day, 'YYYYMMDD');
        var endDate = (this.time.start_time < this.time.end_time)
            ? moment(this.date + ' ' + this.time.end_time, 'YYYYMMDD HHmm')
            : moment(this.date + ' ' + this.time.end_time, 'YYYYMMDD HHmm').add(1, 'days');
        return (displayStartDate < now && endDate > now);
    };
    /**
     * url生成
     */
    Performance.prototype.createURL = function () {
        var id = SELLER.branchCode + this.movie.movie_short_code + this.movie.movie_branch_code + this.date + this.screen.screen_code + this.time.start_time;
        var url = MP_TICKET_ENTRANCE + '/purchase/index.html';
        return url + '?' + object2query({
            id: id,
            member: isSignIn() ? '1' : '0',
            sellerId: SELLER.id,
            redirectUrl: encodeURIComponent(MP_TICKET),
            username: isSignIn() ? $('.username').text() : undefined
        });
    };
    return Performance;
}());

/**
 * スケジュールからパフォーマンスへ変換
 */
function schedule2Performance(schedule, member) {
    var performances = [];
    var date = schedule.date;
    schedule.movie.forEach(function (movie) {
        movie.screen.forEach(function (screen) {
            screen.time.forEach(function (time) {
                performances.push(new Performance({ date: date, movie: movie, screen: screen, time: time, member: member }));
            });
        });
    });
    // 上映開始時間順へソート
    var sortResult = performances.sort(function (a, b) {
        if (a.time.start_time < b.time.start_time) {
            return -1;
        }
        else {
            return 1;
        }
    });
    return sortResult;
}
/**
 * パフォーマンスを作品で絞り込み
 */
function filterPerformancebyMovie(performances, movie) {
    var filterResult = performances.filter(function (p) { return p.movie.movie_short_code === movie.movie_short_code && p.movie.movie_branch_code === movie.movie_branch_code; });
    // 上映開始時間順へソート
    var sortResult = filterResult.sort(function (a, b) {
        if (a.time.start_time < b.time.start_time) {
            return -1;
        }
        else {
            return 1;
        }
    });
    return sortResult;
}

/**
 * 表示可能パフォーマンス判定
 */
function hasDisplayPerformance(performances, movie) {
    var target = filterPerformancebyMovie(performances, movie);
    var filterResult = target.filter(function (p) {
        return p.isDisplay();
    });
    return filterResult.length > 0;
}

Vue.component('purchase-performance', {
    props: ['performance'],
    created: function () { },
    template: '\
    <li v-if="performance.isDisplay()" class="mb-3">\
        <a v-on:click="$emit(\'selectPerformance\', {event: $event, performance: performance})" v-bind:href="performance.createURL()" class= "d-block position-relative py-2 mx-2" \
            v-bind:class="[\
                (performance.isSalse()) ? performance.getAvailability().className : \'bg-light-gray text-dark-gray\',\
                { first: performance.time.late === 1, late: performance.time.late === 2 }\
            ]">\
            <div class="mb-2">\
                <strong class="large gdcs_eng_font_b">{{ performance.getTime(\'start\') }}</strong>\
                <span class="gdcs_eng_font_r">～{{ performance.getTime(\'end\') }}</span>\
            </div>\
            <div class="small mb-2">{{ performance.screen.name }}</div>\
            <div v-if="performance.isSalse()" class="d-flex align-items-center justify-content-center">\
                <span class="mr-2" v-bind:class="performance.getAvailability().symbolClassName">{{ performance.getAvailability().symbolText }}</span>\
                <span>{{ performance.getAvailability().text }}</span>\
            </div>\
            <div v-if="!performance.isSalse() && performance.time.seat_count.cnt_reserve_free > 0 && performance.isBeforePeriod()" class="d-flex align-items-center justify-content-center">販売期間外</div>\
            <div v-if="!performance.isSalse() && performance.time.seat_count.cnt_reserve_free > 0 && performance.isAfterPeriod()" class="d-flex align-items-center justify-content-center">販売期間外</div>\
            <div v-if="!performance.isSalse() && performance.time.seat_count.cnt_reserve_free > 0 && !performance.isBeforePeriod() && !performance.isAfterPeriod() && performance.isWindow()" class="d-flex align-items-center justify-content-center">窓口</div>\
            <div v-if="!performance.isSalse() && performance.time.seat_count.cnt_reserve_free === 0" class="d-flex align-items-center justify-content-center">満席</div>\
        </a>\
    </li>'
});

Vue.component('purchase-performance-sp', {
    props: ['performance'],
    created: function () { },
    template: '\
    <li v-if="performance.isDisplay()" \
        class="border-bottom d-flex align-items-center justify-content-between py-3 pl-2" \
        v-bind:class="[\
            (performance.isSalse()) ? \'\' : \'bg-light-gray text-dark-gray\',\
            { first: performance.time.late === 1, late: performance.time.late === 2 }\
        ]">\
        <div class="line-height-1">\
            <div><strong class="x-large">{{ performance.getTime(\'start\') }}</strong></div>\
            <div>～{{ performance.getTime(\'end\') }}</div>\
        </div>\
        <div class="x-small mx-2">{{ performance.screen.name }}</div>\
        <div class="purchase-button text-center">\
            <a v-on:click="$emit(\'selectPerformance\', {event: $event, performance: performance})" v-if="performance.isSalse()" v-bind:href="performance.createURL()" \
            class="d-flex align-items-center justify-content-center py-3" v-bind:class="performance.getAvailability().className">\
                <span class="mr-2" v-bind:class="performance.getAvailability().symbolClassName">{{ performance.getAvailability().symbolText }}</span>\
                <span>{{ performance.getAvailability().text }}</span>\
            </a>\
            <span v-if="!performance.isSalse() && performance.time.seat_count.cnt_reserve_free > 0 && performance.isBeforePeriod()" class="d-block">販売期間外</span>\
            <span v-if="!performance.isSalse() && performance.time.seat_count.cnt_reserve_free > 0 && performance.isAfterPeriod()" class="d-block">販売期間外</span>\
            <span v-if="!performance.isSalse() && performance.time.seat_count.cnt_reserve_free > 0 && !performance.isBeforePeriod() && !performance.isAfterPeriod() && performance.isWindow()" class="d-block">窓口</span>\
            <span v-if="!performance.isSalse() && performance.time.seat_count.cnt_reserve_free === 0" class="d-block">満席</span>\
        </div>\
    </li>'
});



Vue.component('purchase-performance-film', {
    props: ['schedule'],
    data: function () {
        return {
            performances: [],
            filterPerformancebyMovie: filterPerformancebyMovie,
            hasDisplayPerformance: hasDisplayPerformance
        };
    },
    methods: {
        selectPerformance: function (data) {
            var performance = data.performance;
            var popupMessage1 = (performance.time.popupMessage1 === '')
                ? undefined
                : performance.time.popupMessage1;
            var popupMessage2 = (performance.time.popupMessage2 === '')
                ? undefined
                : performance.time.popupMessage2;
            if (popupMessage1 === undefined && popupMessage2 === undefined) {
                return;
            }
            data.event.preventDefault();
            $('#appearPopupNext').attr('href', performance.createURL());
            var title = (popupMessage1 === undefined)
                ? ''
                : $('<p class="mb-2 text-danger font-weight-bold large"></p>').text(popupMessage1);
            var read = (popupMessage2 === undefined)
                ? ''
                : $('<p class="mb-0"></p>').text(popupMessage2);
            $('#appearPopup .message-area')
                .html('')
                .append(title)
                .append(read);
            $('#appearPopupNext').attr('href', performance.createURL());
            $('#appearPopup').modal('show');
        }
    },
    created: function () {
        this.performances = schedule2Performance(this.schedule, isSignIn());
    },
    template: '<div>\
    <div class="schedule-sort-film d-none d-md-block">\
        <div v-for="movie of schedule.movie" v-bind:class="{ \'d-none\': !hasDisplayPerformance(performances, movie) }" class="border mb-3">\
            <div class="border-bottom bg-light-gray p-3">\
                <div class="font-weight-bold mb-2" v-html="movie.name"></div>\
                <div class="small text-dark-gray mb-2 d-flex align-items-center">\
                    <i class="mr-2 time-icon"></i>\
                    <span class="mr-2">{{ movie.running_time }}分</span>\
                </div>\
                <div v-if="movie.comment || movie.ename" class="small text-dark-gray line-height-1"><span v-if="movie.comment" v-html="movie.comment"></span><span v-if="movie.comment && movie.ename">&nbsp;/&nbsp;</span><span v-if="movie.ename" v-html="movie.ename"></span></div>\
            </div>\
            <ul class="performances d-flex flex-wrap mb-0 px-3 pt-3 pb-0 text-center">\
                <template v-for="performance of filterPerformancebyMovie(performances, movie)">\
                    <purchase-performance v-bind:performance="performance" v-on:selectPerformance="selectPerformance($event)"></purchase-performance>\
                </template>\
            </ul>\
        </div>\
    </div>\
    <div class="schedule-sort-film-sp d-md-none">\
        <div v-for="movie of schedule.movie" v-bind:class="{ \'d-none\': !hasDisplayPerformance(performances, movie) }" class="rounded mb-3 shadow-01">\
            <div class="border-bottom">\
                <a class="bg-light-gray p-3 pr-5 d-block" href="#" data-toggle="collapse" v-bind:data-target="\'#collapse\' + movie.movie_code" aria-expanded="false">\
                    <div class="font-weight-bold mb-2" v-html="movie.name"></div>\
                    <div class="small text-dark-gray d-flex align-items-center">\
                        <i class="mr-2 time-icon"></i>\
                        <span class="mr-2">{{ movie.running_time }}分</span>\
                    </div>\
                </a>\
            </div>\
            <div class="collapse" v-bind:id="\'collapse\' + movie.movie_code" style="">\
                <div v-if="movie.comment" class="small text-dark-gray line-height-1 p-2 border-bottom">{{ movie.comment }}</div>\
                <ul class="performances mb-0 p-2">\
                    <template v-for="performance of filterPerformancebyMovie(performances, movie)">\
                        <purchase-performance-sp v-bind:performance="performance" v-on:selectPerformance="selectPerformance($event)"></purchase-performance-sp>\
                    </template>\
                </ul>\
            </div>\
        </div>\
    </div>\
</div>'});

function createVueInstance() {
    return new Vue({
        el: '#schedule',
        data: {
            theaterCode: undefined,
            currentDate: moment().format('YYYYMMDD'),
            dateList: [],
            error: undefined,
            timer: undefined,
            schedules: [],
            schedule: undefined,
            moment: moment,
            scheduleSwiper: undefined,
            isPreSale: false,
            maintenance: { message: undefined }
        },

        created: function () {
            var _this = this;
            this.getSchedule().done(function (data) {
                _this.maintenance = data.maintenance;
                _this.schedules = data.schedule;
                _this.createDate();
                _this.createSchedule();
                _this.update();
            }).fail(function (error) {
                console.error(error);
            });

        },

        methods: {
            /**
             * 選択日生成
             */
            createDate: function () {
                var _this = this;
                var now = moment();
                var result = [];
                var today = moment(now).format('YYYYMMDD');
                this.schedules.forEach(function (schedule) {
                    var findResult = schedule.movie.find(function (m) {
                        return m.screen.find(function (s) {
                            return s.time.find(function (t) {
                                var endDate = (t.start_time < t.end_time)
                                    ? moment(schedule.date + ' ' + t.end_time, 'YYYYMMDD HHmm')
                                    : moment(schedule.date + ' ' + t.end_time, 'YYYYMMDD HHmm').add(1, 'days');
                                return (moment(t.online_display_start_day) <= moment(today)
                                    && endDate > now);
                            }) !== undefined;
                        }) !== undefined;
                    });
                    var preSale = schedule.movie.find(function (m) {
                        return m.screen.find(function (s) {
                            return s.time.find(function (t) {
                                var rsvStartDate = moment(t.rsv_start_day + ' ' + t.rsv_start_time, 'YYYYMMDD HHmm');
                                var startDate = moment(schedule.date + ' ' + t.start_time, 'YYYYMMDD HHmm');
                                var diff = PRE_SALE_DIFFERENCE_DAY;
                                return startDate.diff(rsvStartDate, 'day') > diff;
                            }) !== undefined;
                        }) !== undefined;
                    });
                    if (findResult === undefined) {
                        return;
                    }
                    else {
                        var date = moment(schedule.date);
                        var day = date.format('DD')
                        result.push({
                            value: schedule.date,
                            display: {
                                month: date.format('MM'),
                                week: date.format('ddd'),
                                day: date.format('DD'),
                                year: date.format('YYYY')
                            },
                            preSale: preSale !== undefined,
                            serviceDay: (schedule.name_service_day === '') ? undefined : schedule.name_service_day
                        });
                    }
                });
                this.dateList = result;
                this.isPreSale = (this.dateList.find(function (date) { return date.preSale; }) !== undefined);
                // 選択
                var json = sessionStorage.getItem('selected');
                if (json !== null) {
                    var selected = JSON.parse(json).date;
                    var findResult = this.dateList.find(function (d) { return d.value === selected; });
                    this.currentDate = (findResult === undefined) ? this.dateList[0].value : selected;
                }
                // スライダー生成
                this.scheduleSwiper = new Swiper('.schedule-slider .swiper-container', {
                    spaceBetween: 0,
                    slidesPerView: 7,
                    breakpoints: {
                        320: { slidesPerView: 2 },
                        767: { slidesPerView: 3 },
                        1024: { slidesPerView: 6 }
                    }
                });
                this.scheduleSwiper.on('resize', function () {
                    var target = $('.schedule-slider .swiper-slide .active').parent();
                    var index = $('.schedule-slider .swiper-slide').index(target);
                    _this.scheduleSwiper.slideTo(index, 0, false);
                });
                setTimeout(function () {
                    _this.scheduleSwiper.update();
                }, 0);

            },
            /**
             * スケジュール取得
             */
            getSchedule: function () {
                this.error = undefined;
                var now = moment().format('YYYYMMDDHHmm');
                var url = SCHEDULE_API + '/' + SELLER.alias + '/schedule.json?date=' + now;
                var options = {
                    dataType: 'json',
                    url: url,
                    type: 'GET',
                    timeout: API_TIMEOUT
                };
                var _this = this;
                return $.ajax(options);
            },
            /**
             * スケジュール作成
             */
            createSchedule: function () {
                this.schedule = undefined;
                var _this = this;
                setTimeout(function () {
                    var now = moment();
                    var today = moment(now).format('YYYYMMDD');
                    var searchDate = (_this.dateList.find(function (d) { return (d.value === _this.currentDate); }) === undefined)
                        ? today : _this.currentDate;
                    _this.currentDate = searchDate;
                    // 選択したスケジュールを抽出　上映終了は除外
                    _this.schedule = _this.schedules.find(function (s) { return (s.date === _this.currentDate); });
                    // 作品順へソート
                    _this.schedule.movie.sort(function (a, b) {
                        if (a.sort_no < b.sort_no) {
                            return -1;
                        }
                        else {
                            return 1;
                        }
                    });
                }, 500);
            },
            /**
             * 定期的にスケジュール更新
             */
            update: function () {
                var time = 1000 * 60 * 10;
                var _this = this;
                this.timer = setInterval(function () {
                    _this.getSchedule().done(function (data) {
                        _this.maintenance = data.maintenance;
                        _this.schedules = data.schedule;
                        _this.createDate();
                        _this.createSchedule();
                    }).fail(function (error) {
                        console.error(error);
                    });
                }, time);
            },
            /**
             * 日付変更
             */
            changeDate: function (event, value) {
                event.preventDefault();
                this.currentDate = value;
                var json = JSON.stringify({ date: this.currentDate });
                sessionStorage.setItem('selected', json);
                this.createSchedule();
            }
        }
    });
}

/**
 * オブジェクトをクエリストリングへ変換
 */
function object2query(params) {
    var query = '';
    for (var i = 0; i < Object.keys(params).length; i++) {
        var key = Object.keys(params)[i];
        var value = params[key];
        if (i > 0) {
            query += '&';
        }
        if (value === undefined) {
            continue;
        }
        query += key + '=' + value;
    }
    return query;
}

/**
 * スケジュールレンダリング
 */
function scheduleRender() {

    SCHEDULE_API = $('input[name=SCHEDULE_API]').val();
    MP_TICKET_ENTRANCE = $('input[name=MP_TICKET_ENTRANCE]').val();
    MP_TICKET = $('input[name=MP_TICKET]').val();
    APP_ENV = $('input[name=APP_ENV]').val();

    var now = moment().format('YYYYMMDDHHmm');
    var options = {
        dataType: 'json',
        url: SCHEDULE_API + '/seller/seller.json?date=' + now,
        type: 'GET',
        timeout: API_TIMEOUT
    };
    var _this = this;
    $.ajax(options).done(function (data) {
        var alias = $('body').attr('data-theater');
        var findResult = data.find(function (s) {
            return s.alias === alias;
        });
        if (findResult === undefined) {
            console.error('not seller');
            return;
        }
        SELLER = findResult;
        var app = createVueInstance();
    }).fail(function (error) {
        console.error(error);
    });;
}
