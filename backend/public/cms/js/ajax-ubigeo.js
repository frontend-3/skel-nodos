$(document).ready(init);
  var $form;
  function init () {
    var dpt   = $('#department_id'),
        prov  = $('#province_id'),
        dist  = $('#district_id');
   
    $form = $('.form-horizontal');
    dpt.on('change', loadProv);
    prov.on('change', loadDist);
  }

  function loadProv() {
    var $this = $(this),
        selectedVal = $this.val(),
        $el = $('#province_id'),
        $sub_el = $('#district_id'),
        html = '<option value="">Selecciona</option>',
        item;

    if (selectedVal === '') {
      $el.html(html)
        .attr('disabled')
    } else  {

      $el.html(html);
      $el.removeAttr('disabled');
      $el.trigger('change');
      $sub_el.html(html).attr('disabled','disabled');
      $sub_el.trigger('change');
      $.post($form.attr('data-provinces'), {
        'cod_dpto' : selectedVal
      }).done( function(data) {
        for (item in data) {
          html += '<option value="' + data[item].id + '">' + data[item].name + '</option>';
        }
        $el.html(html);
      });  
    }
  };

  function loadDist() {
    var $el = $('#district_id'),
        $dept = $('#department_id'),
        $this = $(this),
        html = '<option value="">Selecciona</option>',
        selectedVal = $this.val(),
        item;

    if (selectedVal === '') {
      $el.html(html);
      $el.attr('disabled','disabled');
      $el.parent().addClass('ci-select-disabled');
      $el.trigger('change');
    } else {
      $el.removeAttr('disabled');
      $el.parent().removeClass('ci-select-disabled');
      $el.trigger('change');
      $.post($form.attr('data-districts'), {
        'cod_dpto' : $dept.val(),
        'cod_prov' : $this.val(),
      }).done(function (data) {
        for (item in data) {
          html += '<option value="' + data[item].id + '">' + data[item].name + '</option>';
        }
        $el.html(html);
      });
    }
  };