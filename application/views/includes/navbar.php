
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-light" style="position: fixed; width: 100%;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <div id="clock"><i class="uil-clock"></i><span id="clockvalue"></span></div>
    <ul class="navbar-nav ml-auto">

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <script>
        function updateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'short', // Abréger le mois
                day: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit', 
                hour12: false 
            };
            const dateString = now.toLocaleDateString('fr-FR', options);
            // Mettre la première lettre en majuscule
            const formattedDate = dateString.charAt(0).toUpperCase() + dateString.slice(1);
            document.getElementById('clockvalue').textContent = formattedDate;
        }

        setInterval(updateTime, 1000);
        updateTime(); // Appeler une fois pour éviter le délai
    </script>
