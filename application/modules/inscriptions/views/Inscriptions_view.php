<?php include VIEWPATH . 'includes/head.php'; ?>
<style>
        .radio-inline {
            display: inline-block;
            margin-right: 10px; 
        }

        .student-info {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    margin-top: 10px;
}

.student-detail {
    margin: 5px 0;
}

.student-detail strong {
    color: #333;
}

    </style>

<div class="wrapper">
<?php include VIEWPATH . 'includes/navbar.php'; ?>
<?php include VIEWPATH . 'includes/sidebar.php'; ?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-0">
          <div class="col-sm-6">
            <h3 class="m-0">Inscriptions</h3>
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
            <h3 class="card-title" style="margin: 0;">Liste des élèves inscripts</h3>
            <?php if ($this->session->userdata('ID_PROFIL') >=2) { ?>
            <button type="button" class="btn btn-primary" style="margin-left: auto;" onclick="new_incr()">S'inscrire</button>
            <?php } ?> 
        
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
                    <th>Par</th>
                    <th>Date&nbsp;d'incription</th>
                    <th>Statut&nbsp;élève</th>
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
function new_incr() {
    save_method = 'add';
    
    var anneeScolaire = $('#annee_scolaire').val();
    
    $('#inscription_form')[0].reset();
    
    $('#annee_scolaire').val(anneeScolaire);
    document.getElementById('student-info').style.display = 'none';
    
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#inscription_modal').modal('show');
    $('#btnSave').text('Valider');
    $('.modal-title').text('Inscrire un élève');
}

</script>

<script type="text/javascript">

    $(document).ready(function () {
      liste();
    });

    function liste() {

        var url = "<?= base_url() ?>inscriptions/Inscriptions/liste/";
        
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
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="radio-inline">
                                                <input type="radio" id="optionA" name="choix" value="A" checked>
                                                <label for="optionA">Existant</label>
                                            </div>
                                            <div class="radio-inline">
                                                <input type="radio" id="optionB" name="choix" value="B">
                                                <label for="optionB">Nouveau</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="eleve_existing">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Elève<span style="color: red;">*</span></label>
                                                <select class="form-control" name="id_eleve" id="id_eleve" onchange="displayStudentInfo(this)">
                                                    <option value="">-Choisir-</option>
                                                    <?php foreach ($etudiants as $p) { ?>
                                                        <option value="<?= $p['ID_ETUDIENT'] ?>" data-nom="<?= $p['NOM'] ?>" data-prenom="<?= $p['PRENOM'] ?>" data-date-naissance="<?= $p['DATE_NAISSANCE'] ?>" data-lieu-naissance="<?= $p['LIEU_NAISSANCE'] ?>"><?= $p['MATRICULE'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="student-info" class="student-info " style="font-size: smaller; display: none;">
                                                <p class="student-detail"><strong>Nom:</strong> <span id="student-nom"></span></p>
                                                <p class="student-detail"><strong>Prénom:</strong> <span id="student-prenom"></span></p>
                                                <p class="student-detail"><strong>Date de naissance:</strong> <span id="student-date-naissance"></span></p>
                                                <p class="student-detail"><strong>Lieu de naissance:</strong> <span id="student-lieu-naissance"></span></p>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="eleve_new" style="display:none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Nom<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="nom" id="nom">
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Prénom<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="prenom" id="prenom">
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Sexe<span style="color: red;">*</span></label>
                                                <select class="form-control" name="sexe" id="sexe">
                                                <option value="">-Choisir-</option>
                                                    <option value="M">Masculin</option>
                                                    <option value="F">Féminin</option>
                                                </select>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Date de naissance<span style="color: red;">*</span></label>
                                                <input type="date" class="form-control" name="date_naissance" id="date_naissance">
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Lieu de naissance<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="lieu_naissance" id="lieu_naissance">
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Documents<span style="color: red;">*</span></label>
                                                <input type="file" class="form-control" name="photo" id="photo">
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Adresse<span style="color: red;">*</span></label>
                                                <textarea class="form-control" name="adresse" id="adresse"></textarea>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Classe<span style="color: red;">*</span></label>
                                                <select class="form-control" name="id_classe" id="id_classe">
                                                    <option value="">-Choisir-</option>
                                                    <?php foreach ($classes as $c) { ?>
                                                    <option value="<?= $c['ID_CLASSE'] ?>"><?= $c['NOM'] ?></option>
                                                <?php } ?>

                                                </select>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Cycle<span style="color: red;">*</span></label>
                                                <select class="form-control" name="id_cycle" id="id_cycle">
                                                    <option value="">-Choisir-</option>
                                                    <?php foreach ($cycles as $c) { ?>
                                                    <option value="<?= $c['ID_CYCLES'] ?>"><?= $c['DESCRIPTION'] ?></option>
                                                <?php } ?>
                                                </select>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label"> Statut Elève<span style="color: red;">*</span></label>
                                                <select class="form-control" name="id_statut_eleve" id="id_statut_eleve">
                                                    <option value="">-Choisir-</option>
                                                    <?php foreach ($statut as $c) { ?>
                                                    <option value="<?= $c['ID_STATUT_ELEVE'] ?>"><?= $c['DESCRIPTION'] ?></option>
                                                <?php } ?>

                                                </select>
                                                <span class="help-block" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Année Scolaire<span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="annee_scolaire" id="annee_scolaire">
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
function displayStudentInfo(select) {
    var selectedOption = select.options[select.selectedIndex];
    var nom = selectedOption.getAttribute('data-nom');
    var prenom = selectedOption.getAttribute('data-prenom');
    var dateNaissance = selectedOption.getAttribute('data-date-naissance');
    var lieuNaissance = selectedOption.getAttribute('data-lieu-naissance');

    if (select.value) {
        document.getElementById('student-nom').textContent = nom;
        document.getElementById('student-prenom').textContent = prenom;
        document.getElementById('student-date-naissance').textContent = dateNaissance;
        document.getElementById('student-lieu-naissance').textContent = lieuNaissance;
        document.getElementById('student-info').style.display = 'block';
    } else {
        document.getElementById('student-info').style.display = 'none';
    }
}

</script>

    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const optionA = document.getElementById('optionA');
        const optionB = document.getElementById('optionB');
        const eleveExisting = document.getElementById('eleve_existing');
        const eleveNew = document.getElementById('eleve_new');

        var date = new Date();
        var anneeActuelle = date.getFullYear();
        var anneeProchaine = anneeActuelle + 1;
        var anneeScolaire = anneeActuelle + "-" + anneeProchaine;
        document.getElementById("annee_scolaire").value = anneeScolaire;

        var anneeMinimum = anneeActuelle - 3;
        
        var dateMax = new Date(anneeMinimum, 11, 31); 
        
        var dateMaxStr = dateMax.toISOString().split('T')[0];

        var dateInput = document.getElementById("date_naissance");
        dateInput.setAttribute("max", dateMaxStr);
    
        
        optionA.addEventListener('change', function () {
            if (optionA.checked) {
                eleveExisting.style.display = 'flex';
                eleveNew.style.display = 'none';
            }
        });
        
        optionB.addEventListener('change', function () {
            if (optionB.checked) {
                eleveExisting.style.display = 'none';
                eleveNew.style.display = 'block';
            }
        });
        
        if (optionA.checked) {
            eleveExisting.style.display = 'flex';
            eleveNew.style.display = 'none';
        } else {
            eleveExisting.style.display = 'none';
            eleveNew.style.display = 'block';
        }
    });

    function send() {
        $('.help-block').empty();
        $('#btnSave').html('<button class="btn btn-info" type="button" disabled><span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span>Chargement...</button>');
        $('#btnSave').attr("disabled", true);

        let formData = new FormData($('#inscription_form')[0]);
        formData.append('choix', document.querySelector('input[name="choix"]:checked').value);

        $.ajax({
            url: "<?php echo base_url('index.php/inscriptions/Inscriptions/inscrire') ?>",
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

