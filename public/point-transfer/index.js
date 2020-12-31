init();
$('#start').on('click', start);

function init() {
    var theaters = [
        { "code": "20", "name": "グランドシネマサンシャイン" },
        { "code": "02", "name": "シネマサンシャイン平和島" },
        { "code": "19", "name": "シネマサンシャインユーカリが丘" },
        { "code": "13", "name": "シネマサンシャイン土浦" },
        { "code": "06", "name": "シネマサンシャイン沼津" },
        { "code": "21", "name": "シネマサンシャインららぽーと沼津" },
        { "code": "14", "name": "シネマサンシャインかほく" },
        { "code": "16", "name": "シネマサンシャイン大和郡山" },
        { "code": "17", "name": "シネマサンシャイン下関" },
        { "code": "07", "name": "シネマサンシャイン大街道" },
        { "code": "08", "name": "シネマサンシャイン衣山" },
        { "code": "09", "name": "シネマサンシャイン重信" },
        { "code": "15", "name": "シネマサンシャインエミフルMASAKI" },
        { "code": "12", "name": "シネマサンシャイン北島" },
        { "code": "18", "name": "シネマサンシャイン姶良" }
    ];
    var optionDom = [];
    theaters.forEach(function (t) {
        optionDom.push('<option value="' + t.code + '">' + t.name + '</option>');
    });
    $('#theater').append(optionDom);
}


function start() {
    // var isProduction = /cinemasunshine.co.jp/.test(location.href);
    var code = '0' + $('#theater').val();
    var projectId = 'sskts-production';
    var endpoint = 'https://reserve.smart-theater.com';
    var url = endpoint + '/projects/' + projectId + '/order/money/' + code + '/transfer'
    location.href = url;
}