define([
], function() {

  function loadProv() {
    var $this = $(this),
      selectedVal = $this.val(),
      $el = $('#cod_prov'),
      $sub_el = $('#cod_dist'),
      html = '<option class="ElementForm-selectOption" value="">Selecciona</option>',
      item;

    if (selectedVal === '') {
      $el.html(html)
         .attr('disabled');

      $el.parent()
         .addClass('is-disabled');
    } else {

      $el.html(html);
      $el.removeAttr('disabled');
      $el.parent().removeClass('is-disabled');
      $el.trigger('change');
      $sub_el.html(html).attr('disabled', 'disabled');
      $sub_el.parent().addClass('is-disabled');
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
      $el.parent().addClass('is-disabled');
      $el.trigger('change');
    } else {
      $el.removeAttr('disabled');
      $el.parent().removeClass('is-disabled');
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

  function setupRegisterForm() {
    var dpt = $('#cod_dpto'),
      prov = $('#cod_prov'),
      dist = $('#cod_dist');   
    
    if (prov.length > 0) {
      dpt.on('change', loadProv);
      prov.on('change', loadDist);
    }    
  };

  return {
    setupRegisterForm: setupRegisterForm
  };

});