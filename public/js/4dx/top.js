$(function () {
    $(document).on('click', '.theater-select li a', selectTheater);
});

/**
 * 劇場選択
 * @param {Event} event
 */
function selectTheater(event) {
    event.preventDefault();
    $('.theater-select .active')
        .removeClass('active')
        .find('a')
        .removeClass('text-red border-red')
        .addClass('text-gray border-gray');
    var parent = $(this).parent();
    parent
        .addClass('active')
        .find('a')
        .removeClass('text-gray border-gray')
        .addClass('text-red border-red');
    var theaterCode = $(this).attr('data-theaterCode');
    $('.screening, .scheduled')
        .removeClass('d-flex')
        .addClass('d-none');
    $('.screening[data-theaterCode='+ theaterCode +'], .scheduled[data-theaterCode='+ theaterCode +']')
        .removeClass('d-none')
        .addClass('d-flex');
}   