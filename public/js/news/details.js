$(function () {
    setShare();
});

/**
 * シェアボタン設定
 */
function setShare() {
    var url = location.href;
    var title = $('#shareTitle').text();
    var image = $('#shareImage').attr('src').replace('/images', location.origin + '/images');
    var description = title + image;
    var twitterUrl = 'https://twitter.com/share?url=' + url + '&text=' + encodeURIComponent(description);
    var facebookUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + url + '&t=' + encodeURIComponent(description);
    var lineUrl = 'http://line.me/R/msg/text/?' + encodeURIComponent(description + ' ' + url);
    $('head').append('\
    <meta property="og:title" content="' + title + '">\
    <meta property="og:type" content="website">\
    <meta property="og:url" content="'+ url + '">\
    <meta property="og:image" content="'+ image + '">\
    ');
    $('.twitter-share').attr('href', twitterUrl);
    $('.fb-share').attr('href', facebookUrl);
    $('.line-share').attr('href', lineUrl);
}
