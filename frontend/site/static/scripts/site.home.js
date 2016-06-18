require([
  'fb',
  'tw',
  'jquery'
  ], function (fb, tw, $) {
    var dom = {};
    var initialize;
    var catchDom;
    var afterCatchDom;
    var subscribeEvents;
    var events;
    var fn;
    var st;

    catchDom = function() {
      dom.document = $(document);
    }

    afterCatchDom = function() {
      fn.parseSocialElements();
    }

    subscribeEvents = function() {
    }

    events = {
    }

    fn = {
      parseSocialElements: function() {
        fb.parseEls();
        tw.parseEls();
      }
    }

    initialize = function() {
      catchDom();
      afterCatchDom();
      subscribeEvents();
    }

		$doc.ready(initialize);
});
