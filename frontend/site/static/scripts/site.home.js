require([
  'fb',
  'tw',
  'jquery'
  ], function (fb, tw, $) {
    var $document = $(document);
    var dom = {};
    var initialize;
    var catchDom;
    var afterCatchDom;
    var subscribeEvents;
    var events;
    var fn;
    var st;

    st = {
    }

    catchDom = function() {
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

		$document.ready(initialize);
});
