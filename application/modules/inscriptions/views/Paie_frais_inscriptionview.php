<?php include VIEWPATH . 'includes/head.php'; ?>


<div class="wrapper">
<?php include VIEWPATH . 'includes/navbar.php'; ?>
<?php include VIEWPATH . 'includes/sidebar.php'; ?>
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-0">
          <div class="col-sm-6">
            <h3 class="m-0">Frais d'inscription</h3>
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
            <h3 class="card-title" style="margin: 0;">Liste des élèves qui ont payé les frais d'inscription</h3>
            <button type="button" class="btn btn-primary" style="margin-left: auto;" onclick="new_paie()">Payer</button>
        </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Identification</th>
                    <th>Cycle</th>
                    <th>Classe</th>
                    <th>A/S</th>
                    <th>Montant</th>
                    <th>Par</th>
                    <th>Date&nbsp;de paiement</th>
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

<script type="text/javascript">
function new_paie() {
    save_method = 'add';  
    $('#inscription_form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#inscription_modal').modal('show');
    $('#btnSave').text('Valider');
    $('.modal-title').text('Nouveau paiement');
}

$(document).ready(function () {
      liste();
    });

    function liste() {

        var url = "<?= base_url() ?>inscriptions/Paie_frais_inscription/liste/";
        
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

function send() {
        $('.help-block').empty();
        $('#btnSave').html('<button class="btn btn-info" type="button" disabled><span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Chargement...</button>');
        $('#btnSave').attr("disabled", true);

        let formData = new FormData($('#inscription_form')[0]);

        $.ajax({
            url: "<?php echo base_url('index.php/inscriptions/Paie_frais_inscription/payer') ?>",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (data) {
                if (data.status) {
                    liste();
                    toast('<b style="color: green">Opération</b>', 'effectuée avec succès!', 'success');
                    $('#inscription_modal').modal('hide');
                } else {
                    for (let i = 0; i < data.inputerror.length; i++) {
                        $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                        $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                    }
                    toast('<b style="color: red">Attention</b>', 'Veuillez remplir tous les champs', 'error');
                }
                $('#btnSave').text('Valider');
                $('#btnSave').attr('disabled', false);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toast('Attention', 'Erreur s\'est produite', 'error');
                $('#btnSave').text('Valider');
                $('#btnSave').attr('disabled', false);
            }
        });
    }

</script>

<div class="modal fade" id="inscription_modal" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="inscription_form" method="POST">
                                <div class="form-body">
                                    <input type="hidden">
                                    <div class="row" id="eleve_existing">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Elève<span style="color: red;">*</span></label>
                                                <select class="form-control" name="id_eleve" id="id_eleve" onchange="displayStudentInfo(this)">
                                                    <option value="">-Choisir-</option>
                                                    <?php foreach ($etudiants as $p) { ?>
                                                        <option value="<?= $p['ID_INSCRIPTION'] ?>"><?= $p['MATRICULE'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
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
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="button" id="btnSave" onclick="send()" class="btn btn-primary">Valider</button>
                </div>
                </div>

            </div>
        </div>
    </div>