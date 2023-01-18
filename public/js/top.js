$(function () {
    $(document).on('click', '.region-selection-sp.plus-button a', function (event) {
        event.preventDefault();
        if ($('.region-selection').is(':visible')) {
            return;
        }
        // 劇場一覧アコーディオン開く
        var speed = 200;
        $('.region-selection')
            .removeClass('d-none')
            .css('display', 'none')
            .slideDown(speed, function () {
                $('.region-selection-sp')
                    .removeClass('plus-button')
                    .addClass('minus-button');
            });
    });
    $(document).on('click', '.region-selection-sp.minus-button a', function (event) {
        event.preventDefault();
        if ($('.region-selection').is(':hidden')) {
            return;
        }
        // 劇場一覧アコーディオン閉じる
        var speed = 200;
        $('.region-selection')
            .slideUp(speed, function () {
                $('.region-selection-sp')
                    .removeClass('minus-button')
                    .addClass('plus-button');
                $('.region-selection').addClass('d-none')
            });
    });

    $('#ranking').one('inview', () => {
        const $ranking = $(this);

        $.ajax('api/title/ranking')
            .done((data) => {
                if (data.data.date_range.from) {
                    $ranking.find('.help .from').text(formatRankingDate(data.data.date_range.from));
                }

                if (data.data.date_range.to) {
                    $ranking.find('.help .to').text(formatRankingDate(data.data.date_range.to));
                }

                data.data.ranking.forEach((rankData) => {
                    $ranking.find('.scroll ul').append(
                        factoryRankingTitleElement(rankData)
                    );
                });
            });
    });

    function formatRankingDate(dateStr) {
        const weekdayJp = ['日', '月', '火', '水', '木', '金', '土'];
        const tmp = dateStr.split('-');
        const date = new Date(tmp[0], tmp[1] - 1, tmp[2]);
        return (date.getMonth() + 1) + '/' + date.getDate() + '(' + weekdayJp[date.getDay()] + ')';
    }

    function factoryRankingTitleElement(rankData) {
        const $row = $('<li>').addClass('ml-0');

        const $rank = ((rank) => {
            $el = $('<div>').addClass('text-center text-white mb-2 py-1 small');
            $el.addClass('rank-0' + rank).text(rank);
            return $el;
        })(rankData.rank);
        $row.append($rank);

        const $linkBase = $('<a>').attr('href', rankData.url);

        const $titleImage = ((image, $linkEl) => {
            const $el = $('<div>').addClass('mb-2 border');
            const $image = $('<img>').addClass('w-100').attr('src', image.length == '0' ? '/images/common/no_img_movie.png' : image);
            $linkEl.append($image);
            $el.append($linkEl);
            return $el;
        })(rankData.image, $linkBase.clone());
        $row.append($titleImage);

        const $titleName = ((name, $linkEl) => {
            $el = $('<p>').addClass('mb-0');
            $linkEl.text(name);
            $el.append($linkEl);
            return $el;
        })(rankData.name, $linkBase.clone());
        $row.append($titleName);

        return $row;
    }
});
