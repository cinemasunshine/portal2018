$(function () {
    $(document).on('click', '.theater-select li a', selectTheater);
});

/**
 * 劇場選択
 * @param {Event} event
 */
function selectTheater(event) {
    event.preventDefault();
    $('.not-select').addClass('d-none');
    $('.titles').addClass('d-block');
    var activeClass = 'text-red border-red';
    var defaultClass = 'text-gray border-gray';
    $('.theater-select .active')
        .removeClass('active')
        .find('a')
        .removeClass(activeClass)
        .addClass(defaultClass);
    var parent = $(this).parent();
    parent
        .addClass('active')
        .find('a')
        .removeClass(defaultClass)
        .addClass(activeClass);
    var theaterCode = $(this).attr('data-theaterCode');
    $('.screening, .scheduled')
        .removeClass('d-flex')
        .addClass('d-none');
    $('.screening[data-theaterCode='+ theaterCode +'], .scheduled[data-theaterCode='+ theaterCode +']')
        .removeClass('d-none')
        .addClass('d-flex');
}
