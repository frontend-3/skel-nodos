var $doc = $(document);

$doc.ready(init);

function init () {
  var months       = [],
      full_months  = [],
      users        = [],
      wrapRegister = $('#charts-register'),
      wrapAwards   = $('#charts-awards');

  $.getJSON(wrapRegister.attr('data-json'))
    .done(function (data) {
      for(var incremento in data){
        var currentData = data[incremento];
        months.push(currentData.month.substr(0,3));
        full_months.push(currentData.month);
        users.push(parseInt(currentData.users));
      }

      var chartUsersRegistered = {
        months: months,
        full_month: full_months,
        users: users
      }

      loadDataUsersRegistereds(chartUsersRegistered);
    });

  $.getJSON(wrapAwards.attr('data-json'))
    .done(function (data) {
      var code    = [],
          name    = [],
          stock   = [];

      for(var incremento in data){
        var currentData = data[incremento];
        code.push(currentData.code);
        name.push(currentData.name);
        stock.push(parseInt(currentData.stock));
      }

      var chartAwardsStock = {
        code: code,
        name: name,
        stock: stock
      }

      loadDataAwardsStock(chartAwardsStock);
    });

}

function loadDataUsersRegistereds (dataObject) {
  $('#charts-register').highcharts({
    title: {
        text: 'Usuarios registrados los últimos 12 meses.',
        x: -20 //center
    },
    xAxis: {
        categories: dataObject.months
    },
    yAxis: {
        title: {
            text: '# Usuarios Registrados'
        },
        plotLines: [{
            value: 0,
            width: 1,
            color: '#808080'
        }]
    },
    tooltip: {
        valueSuffix: 'Personas registradas'
    },
    legend: {
      enabled: false
    },
    series: [{
        name: 'Nº',
        data: dataObject.users
    }]
  });

  for(var incremento in dataObject.full_month){
    var $tr = $('<tr></tr>');
    $tr.append('<td>' + dataObject.full_month[incremento] + '</td>')
    $tr.append('<td>' + dataObject.users[incremento] + '</td>')
    $('#charts-register-table').append($tr);
  }


}


function loadDataAwardsStock (dataObject) {
  $('#charts-awards').highcharts({
    chart: {
        type: 'column'
    },
    title: {
        text: 'Stock de premios'
    },
    xAxis: {
        categories: dataObject.code
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
        data: dataObject.stock
    }]
  });

  for(var incremento in dataObject.name){
    var $tr = $('<tr></tr>');
    $tr.append('<td>' + dataObject.code[incremento] + '</td>')
    $tr.append('<td>' + dataObject.name[incremento] + '</td>')
    $tr.append('<td>' + dataObject.stock[incremento] + '</td>')
    $('#charts-awards-table').append($tr);
  }  
}