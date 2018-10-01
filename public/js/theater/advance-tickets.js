$(function () {
    $(document).on('click', '[data-toggle=tooltip]', function (event) {
        event.preventDefault();
    });
    $('[data-toggle=tooltip]').tooltip();
});