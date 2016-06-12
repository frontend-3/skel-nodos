define(function() {
  function customInput(input) {
    this.el = $(input);

    if (this.el.attr('type') === 'checkbox') {
      prepare(this, 'checkbox');
    }

    return this;
  };

  function prepare(obj, type) {
    var tpl = {
      checkbox: 'ci-check',
      text: 'ci-text',
    }[type];

    if (!tpl) { return; }

    tpl = $('<label></label>').attr('class', tpl);

    tpl.attr({
      'for': obj.el.attr('id')
    });

    tpl.addClass(type + '-' + obj.el.attr('name'));

    obj.el.wrap(tpl);
    obj.content = obj.el.parent();
    

    if (type === 'checkbox') {
      checkboxChecked(obj.content, { type: type, input: obj.el });
    }

    obj.content.on('change', { type: type, input: obj.el }, interaction);
  };

  function interaction(e) {

    var el = $(e.currentTarget);
    if (e.data.type === 'checkbox') {
      checkboxChecked(el, e.data);
    }
    ;
  }

  function checkboxChecked(el, data) {
    if (data.input.is(':checked')) {
      el.addClass('checkbox-checked');
    }
    else {
      el.removeClass('checkbox-checked');
    }
  };

  return customInput;
});
