var $doc = $(document);

$doc.ready(init);

function init () {
  var sts   = $('.status');
  sts.on('change', updateStatus);
}

function updateStatus(){
   var $this = $(this),
        selectedVal = $this.val(),
        url_post=$('#orders').attr('data-status'),
        id_order=$this.parent().attr('data-id');
    $this.hide();
    $("#image_"+id_order).show();
    $.post(url_post, {
        'id' : id_order,
        'status' : selectedVal,
      }).done(function (data) {
        $("#image_"+id_order).hide();
        $("#parent_"+id_order).remove();
      });
}
