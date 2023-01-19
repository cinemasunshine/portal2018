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

    function findNewsByCategory(category) {
        return $.ajax(`api/news/page/1/category/${category}?limit=8`);
    }

    function factoryNewsRowElement(news) {
        const $el = $('<li>').addClass('col-md-3 col-6 py-2 mb-4');
        const $link = ((news) => {
            const $el = $('<a>').addClass('d-block').attr('href', news.url);
            if (news.image.length > 0) {
                $el.append($('<div>')
                    .addClass('border mb-2 image border-gray')
                    .css('background-image', `url(${news.image})`));
            }
            $el.append($('<p>').addClass('mb-0').text(news.headline));
            return $el;
        })(news);
        $el.append($link);
        return $el;
    }

    $('#news-inview').one('inview', () => {
        $(this).addClass('d-none');
        const $section = $('#news');
        const category = 1;
        findNewsByCategory(category)
            .done((response) => {
                if (response.data.length > 0) {
                    $section.removeClass('d-none');
                }
                response.data.slice(0, 4).forEach((news) => {
                    const $row = factoryNewsRowElement(news);
                    $section.find('.container ul.row.head').append($row);
                });
                response.data.slice(4, 8).forEach((news) => {
                    const $row = factoryNewsRowElement(news);
                    $section.find('.container ul.row.more').append($row);
                });
            });
    });

    $('#news-imax-inview').one('inview', () => {
        $(this).addClass('d-none');
        const $section = $('#news-imax');
        const category = 3;
        findNewsByCategory(category)
            .done((response) => {
                if (response.data.length > 0) {
                    $section.removeClass('d-none');
                }
                response.data.slice(0, 4).forEach((news) => {
                    const $row = factoryNewsRowElement(news);
                    $section.find('.container ul.row.head').append($row);
                });
                response.data.slice(4, 8).forEach((news) => {
                    const $row = factoryNewsRowElement(news);
                    $section.find('.container ul.row.more').append($row);
                });
            });
    });

    $('#news-4dx-inview').one('inview', () => {
        $(this).addClass('d-none');
        const $section = $('#news-4dx');
        const category = 4;
        findNewsByCategory(category)
            .done((response) => {
                if (response.data.length > 0) {
                    $section.removeClass('d-none');
                }
                response.data.slice(0, 4).forEach((news) => {
                    const $row = factoryNewsRowElement(news);
                    $section.find('.container ul.row.head').append($row);
                });
                response.data.slice(4, 8).forEach((news) => {
                    const $row = factoryNewsRowElement(news);
                    $section.find('.container ul.row.more').append($row);
                });
            });
    });

    $('#news-screenx-inview').one('inview', () => {
        $(this).addClass('d-none');
        const $section = $('#news-screenx');
        const category = 6;
        findNewsByCategory(category)
            .done((response) => {
                if (response.data.length > 0) {
                    $section.removeClass('d-none');
                }
                response.data.slice(0, 4).forEach((news) => {
                    const $row = factoryNewsRowElement(news);
                    $section.find('.container ul.row.head').append($row);
                });
                response.data.slice(4, 8).forEach((news) => {
                    const $row = factoryNewsRowElement(news);
                    $section.find('.container ul.row.more').append($row);
                });
            });
    });

    $('#news-4dx-screenx-inview').one('inview', () => {
        $(this).addClass('d-none');
        const $section = $('#news-4dx-screenx');
        const category = 7;
        findNewsByCategory(category)
            .done((response) => {
                if (response.data.length > 0) {
                    $section.removeClass('d-none');
                }
                response.data.slice(0, 4).forEach((news) => {
                    const $row = factoryNewsRowElement(news);
                    $section.find('.container ul.row.head').append($row);
                });
                response.data.slice(4, 8).forEach((news) => {
                    const $row = factoryNewsRowElement(news);
                    $section.find('.container ul.row.more').append($row);
                });
            });
    });

    function factoryNewsInformationRowElement(news) {
        $el = $('<li>').addClass('border-bottom border-light-gray');
        const $link = ((news) => {
            const $el = $('<a>')
                .addClass('d-md-flex d-block py-3 pl-3 pr-5')
                .attr('href', news.url);
            $el.append($('<div>').addClass('mb-md-0 mb-2 infomation-date').text(dayjs(news.start_date).format('YYYY/M/D')));
            $el.append($('<div>').text(news.headline));
            return $el;
        })(news);
        $el.append($link);
        return $el;
    }

    $('#news-infomation-inview').one('inview', () => {
        $(this).addClass('d-none');
        const $section = $('#news-infomation');
        const category = 2;
        findNewsByCategory(category)
            .done((response) => {
                if (response.data.length > 0) {
                    $section.removeClass('d-none');
                }
                response.data.slice(0, 4).forEach((news) => {
                    const $row = factoryNewsInformationRowElement(news);
                    $section.find('.infomation ul.head').append($row);
                });
                response.data.slice(4, 8).forEach((news) => {
                    const $row = factoryNewsInformationRowElement(news);
                    $section.find('.infomation ul.more').append($row);
                });
            });
    });
});
