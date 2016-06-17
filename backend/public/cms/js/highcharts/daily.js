/**
 * Created by Flash on 17/12/14.
 */

var $doc = $(document);

$doc.ready(init);

function init () {
    var days       = [],
        users        = [],
        data = [],
        dataRegister = $('#users');

    data = JSON.parse(dataRegister.attr('data-json'));
    for(var incremento in data){
        var currentData = data[incremento];
        days.push(currentData.register);
        users.push(currentData.users);
    }
    console.log(users);

    var chartUsersRegistered = {
        days: days,
        users: users
    }
    loadDataUsersRegistereds(chartUsersRegistered);

}

function loadDataUsersRegistereds (dataObject) {
    $('#charts-register').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Stock de premios'
        },
        xAxis: {
            categories: dataObject.days
        },
        yAxis: {
            min: 0,
            title: {
                text: '# Stock'
            }
        },
        tooltip: {
            pointFormat: '{point.y} Und',
            valuePrefix: '',
            valueSuffix: ''
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        legend: {
            enabled: false
        },
        series: [{
            name: 'Stock',
            data: dataObject.users
        }]
    });

}


