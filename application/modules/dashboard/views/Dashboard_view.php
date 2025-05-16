
<?php include VIEWPATH . 'includes/head.php'; ?>


<div class="wrapper">
<?php include VIEWPATH . 'includes/navbar.php'; ?>
<?php include VIEWPATH . 'includes/sidebar.php'; ?>
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-0">
        <div class="col-sm-6">
            <h3 class="m-0">Dashboard</h3>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- /.row -->
        <div class="card">
              <div class="card-header d-flex justify-content-between row" >
                
            <h3 class="card-title" style="margin: 0;">Tableau de bord</h3>
            <div class="row " id="dates_cont"  >
                        <div class="col-md-4">
                            <label class="control-label">Année</label>
                            <select class="form-control"  name="annee" id="annee" onchange="updateMonthOptions()">
                                <?php 
                                    $annee_actuelle = date('Y', strtotime($actuel));
                                    foreach ($datte as $value) { 
                                        $selected = ($value['annee'] == $annee_actuelle) ? 'selected' : '';
                                ?>
                                    <option value="<?= $value['annee'] ?>" <?= $selected ?>>
                                        <?= $value['annee'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">Mois</label>
                            <select class="form-control"  name="mois" id="mois" onchange="updateDayOptions()">
                                <option value="">Sélectionner</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">Jours</label>
                            <select class="form-control"  name="jour" id="jour" onchange="updateDaySelection() ">
                                <option value="">Sélectionner</option>
                            </select>
                        </div>
                    </div>         
              <!-- /.card-header -->
              <div class="card-body">
              <div class="chart">
                  <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
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
<script src="<?=base_url()?>plugins/chart.js/Chart.min.js"></script>

<script>
function initialiserChartDynamic(series, dates) {
    var areaChartCanvas = $('#areaChart').get(0).getContext('2d');

    var areaChartData = {
        labels: dates, 
        datasets: [
            {
                label: 'Montant',
                backgroundColor: 'rgba(60,141,188,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                pointRadius: 5, 
                pointBackgroundColor: '#ffffff', 
                pointBorderColor: '#3b8bba', 
                pointHoverRadius: 8, 
                pointHoverBackgroundColor: '#3b8bba',
                pointHoverBorderColor: '#ffffff',
                data: series 
            },
        ]
    };

    var areaChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
            display: false 
        },
        scales: {
            xAxes: [{
                gridLines: {
                    display: false,
                },
                ticks: {
                    fontColor: '#666', 
                }
            }],
            yAxes: [{
                gridLines: {
                    display: true,
                },
                ticks: {
                    callback: function(value) {
                        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' '); 
                    },
                    fontColor: '#666' 
                }
            }]
        },
        tooltips: {
            enabled: true,
            mode: 'index',
            intersect: false,
            backgroundColor: 'rgba(50, 50, 50, 0.9)', 
            titleFontColor: '#ffffff', 
            titleFontSize: 14, 
            bodyFontColor: '#ffffff', 
            bodyFontSize: 16, 
            bodySpacing: 8, 
            xPadding: 15, 
            yPadding: 15, 
            borderColor: '#3b8bba', 
            borderWidth: 1,
            cornerRadius: 8, 
            callbacks: {
                label: function(tooltipItem, data) {
                    var value = tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                    return `Montant : ${value} `; 
                },
                title: function(tooltipItems, data) {
                    return `📅 Date : ${tooltipItems[0].label}`; 
                }
            }
        }
    };

    if (window.myChartInstance) {
        window.myChartInstance.destroy(); 
    }

    window.myChartInstance = new Chart(areaChartCanvas, {
        type: 'line',
        data: areaChartData,
        options: areaChartOptions
    });
}


function updateChartDataMontant() {
    var annee = document.getElementById('annee').value;
    var mois = document.getElementById('mois').value;
    var jour = document.getElementById('jour').value;

    $.ajax({
        url: 'dashboard/Dashboard/getmontant',
        method: 'POST',
        data: { annee: annee, mois: mois, jour: jour },
        success: function(data) {
            var results = JSON.parse(data);
            var series = [];
            var dates = [];

            results.forEach(result => {
                series.push(result.montant);
                dates.push(result.date);
            });

            var combinedData = dates.map((date, index) => {
                return {
                    date: date,
                    montant: series[index]
                };
            });

            combinedData.sort((a, b) => {
                var partsA = a.date.split('-');
                var partsB = b.date.split('-');
                return new Date(partsA[2], partsA[1] - 1, partsA[0]) - new Date(partsB[2], partsB[1] - 1, partsB[0]);
            });

            var sortedDates = combinedData.map(item => item.date);
            var sortedMontant = combinedData.map(item => item.montant);

            initialiserChartDynamic(sortedMontant, sortedDates);
        }
    });
}


</script>

<script>
  function updateDaySelection() {
    var annee = document.getElementById('annee').value;
    var mois = document.getElementById('mois').value;
    var jour = document.getElementById('jour').value;
    updateChartDataMontant();
}

function updateMonthOptions() {
    var annee = document.getElementById('annee').value;

    $.ajax({
        url: 'dashboard/Dashboard/getMonths',
        method: 'POST',
        data: { annee: annee },
        success: function(data) {

            var moisOptions = JSON.parse(data);
            var moisSelect = document.getElementById('mois');
            moisSelect.innerHTML = '<option value="0">Sélectionner</option>';
            moisOptions.forEach(function(mois) {
                moisSelect.innerHTML += '<option value="' + mois + '">' + mois + '</option>';
            });
            updateChartDataMontant();
        }
    });
}

function updateDayOptions() {
    var annee = document.getElementById('annee').value;
    var mois = document.getElementById('mois').value.split('-')[0];

    $.ajax({
        url: 'dashboard/Dashboard/getDays',
        method: 'POST',
        data: { annee: annee, mois: mois },
        success: function(data) {
            var jourOptions = JSON.parse(data);
            var jourSelect = document.getElementById('jour');
            jourSelect.innerHTML = '<option value="0">-Sélectionner-</option>';
            jourOptions.forEach(function(jour) {
                jourSelect.innerHTML += '<option value="' + jour + '">' + jour + '</option>';
            });
            updateChartDataMontant();
        }
    });
}

document.getElementById('mois').addEventListener('change', function() {
    updateDayOptions();
});

document.getElementById('annee').addEventListener('change', function() {
    updateMonthOptions();
});

document.addEventListener('DOMContentLoaded', function() {
    updateMonthOptions();
});
</script>