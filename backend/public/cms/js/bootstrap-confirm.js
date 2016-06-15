var $doc = $(document);

$doc.ready(init);

function init () {
  var $btn_delete = $('a[data-modal]');

  setupModalConfirm();

  $btn_delete.click(function () {
    var $this     = $(this), 
        $target   = $this.attr('data-target'),
        $modal    = $($this.attr('data-modal'));

    $modal.find('.btn-target').attr('href', $target);
    $modal.modal('show');
  });
}

function setupModalConfirm () {
  var box_modal = '<div class="modal fade" tabindex="-1" role="dialog" id="modal-confirm" aria-hidden="true">'+
    '<div class="modal-dialog modal-sm">'+
        '<div class="modal-content">'+
            '<div class="modal-header">'+
              '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Cerrar</span></button>'+
              '<h4 class="modal-title">Solicitud de confirmación</h4>'+
            '</div>'+
            '<div class="modal-body">'+
              '¿Está seguro de completar la acción?'+
            '</div>'+
            '<div class="modal-footer text-center">'+
              '<a class="btn btn-danger btn-target">Si</a>'+
              '<a class="btn btn-default" data-dismiss="modal">No</a>'+
            '</div>'+
        '</div>'+
      '</div>'+
    '</div>';

  $('body').append(box_modal);
}