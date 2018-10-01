$(function(){
    $(document).on('click', '.scale-up', scaleUp);
    $(document).on('click', '.screen', scaleDown);
});

/**
 * 拡大
 * @param {Event} event 
 */
function scaleUp(event) {
    if (!isMobile()) {
        return;
    }
    var screen = $('.screen');
    screen.removeClass('d-none');
    var src = $(this).find('img').attr('src');
    var image = new Image();
    image.src = src;
    image.onload = function() {
        var target = screen.find('img');
        target
        .width(image.width / 1.5)
        .height(image.height / 1.5)
        .attr(image.src);
    }
}

/**
 * 縮小
 * @param {Event} event 
 */
function scaleDown(event) {
    if (!isMobile()) {
        return;
    }
    $(window).scrollTop(0);
    var screen = $('.screen');
    screen.addClass('d-none');
}