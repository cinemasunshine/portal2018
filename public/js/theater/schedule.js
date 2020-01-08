var SCHEDULE_API_ENDPOINT = 'https://ssktsscheduletest.blob.core.windows.net';
var API_TIMEOUT = 60 * 1000;
var PRE_SALE_DIFFERENCE_DAY = 2;

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
        var threshold = 10;
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
            ? moment(this.time.member_rsv_start_day + " " + this.time.member_rsv_start_time, 'YYYYMMDD HHmm')
            : moment(this.time.rsv_start_day + " " + this.time.rsv_start_time, 'YYYYMMDD HHmm');
        return rsvStartDate > moment();
    };
    /**
     * 予約期間後判定（上映開始10分以降）
     */
    Performance.prototype.isAfterPeriod = function () {
        var startDate = moment(this.date + " " + this.time.start_time, 'YYYYMMDD HHmm');
        return moment(startDate).add(10, 'minutes') < moment();
    };
    /**
     * 窓口判定（上映開始60分前から上映開始0分後）
     */
    Performance.prototype.isWindow = function () {
        var startDate = moment(this.date + " " + this.time.start_time, 'YYYYMMDD HHmm');
        var now = moment();
        return (this.time.seat_count.cnt_reserve_free > 0
            && moment(startDate).add(-60, 'minutes') < now
            && moment(startDate) > now);
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
        var endDate = moment(this.date + " " + this.time.end_time, 'YYYYMMDD HHmm');
        return (displayStartDate < now && endDate > now);
    };
    /**
     * url生成
     */
    Performance.prototype.createURL = function () {
        var theaterName = $('body').attr('data-theater');
        var theatreTable = getTheaterTable();
        var theatreTableFindResult = theatreTable.find(function (t) { return (theaterName === t.name); });
        var env = 'dev';
        var plefix = (env === 'production') ? '0' : '1';
        var id = plefix + theatreTableFindResult.code + this.movie.movie_short_code + this.movie.movie_branch_code + this.date + this.screen.screen_code + this.time.start_time;
        var url = 'https://entrance.ticket-cinemasunshine.com/purchase/index.html?id=';
        return url + id;
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
    return performances;
}
/**
 * パフォーマンスを作品で絞り込み
 */
function filterPerformancebyMovie(performances, movie) {
    var filterResult = performances.filter(function (p) { return p.movie.movie_short_code === movie.movie_short_code && p.movie.movie_branch_code === movie.movie_branch_code; });
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
 * 劇場一覧取得
 */
function getTheaterTable() {
    return [
        { "code": "20", "name": "gdcs" },
        { "code": "02", "name": "heiwajima" },
        { "code": "19", "name": "yukarigaoka" },
        { "code": "13", "name": "tsuchiura" },
        { "code": "06", "name": "numazu" },
        { "code": "21", "name": "lalaportnumazu" },
        { "code": "14", "name": "kahoku" },
        { "code": "16", "name": "yamatokoriyama" },
        { "code": "17", "name": "shimonoseki" },
        { "code": "07", "name": "okaido" },
        { "code": "08", "name": "kinuyama" },
        { "code": "09", "name": "shigenobu" },
        { "code": "15", "name": "masaki" },
        { "code": "12", "name": "kitajima" },
        { "code": "18", "name": "aira" }
    ]
}

Vue.component('purchase-performance-film', {
    props: ['schedule'],
    data: function () {
        return {
            performances: [],
            filterPerformancebyMovie: filterPerformancebyMovie
        };
    },
    created: function () {
        this.performances = schedule2Performance(this.schedule, isSignIn());
    },
    template: '<div>\
    <div class="schedule-sort-film d-none d-md-block">\
        <div v-for="movie of schedule.movie" class="border mb-3">\
            <div class="border-bottom bg-light-gray p-3">\
                <div class="mb-2"><strong>{{ movie.name }}</strong></div>\
                <div class="small text-dark-gray mb-2 d-flex align-items-center">\
                    <i class="mr-2 time-icon"></i>\
                    <span class="mr-2">{{ movie.running_time }}分</span>\
                </div>\
                <div v-if="movie.comment" class="small text-dark-gray line-height-1">{{ movie.comment }}</div>\
            </div>\
            <ul class="performances d-flex flex-wrap mb-0 px-3 pt-3 pb-0 text-center">\
                <li v-for="performance of filterPerformancebyMovie(performances, movie)" v-if="performance.isDisplay()" class="mb-3">\
                    <a v-bind:href="performance.createURL()" class= "d-block position-relative py-2 mx-2" \
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
                        <div v-if="!performance.isSalse() && performance.isBeforePeriod()" class="d-flex align-items-center justify-content-center">販売期間外</div>\
                        <div v-if="!performance.isSalse() && performance.isAfterPeriod()" class="d-flex align-items-center justify-content-center">販売期間外</div>\
                        <div v-if="!performance.isSalse() && !performance.isBeforePeriod() && !performance.isAfterPeriod() && performance.isWindow()" class="d-flex align-items-center justify-content-center">窓口</div>\
                    </a>\
                </li>\
            </ul>\
        </div>\
    </div>\
    <div class="schedule-sort-film-sp d-md-none">\
        <div v-for="movie of schedule.movie" class="rounded mb-3 shadow-01">\
            <div class="border-bottom">\
                <a class="bg-light-gray p-3 pr-5 d-block" href="#" data-toggle="collapse" v-bind:data-target="\'#collapse\' + movie.movie_code" aria-expanded="true">\
                    <div class="mb-2"><strong>{{ movie.name }}</strong></div>\
                    <div class="small text-dark-gray d-flex align-items-center">\
                        <i class="mr-2 time-icon"></i>\
                        <span class="mr-2">{{ movie.running_time }}分</span>\
                    </div>\
                </a>\
            </div>\
            <div class="collapse show" v-bind:id="\'collapse\' + movie.movie_code" style="">\
                <div v-if="movie.comment" class="small text-dark-gray line-height-1 p-2 border-bottom">{{ movie.comment }}</div>\
                <ul class="performances mb-0 p-2">\
                    <li v-for="performance of filterPerformancebyMovie(performances, movie)" v-if="performance.isDisplay()" \
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
                            <a v-if="performance.isSalse()" v-bind:href="performance.createURL()" \
                            class="d-flex align-items-center justify-content-center py-3" v-bind:class="performance.getAvailability().className">\
                                <span class="mr-2" v-bind:class="performance.getAvailability().symbolClassName">{{ performance.getAvailability().symbolText }}</span>\
                                <span>{{ performance.getAvailability().text }}</span>\
                            </a>\
                            <span v-if="!performance.isSalse() && performance.isBeforePeriod()" class="d-block">販売期間外</span>\
                            <span v-if="!performance.isSalse() && performance.isAfterPeriod()" class="d-block">販売期間外</span>\
                            <span v-if="!performance.isSalse() && !performance.isBeforePeriod() && !performance.isAfterPeriod() && performance.isWindow()" class="d-block">窓口</span>\
                        </div>\
                    </li>\
                </ul>\
            </div>\
        </div>\
    </div>\
</div>'});

/**
 * スケジュールレンダリング
 */
function scheduleRender() {
    var app = new Vue({
        el: '#schedule',
        data: {
            theaterCode: undefined,
            currentDate: moment().format('YYYYMMDD'),
            dateList: [],
            error: undefined,
            timer: undefined,
            schedules: [],
            schedule: undefined,
            moment: moment
        },

        created: function () {
            var _this = this;
            this.getSchedule().done(function (data) {
                _this.schedules = data;
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
                var now = moment();
                var result = [];
                var today = moment(now).format('YYYYMMDD');
                this.schedules.forEach(function (schedule) {
                    var findResult = schedule.movie.find(function (m) {
                        return m.screen.find(function (s) {
                            return s.time.find(function (t) {
                                return (moment(t.online_display_start_day) <= moment(today)
                                    && moment(schedule.date + " " + t.end_time, 'YYYYMMDD HHmm') > now);
                            }) !== undefined;
                        }) !== undefined;
                    });
                    var preSale = schedule.movie.find(function (m) {
                        return m.screen.find(function (s) {
                            return s.time.find(function (t) {
                                var rsvStartDate = moment(t.rsv_start_day + " " + t.rsv_start_time, 'YYYYMMDD HHmm');
                                var startDate = moment(schedule.date + " " + t.start_time, 'YYYYMMDD HHmm');
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
                            serviceDay: (day === '01') ? 'ファーストデイ'
                                : (day === '15') ? 'シネマサンシャインデイ'
                                    : (date.format('ddd') === '水') ? 'レディースデイ'
                                        : undefined
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
            },
            /**
             * スケジュール取得
             */
            getSchedule: function () {
                this.error = undefined;
                var now = moment().format('YYYYMMDDHHmm');
                var theaterName = $('body').attr('data-theater');
                var theatreTable = getTheaterTable();
                var theatreTableFindResult = theatreTable.find(function (t) { return (theaterName === t.name); });
                var url = SCHEDULE_API_ENDPOINT + '/' + theatreTableFindResult.name + '/schedule/json/schedule.json?date=' + now;
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
                }, 0);
            },
            /**
             * 定期的にスケジュール更新
             */
            update: function () {
                var time = 1000 * 60 * 5;
                var _this = this;
                this.timer = setInterval(function () {
                    _this.getSchedule().done(function (data) {
                        _this.schedules = data;
                        _this.createDate();
                        _this.createSchedule();
                    }).fail(function (error) {
                        console.error(error);
                    });
                }, time);
            },

            /**
             * パフォーマンス選択
             */
            selectPerformance: function (params) {
                var event = params.event;
                var performance = params.performance;
                event.preventDefault();
                // 残席なしなら遷移しない
                if (!performance.isSalse()) {
                    return;
                };
                var performances = schedule2Performance(this.schedule, false);
                var filterResult = performances.filter(function (p) {
                    return (p.movie.movie_short_code === performance.movie.movie_short_code
                        && p.movie.movie_branch_code === performance.movie.movie_branch_code
                        && p.isSalse());
                });
                var _this = this;
                var data = filterResult.map(function (p) {
                    return {
                        id: _this.theaterCode + p.createId(),
                        startTime: p.getTime('start')
                    };
                });
                sessionStorage.setItem('performances', JSON.stringify(data));
                var id = this.theaterCode + performance.createId();
                var entrance = $('input[name=ENTRANCE_SERVER_URL]').val();
                location.href = entrance + '/fixed/index.html?id=' + id;
            },
            /**
             * 日付変更
             */
            changeDate: function (event, value) {
                event.preventDefault();
                this.currentDate = value;
                sessionStorage.setItem('selected', JSON.stringify({ date: this.currentDate }));
                this.createSchedule();
            },
        }
    });
}



