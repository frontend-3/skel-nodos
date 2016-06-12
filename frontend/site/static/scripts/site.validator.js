require([
  'jquery',
  'floatedLabel',
  'autotab',
  'alphanumeric',
  'validate'
    ], function ($, floated, autotab, alphanumeric, validate) {

    var $doc = $(document);

    function setupForm() {
      var $form = $('form');
      $('input').floatlabel({
        slideInput: false,
        labelStartTop: '5px',
        labelEndTop:'5px',
        inputPaddingTop:'5px',
        paddingOffset:'0'
      });
      $('.number').autotab('number');
      $('.number').numeric();

      $form.validate();

      $('#day').rules("add", {
        required: true,
        messages: {
          required: ""
        }
      });

      $('#month').rules("add", {
        required: true,
        messages: {
          required: "",
        }
      });

      $("#year").rules("add", {
        required: true,
        messages: {
          required: "",
        }
      });

      $form.on('submit',submitValidator);
    };

    function submitValidator () {
      var day = $('#day').val(),
          month = $('#month').val(),
          year = $('#year').val(),
          date = new Date(year,month-1,day);

      if (date.getFullYear() == year && date.getMonth() + 1 == month && date.getDate() == day) {
        if (date.getFullYear() >= 1900 && date.getFullYear() < 1997){
          ga('send','event', 'Web-Contenido', 'Confirmar-Edad');
          return true;
        }
      }
      $('input').addClass("error");
      return false;
    }

    function init() {
      setupForm();
    };

        $doc.ready(init);
});