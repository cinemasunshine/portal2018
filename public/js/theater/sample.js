/**
 * スケジュール表示に関するサンプル
 */
$(function () {
    var theater = $('body').data('theater');
    
    // 日付クリックイベント（セレクタ仮）
    $(document).on('.date-list .date', 'click', function() {
        var date = 'TODO';
        var jqXhr = fetchScheduleForDate(theater, date);
        
        jqXhr
            .done(function(data) {
                var movies = data.data;
                buildMovieList(movies);
            });
    });
    
    execute();
    
    function execute() {
        /**
         * メンテなどでスケジュールを非表示にするケースあり。
         * 特定の条件で処理しないような設計にする。
         * おそらく「上映スケジュール・チケット購入」の領域を非表示にするのでそれで判断するのがいいかも。
         */
        
        var jqXhr = fetchSchedule(theater);
        jqXhr
            .done(function(data) {
                var schedules = data.data;
                buildDateList(schedules);
                
                // TODO:先行販売があれば「先行販売あり」の表示
                
                // TODO:最初の日付を検索する処理（clickのトリガー？）
            });
    }
    
    /**
     * スケジュール取得
     * 
     * @param {String} theater
     * @returns {jqXHR}
     */
    function fetchSchedule(theater) {
        var jqXhr = api.schedule.list(theater);
        
        jqXhr.done(function(data) {
                if (data.data.length === 0) {
                    alert('現在上映スケジュールは公開されていません。');
                    return;
                }
            })
            .fail(function() {
                alert('上映スケジュールを取得できませんでした。\n時間をおいて再度お試しください。');
            });
            
        return jqXhr;
    }
    
    /**
     * build date list
     * 
     * @param {Array} schedules 
     */
    function buildDateList(schedules) {
        var $list = $('.tmp');
        
        // TODO:データ調整（件数が少ないケース、日付の間が抜けてるケース）
        
        $.each(schedules, function(i, schedule) {
            var $date = buildDate(schedule);
            $list.append($date);
        });
    }
    
    /**
     * build
     * 
     * @param {Object} schedule 
     */
    function buildDate(schedule) {
        var $date = $('<div>');
        
        // TODO:要素構築
        // レディースデー、ファーストデー、メンバーズデー
        
        return $date;
    }
    
    /**
     * 指定した日付のスケジュールを取得
     * 
     * @param {String} theater 
     * @param {String} date YYYY-MM-DD
     */
    function fetchScheduleForDate(theater, date) {
        var jqXhr = api.schedule.date(theater, date);
        
        jqXhr.done(function(data) {
                if (data.data.length === 0) {
                    alert('現在上映スケジュールは公開されていません。');
                    return;
                }
            })
            .fail(function() {
                alert('上映スケジュールを取得できませんでした。\n時間をおいて再度お試しください。');
            });
            
        return jqXhr;
    }
    
    function buildMovieList(movies) {
        var $list = $('.tmp');
        
        $.each(movies, function(i, movie) {
            var $movie = buildMovie(movie);
            $list.append($movie);
        });
    }
    
    function buildMovie(movie) {
        var $movie = $('<div>');
        
        // TODO:要素構築
        
        /**
         * 購入リンクについて、コアさんの場合は別ウィンドウ。MPは画面内遷移。
         * 現行ポータルは劇場で判断しているが、導入作業を考えるとドメインなどで判断するのがいいかもしれない。
         */
        
        return $movie;
    }
});