$(function () {
    $(document).on('click', '.tabs a', selectTab);
    $(document).on('change', '.region-selection', function () {
        var theater = $(this).val();
        changeTheater(theater);
    });
    if (getParam('theater')) {
        var theater = getParam('theater');
        $('.region-selection').val(theater);
        changeTheater(theater);
    }
    if (getParam('showType') === '1') {
        $('.tabs a').trigger('click');
    }
});

/**
 * 劇場選択
 */
function changeTheater(theater) {
    var theaterCode = theater;
    if (theaterCode === '') {
        $('.screening .movie, .scheduled .movie')
            .addClass('d-block')
            .removeClass('d-none');
        return;
    }
    var parents = ['.screening .movie', '.scheduled .movie'];
    parents.forEach(function (parent) {
        $(parent).each(function (index, element) {
            var target = $(element).find('a[data-theatercode=' + theaterCode + ']');
            if (target.length > 0) {
                $(element)
                    .addClass('d-block')
                    .removeClass('d-none');
            } else {
                $(element)
                    .addClass('d-none')
                    .removeClass('d-block');
            }
        });
    });
}

/**
 * タブ選択
 * @param {Event} event
 */
function selectTab(event) {
    event.preventDefault();
    var activeClass = ($('body').hasClass('type-4dx'))
        ? 'text-red border-red'
        : ($('body').hasClass('type-scx'))
            ? 'text-orange border-orange'
            : ($('body').hasClass('type-4dxwscx'))
                ? 'text-r-yellow border-r-yellow'
                : 'text-blue border-blue';
    $('.tabs .active')
        .removeClass('active')
        .find('a')
        .removeClass(activeClass)
        .addClass('text-gray border-gray');
    var parent = $(this).parent();
    parent
        .addClass('active')
        .find('a')
        .removeClass('text-gray border-gray')
        .addClass(activeClass);
    var type = $(this).attr('data-type');
    $('.screening, .scheduled')
        .removeClass('d-block')
        .addClass('d-none');
    $('.' + type)
        .removeClass('d-none')
        .addClass('d-block');
} 