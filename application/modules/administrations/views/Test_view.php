

<?php include VIEWPATH . 'includes/sidebar.php'; ?>
<?php include VIEWPATH . 'includes/navbar.php'; ?>
<?php include VIEWPATH . 'includes/head.php'; ?>

<div class="wrapper">

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 class="m-0">Utilisateurs</h3>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- /.row -->
        <div class="card">
              <div class="card-header">
                <h3 class="card-title">Liste des utilisateurs</h3>
                <button type="button" class="btn btn-primary"  onclick="new_ut()" style="margin-left: 865px; margin-top: -20px;">
                  Ajouter
                </button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
														<th>#</th>
														<th>Identification</th>
														<th>Contact</th>
														<th>Nom d' utilisateur </th>
														<th>Profil</th>
														<th>Adresse</th>
														<th>Statut</th>
														<th>Action</th>
													</tr>
                  </thead>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->

</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->




<!-- DataTables  & Plugins -->

<?php include VIEWPATH . 'includes/footer.php'; ?>


<div class="modal fade" id="user_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="users_form" method="POST">
                            <div class="form-body">
                                <input type="hidden" name="ID_USERS" id="ID_USERS">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Montant<span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="montant" id="montant">
                                            <span class="help-block" style="color:red"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                <button type="button" id="btnSave" onclick="send()" class="btn btn-primary">Valider</button>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">

    $(document).ready(function () {
      liste();
    });

    function liste() {

        var url = "<?= base_url() ?>administrations/Utilisateurs/liste/";
        
        $("#example1").DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
       "lengthChange": false,
        "autoWidth": false,
            "order": [[0, 'asc']],
            "ajax": {
                url: url,
                type: "POST",
                data: { },
                beforeSend: function () { }
            },
            lengthMenu: [[5, 10, 20, 50, 100, -1], [5, 10, 20, 50, 100, "All"]],
            pageLength: 5,
            buttons: [],
        });
    }

    </script>

<script type="text/javascript">
function new_ut() {
save_method = 'add';
$('#users_form')[0].reset();
$('.form-group').removeClass('has-error');
$('.help-block').empty();
$('#user_modal').modal('show');
$('#btnSave').text('Valider');
$('.modal-title').text('Paiement');
}
</script>


<script type="text/javascript">


    function send() {
    $('.help-block').empty();

    $('#btnSave').html('<button class="btn btn-info" type="button" disabled><span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Chargement...</button>');
    $('#btnSave').attr("disabled", true);


    var url;
    let message_success = "effectuée avec succès!";
        let danger_message = "Veuillez remplir tous les champs";

    if (save_method == "add") {
      url = "<?php echo base_url('index.php/administrations/Test/add') ?>";
      successMessage = "Enregistrement effectué avec succès!";
    } else {
      url = "<?php echo base_url('index.php/administrations/Test/update') ?>";
      successMessage = "Modification effectuée avec succès!";
    }

    var formData = new FormData($('#users_form')[0]);

    $.ajax({
      url: url,
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "JSON",
      success: function (data) {
        if (data.status) {
          liste();
          toast('Opération', message_success, 'success');
          $('#user_modal').modal('hide');

        } else {
          for (var i = 0; i < data.inputerror.length; i++) {
            $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
          }
          toast('Attention', danger_message, 'error');

        }

        $('#btnSave').text('Valider');
        $('#btnSave').attr('disabled', false);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        let warning_message = 'Erreur s\'est produite';
        toast('Attention', warning_message, 'error');
        $('#btnSave').text('Valider');
        $('#btnSave').attr('disabled', false);
      }
    });
  }

</script>