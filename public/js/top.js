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
});