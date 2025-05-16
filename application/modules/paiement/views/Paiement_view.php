<?php include VIEWPATH . 'includes/head.php'; ?>

<div class="wrapper">
<?php include VIEWPATH . 'includes/navbar.php'; ?>
<?php include VIEWPATH . 'includes/sidebar.php'; ?>
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-0">
          <div class="col-sm-6">
            <h3 class="m-0"> Paiement</h3>
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
            <h3 class="card-title" style="margin: 0;">Liste des élèves qui ont payé</h3>
            <?php if ($this->session->userdata('ID_PROFIL') ==3) { ?>
            <button type="button" class="btn btn-primary" style="margin-left: auto;" onclick="new_paiement()">Payer</button>
            <?php } ?> 
        </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Identification</th>
                    <th>No.du reçu</th>
                    <th>Montant</th>
                    <th>Banque</th>
                    <th>No.bordereau</th>
                    <th>Imprimer</th>
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

<script>
    function new_paiement() {
    save_method = 'add';  
    $('#paiement_form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#paiement_modal').modal('show');
    $('#btnSave').text('Valider');
    $('.modal-title').text('Nouveau paiement');
}
</script>

<div class="modal fade" id="paiement_modal" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="paiement_form" method="POST">
                                <div class="form-body">
                                    <input type="hidden">
                                    <div class="row" id="eleve_existing">
                                    <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Elève<span style="color: red;">*</span></label>
                                                <select class="form-control" name="id_eleve" id="id_eleve" onchange="fetchStudentInfo(this.value)">
                                                    <option value="">-Choisir-</option>
                                                    <?php foreach ($etudiants as $p) { ?>
                                                        <option value="<?= $p['ID_INSCRIPTION'] ?>"><?= $p['MATRICULE'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <div class="form-group">
                                                <label class="control-label">Nom<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="nom" id="nom" disabled>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <div class="form-group">
                                                <label class="control-label">Prénom<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="prenom" id="prenom" disabled>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <div class="form-group">
                                                <label class="control-label">Date de naissance<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="date_naissance" id="date_naissance" disabled>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <div class="form-group">
                                                <label class="control-label">Cycle<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="id_cycle" id="id_cycle" disabled>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <div class="form-group">
                                                <label class="control-label">Section<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="id_section" id="id_section" disabled>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <div class="form-group">
                                                <label class="control-label">Classe<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="id_classe" id="id_classe" disabled>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <div class="form-group">
                                                <label class="control-label">Nombre de tranches<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="nbre_tranche" id="nbre_tranche" disabled>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <div class="form-group">
                                                <label class="control-label">Montant/tranche<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="montant_tranche" id="montant_tranche" disabled>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <div class="form-group">
                                                <label class="control-label">Montant à payer<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="montant_apayer" id="montant_apayer" disabled>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <div class="form-group">
                                                <label class="control-label">Montant payé<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="montant_payer" id="montant_payer" oninput="this.value = this.value.replace(/[^0-9]/g,'');">
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <div class="form-group">
                                                <label class="control-label">Montant restant<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="montant_restant" id="montant_restant" disabled>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Banque<span style="color: red;">*</span></label>
                                                <select class="form-control" name="id_banque" id="id_banque" >
                                                    <option value="">-Choisir-</option>
                                                    <?php foreach ($banque as $b) { ?>
                                                        <option value="<?= $b['ID_BANQUE'] ?>"><?= $b['NOM'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                                <label class="control-label">Numéro de bordereau<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="numero_banque" id="numero_banque">
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

    <script>



function fetchStudentInfo(id_inscription) {
    if (id_inscription) {
        $.ajax({
            url: '<?= base_url('paiement/Paiement/fetchStudentInfo') ?>', 
            type: 'POST',
            data: { id_inscription: id_inscription },
            dataType: 'json',
            success: function(data) {
                if (data) {
                    $('#id_cycle').val(data.cycle);
                    $('#id_section').val(data.section);
                    $('#id_classe').val(data.classe);
                    $('#nbre_tranche').val(data.nbre_tranche);
                    $('#montant_tranche').val(data.montant_tranche);
                    $('#montant_apayer').val(data.montant_apayer);
                    $('#montant_restant').val(data.montant_restant);
                    $('#nom').val(data.nom);
                    $('#prenom').val(data.prenom);
                    $('#date_naissance').val(data.date_naissance);

                    $('#montant_payer').on('input', function() {
                        var montant_payer = parseFloat($(this).val());
                        var montant_apayer = parseFloat(data.montant_apayer);
                        var montant_restant = data.montant_restant;

                        if (!isNaN(montant_payer)) {
                            montant_restant = data.montant_restant - montant_payer;
                        }

                        if (montant_payer > data.montant_restant) {
                            toast('<b style="color: red">Attention</b>', 'Le montant payé ne peut pas dépasser le montant restant !', 'error');
                        } else {
                            $('#montant_restant').val(montant_restant);
                        }
                    });
                }
            }
        });
    } else {
        // Vider tous les champs si l'option "Choisir" est sélectionnée
        $('#id_cycle').val('');
        $('#id_section').val('');
        $('#id_classe').val('');
        $('#nbre_tranche').val('');
        $('#montant_tranche').val('');
        $('#montant_apayer').val('');
        $('#montant_restant').val('');
        $('#nom').val('');
        $('#prenom').val('');
        $('#date_naissance').val('');
        $('#montant_payer').val('');
    }
}
function send() {
    $('.help-block').empty();
    $('#btnSave').html('<button class="btn btn-info" type="button" disabled><span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Chargement...</button>');
    $('#btnSave').attr("disabled", true);

    // Vérification de la connexion Internet
    if (!navigator.onLine) {
        toast('Attention', 'Connexion Internet instable. Veuillez réessayer.', 'error');
        $('#btnSave').text('Valider');
        $('#btnSave').attr('disabled', false);
        return; 
    }

    let formData = new FormData($('#paiement_form')[0]);

    $.ajax({
        url: "<?php echo base_url('index.php/paiement/Paiement/payer') ?>",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (data) {
            if (data.status) {
                toast('<b style="color: green">Opération</b>', 'effectuée avec succès!', 'success');
                $('#paiement_modal').modal('hide');
                liste();
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

<script type="text/javascript">

$(document).ready(function () {
  liste();
});

function liste() {

    var url = "<?= base_url() ?>paiement/Paiement/liste/";
    
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
function Export_recus(facture_id) {
    const encrypted_id = encodeURIComponent(btoa(facture_id)); 
    windowObjectReference = window.open(
        "<?= base_url() ?>recus/" + encrypted_id,
        "DescriptiveWindowName",
        "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1000,height=3000"
    );
}
</script>