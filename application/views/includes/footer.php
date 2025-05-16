<!-- jQuery -->
<script src="<?=base_url()?>plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap -->
<script src="<?=base_url()?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?=base_url()?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url()?>dist/js/adminlte.js"></script>



<script src="<?=base_url()?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?=base_url()?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?=base_url()?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?=base_url()?>plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?=base_url()?>plugins/jszip/jszip.min.js"></script>
<script src="<?=base_url()?>plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?=base_url()?>plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?=base_url()?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?=base_url()?>plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?=base_url()?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- SweetAlert2 -->
<script src="<?=base_url()?>plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="<?=base_url()?>plugins/toastr/toastr.min.js"></script>




<script>
          $(function() {
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });

    $('.swalDefaultSuccess').click(function() {
      Toast.fire({
        icon: 'success',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
      })
    });


});
</script>






<script>
    function toast(title, message, type) {
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        Toast.fire({
            icon: type, // Le type d'icône ('success', 'error', 'warning', 'info')
            title: title,
            text: message,
            timer: 2500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
            showCloseButton: true,
            showConfirmButton: false
        });
    }
</script>


