define(["datepicker","validate-ext"],function(){function e(e){this.options=$.extend({},t,e),this.init()}var e,t;return t={padding:0,width:450,height:320,isFixed:!1,content:"This is a content example",onClose:null,onAfterShow:null,id:""},e.prototype={constructor:e,init:function(){var e=$(".Modal");e.length==0?$("body").prepend($('<div class="Overlay"></div>'),$('<div class="Modal Modal--'+this.options.id+'"></div>')):$("body").prepend($('<div class="Modal Modal--'+this.options.id+'"></div>')),this.$modal=$(".Modal--"+this.options.id),this.$overlay=$(".Overlay"),this.setContent(this.options.content),this._setupEventCloseModal()},setContent:function(e){this.$modal.html(e)},getContent:function(){return this.$modal.html()},_setupEventCloseModal:function(){var e=$("body");e.on("click",this.$modal.find(".Modal-close").selector,{obj:this},this.close),this.$overlay.on("click",{obj:this},this.close),$(document).on("keydown",{obj:this},function(e){e.keyCode==27&&e.data.obj.close(e)})},close:function(e){var t,n,r;e?(n=e.data,r=n.obj):r=this,t=r.options.onClose,r.$overlay.fadeOut(200),r.$modal.fadeOut(200),r.$modal.removeClass("is-show"),t&&t(),r.options.isFixed&&$("body").removeClass("u-noScroll")},show:function(){var e,t,n,r;this.$overlay.show(),e=$("body"),calculatedPositionLeft=parseInt((e.width()-this.options.width)/2),calculatedPositionTop=parseInt((e.height()-this.options.height)/2),topScrollbar=$(document).scrollTop(),this.$modal.removeClass("is-show"),this.options.isFixed?(e.addClass("u-noScroll"),this.$modal.css({top:calculatedPositionTop+"px",padding:this.options.padding+"px",width:this.options.width+"px",height:this.options.height+"px",left:calculatedPositionLeft+"px"})):this.$modal.css({top:topScrollbar+150+"px",padding:this.options.padding+"px",width:this.options.width+"px",height:this.options.height+"px",left:calculatedPositionLeft+"px"}),this.$modal.fadeIn(600);var i=this.options.onAfterShow;i&&i()}},e});