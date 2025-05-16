<!DOCTYPE html>
<html lang="en" data-layout-mode="light_mode" data-layout-style="box" data-nav-color="light">


<?php include VIEWPATH . 'includes/head.php'; ?>
<div class="wrapper">
<?php include VIEWPATH . 'includes/navbar.php'; ?>
<?php include VIEWPATH . 'includes/sidebar.php'; ?>
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-0">
          <div class="col-sm-6">
            <h3 class="m-0">Profils</h3>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- /.row -->
        <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title" style="margin: 0;">Liste des profils</h3>
            <button type="button" class="btn btn-primary" style="margin-left: auto;" onclick="new_ut()">Ajouter</button>
        </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Description</th>
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
</html>

<div class="modal fade" id="user_modal" data-backdrop="static" >
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="users_form" method="POST">
                            <div class="form-body">
                                <input type="hidden" name="ID_PROFIL" id="ID_PROFIL">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Description<span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="nom" id="nom">
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

        var url = "<?= base_url() ?>administrations/Profil/liste/";
        
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
$('.modal-title').text('Nouveau profil');
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
      url = "<?php echo base_url('index.php/administrations/Profil/add') ?>";
      successMessage = "Enregistrement effectué avec succès!";
    } else {
      url = "<?php echo base_url('index.php/administrations/Profil/update') ?>";
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
          $('#users_form')[0].reset();
          toast('<b style="color: green">Opération</b>', message_success, 'success');
          $('#user_modal').modal('hide');

        } else {
          for (var i = 0; i < data.inputerror.length; i++) {
            $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
          }
          toast('<b style="color: red">Attention</b>', danger_message, 'error');

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

<script>
    function edit_profil(id) {
    save_method = 'update';
    $('#users_form')[0].reset();
    $('.help-block').empty();
    $('#btnSave').attr('disabled', false);

    $.ajax({
      url: "<?php echo site_url('administrations/Profil/getOne') ?>/" + id,
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        $('[name="ID_PROFIL"]').val(data.ID_PROFIL);
        $('[name="nom"]').val(data.DESCRIPTION);


        $('#user_modal').modal('show');
        $('.modal-title').text('Modification du profil');
        $('#btnSave').text('Modifier');
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Erreur lors de la modification');
      }
    });
  }
</script>

<!-- Modal de confirmation -->
<div class="modal fade" id="confirmatModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="id"> 
                <p id="modalMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal" aria-label="Close">Annuler</button>
                <button type="button" class="btn btn-primary"  id="confChangeStatus" >Confirmer</button>
            </div>
        </div>
    </div>
</div>

<script>
    function change_status_profil(id, stat) {
        let message;
        const idField = document.getElementById('id');

        if (stat == 0) {
            message = 'Voulez-vous activer ce profil ?';
        } else {
            message = 'Voulez-vous arrêter ce profil ?';
        }
        idField.value = id; 

        document.getElementById('modalMessage').innerText = message;
  

        $('#confirmatModal').modal('show');

        $('#confChangeStatus').off('click').on('click', function() {

            $.ajax({
                url: "<?php echo base_url('index.php/administrations/Profil/change_status_profil')?>/" + id + '/' + stat,
                type: "POST",
                dataType: "JSON",
                data: {
                  
                },
                success: function(data) {
                    $('#confirmatModal').modal('hide');
                    liste(); 
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Erreur lors du changement de statut');
                }
            });
        });
    }
</script>
