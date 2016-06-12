define([
  //'datepicker',
  'validate-ext'
], function() {

  function setupRulesRegisterForm(form) {
    var $form = form;

    $.each($('input[date]'), function(index) {
      var minValue = $(this).attr('data-rule-mindate').split('/'),
        maxValue = $(this).attr('data-rule-maxdate').split('/'),
        xMinDate = new Date(minValue[2], minValue[1] - 1, minValue[0]),
        xMaxDate = new Date(maxValue[2], maxValue[1] - 1, maxValue[0]);

      $(this).datepicker({
        dateFormat: 'dd/mm/yy',
        minDate: xMinDate,
        maxDate: xMaxDate
      });
    });


    $('input.number').numeric();
    $('input[date]').numeric({
      allow: '/'
    });

    $form.validate({
      errorClass: 'ElementForm-label--error'
    });
  };

  function loadProv() {
    var $this = $(this),
      selectedVal = $this.val(),
      $el = $('#cod_prov'),
      $sub_el = $('#cod_dist'),
      html = '<option class="ElementForm-selectOption" value="">Selecciona</option>',
      item;

    if (selectedVal === '') {
      $el.html(html)
        .attr('disabled')
        .parent().addClass('is-disabled')
    } else {

      $el.html(html);
      $el.removeAttr('disabled');
      $el.parent().removeClass('is-disabled');
      $el.trigger('change');
      $sub_el.html(html).attr('disabled', 'disabled');
      $sub_el.parent().addClass('.is-disabled');
      $sub_el.trigger('change');

      $.get($('form').attr('data-source-provinces'), {
        'cod_dpto': selectedVal
      }).done(function(data) {
        for (item in data) {
          html += '<option class="ElementForm-selectOption" value="' + data[item].id + '">' + data[item].name + '</option>';
        }
        $el.html(html);
      });
    }
  };

  function loadDist() {
    var $el = $('#cod_dist'),
      $dept = $('#cod_dpto'),
      $this = $(this),
      html = '<option class="ElementForm-selectOption" value="">Selecciona</option>',
      selectedVal = $this.val(),
      item;

    if (selectedVal === '') {
      $el.html(html);
      $el.attr('disabled', 'disabled');
      $el.parent().addClass('ci-select-disabled');
      $el.trigger('change');
    } else {
      $el.removeAttr('disabled');
      $el.parent().removeClass('ci-select-disabled');
      $el.trigger('change');
      $.get($('form').attr('data-source-districts'), {
        'cod_prov': $this.val(),
        'cod_dpto': $dept.val(),
      }).done(function(data) {
        for (item in data) {
          html += '<option class="ElementForm-selectOption" value="' + data[item].id + '">' + data[item].name + '</option>';
        }
        $el.html(html);
      });
    }
  };

  function changeValSelect() {
    var _self = $(this);
    _self.parent().find('.ElementForm-selectText').html(_self.find('option:selected').html());
  };

  function parseErrors(data) {
    var keys_erros = data.message,
      error_msg = "",
      key,
      selector = "";
    for (key in keys_erros) {
      selector_id = "#" + key.split('_')[0] + "_error";
      error_msg = keys_erros[key];
      $(selector_id)
        .text(error_msg)
        .show();
    }
  };

  function trackEventSubmitForm(category, action, label) {
    ga('send', 'event', category, action, label);
  };

  function setupRegisterForm(settings) {
    var $form = $(settings.form),
      dpt = $('#cod_dpto'),
      prov = $('#cod_prov'),
      dist = $('#cod_dist');

    setupRulesRegisterForm($form);    
    $form.on('submit', {
      settings: settings
    }, onSubmitRegisterForm);
    dpt.on('change', changeValSelect);
    dpt.on('change', loadProv);
    prov.on('change', changeValSelect);
    prov.on('change', loadDist);
    dist.on('change', changeValSelect);
    dpt.val('15');
    dpt.trigger('change');
  };

  function onSubmitRegisterForm(e) {
    var $form = $(this),
      settings = e.data.settings,
      tracking = settings.tracking;

    if ($form.valid()) {
      if (settings.isAjax) {
        var payload = {};
        payload.first_name = $('#first_name').val();
        payload.last_name = $('#last_name').val();
        payload.email = $('#email').val();
        payload.dni = $('#document_number').val();
        payload.phone = $('#phone').val();
        payload.cod_dpto = $('#cod_dpto').val();
        payload.cod_prov = $('#cod_prov').val();
        payload.cod_dist = $('#cod_dist').val();
        payload.tyc = $('#tyc').val();
        payload.csrf_token = $('#csrf_token').val();
        $.post($form.attr('action'), data)
          .done(function(data) {
            if (data.status_code != 0) {
              parseErrors(data);
            } else {
              trackEventSubmitForm(tracking.category, tracking.action, tracking.label);
            }
          })
          .fail(function() {

          });
      }
      if (tracking) {
        trackEventSubmitForm(tracking.category, tracking.action, tracking.label);
      }
    } else {
      return false;
    }
  }
  return {
    setupRegisterForm: setupRegisterForm
  };

});