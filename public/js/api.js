/**
 * API
 */
var api;

api = {};
api.schedule = {};

/**
 * fetch schedule list
 * 
 * @param {String} theater
 * @returns {jqXHR}
 */
api.schedule.list = function (theater) {
    return $.ajax({
        url: '/api/schedule/' + theater,
        cache: false,
        dataType: 'json'
    });
};

/**
 * fetch schedule for date
 * 
 * @param {String} theater
 * @param {String} date    YYYY-MM-DD
 * @returns {jqXHR}
 */
api.schedule.date = function (theater, date) {
    return $.ajax({
        url: '/api/schedule/' + theater + '/' + date,
        cache: false,
        dataType: 'json'
    });
};