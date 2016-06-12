require([
  'fb',
  'tw',
  'jquery'
  ], function (fb, tw, $) {

    var $doc = $(document);

		function init() {
      fb.parseEls();
      tw.parseEls();
      console.log('123');
    }

		$doc.ready(init);
});