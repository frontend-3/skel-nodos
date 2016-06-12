define([
  'datepicker',
  'validateExt',
  'alphanumeric'
], function() {

  function setupEasyForm() {
    var $form = $('form');

    $form.validate({
      errorClass: 'ElementForm-label--error'
    });

    $.each($('input.date'), function(index) {
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

    $.each($('input[type="password"]'), function() {
      var $this = $(this),
        $reference_confirm = $($this.attr('data-reference-confirm')) || '';
      if ($reference_confirm.length > 0) {       
        $reference_confirm.rules("add", {
          equalTo: '#' + $(this).attr('id')
        });
      }
    });

    $('input.number').numeric();
    $('input.date').numeric({
      allow: '/'
    });

    $('select').on('change', changeValSelect);
    $('input[type="checkbox"]').each(function() {
      var $this = $(this);
      customCheckBox($this);
    });
    setTimeout(function() {
      $('select').each(function() {
        var $this = $(this);
        $this.trigger('change');
      });
    }, 500);

    $form.each(function() {
      var $this = $(this),
        $submit_button = $this.find('button[type="submit"]');
      if(!$this.data('custom-form')) {
        if ($submit_button.length > 0) {
          $submit_button.on("click", {
              form: $this
            },
            onSubmitRegisterForm
          );
        }
      }
    });
  };

  function onSubmitRegisterForm(e) {
    e.preventDefault();
    var $form = e.data.form,
      isAjax = $form.data('is-ajax') || false,
      tracking_category = $form.data('tracking-category'),
      tracking_action = $form.data('tracking-action'),
      tracking_label = $form.data('tracking-label');

    if ($form.valid()) {

      if (isAjax) {
        $.post(
          $form.attr('action'),
          $form.serialize()
        ).done(function(data) {

          if (data.status_code != 0) {
            parseErrors(data);
          } else {
            trackEventSubmitForm(tracking_category, tracking_action, tracking_label);

          }

        }).fail(function() {

        });
        if (tracking_category) {
          trackEventSubmitForm(
            tracking_category,
            tracking_action,
            tracking_label
          );
        }

      } else {
        if (tracking_category) {
          trackEventSubmitForm(
            tracking_category,
            tracking_action,
            tracking_label
          );
        }
        $form.submit();
      }
    }
  }

  function trackEventSubmitForm(category, action, label) {
    ga('send', 'event', category, action, label);
  };

  function changeValSelect() {
    var _self = $(this);
    _self.parent().find('.ElementForm-selectText').html(_self.find('option:selected').html());
  };

  function customCheckBox(el) {
    var parent = el.parents('.ElementForm');
    parent.find('.ElementForm-contentCheckBox').on('click', changeState);
    parent.find('label').on('click', changeState);
  }

  function changeState(e) {
    var $this = $(this),
      content = $this.parents('.ElementForm'),
      input = content.find('input');


    if (content.hasClass('is-checked')) {
      content.find('.ElementForm-contentCheckBox').removeClass('is-checked');
      content.removeClass('is-checked');
      input.removeAttr('checked');
    } else {
      content.find('.ElementForm-contentCheckBox').addClass('is-checked');
      content.addClass('is-checked');
      input.attr('checked', true);
    }

    return false;
  }

  return {
    setup: setupEasyForm
  };

});