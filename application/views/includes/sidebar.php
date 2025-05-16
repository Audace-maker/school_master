<?php

if (empty($this->session->userdata('USER_ID'))) {
    redirect(base_url());
}

$get_profil = $this->Model->getOne('profil', array('ID_PROFIL' => $this->session->userdata('ID_PROFIL')));

$id_user = $this->session->userdata('USER_ID');

$user = $this->Model->getRequeteOne('SELECT u.`ID_USERS`, u.`NOM`, u.`PRENOM`, u.`PHOTOS` FROM `users` u WHERE u.`ID_USERS`=' . $id_user);

$phto = $user['PHOTOS'];
$nom = $user['NOM'] . " " . $user['PRENOM'];

if (!empty($phto) && $phto != "" && $phto != null) {
    $bar_img_path = base_url() . $user['PHOTOS'];
    if (!file_exists('uploads/members/' . basename($phto))) {
        $bar_img_path = base_url('assets/user.png');
    }
} else {
    $bar_img_path = base_url() . 'assets/user.png';
}
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="images/logom.jpg" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">ACCESS SCHOOL</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <div class="user-image">
                    <img src="<?php echo $bar_img_path; ?>" class="img-circle elevation-2" alt="User Image">
                    <span class="status-text">Connecté</span>
                </div>
            </div>
            <div class="info">
                <a href="<?= base_url() ?>Logout" class="d-block"><?php echo $nom; ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <?php if ($this->session->userdata('ID_PROFIL') ==1) { ?>
            <li class="nav-item">
                    <a href="<?= base_url() ?>dashboard" class="nav-link" data-url="<?= base_url() ?>dashboard">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
            
                <li class="nav-item">
                    <a href="<?= base_url() ?>utilisateurs" class="nav-link" data-url="<?= base_url() ?>utilisateurs">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Utilisateurs</p>
                    </a>
                </li>
                <?php } ?>
            <?php if ($this->session->userdata('ID_PROFIL') >=2) { ?>
                <li class="nav-item">
                    <a href="<?= base_url() ?>Inscrire" class="nav-link" data-url="<?= base_url() ?>Inscrire">
                        <i class="fas fa-user-plus nav-icon"></i>
                        <p>Inscription</p>
                    </a>
                </li>
                <?php } ?>
            <?php if ($this->session->userdata('ID_PROFIL') ==3) { ?>
                <li class="nav-item">
                    <a href="<?= base_url() ?>paiement" class="nav-link" data-url="<?= base_url() ?>paiement">
                        <i class="fas fa-credit-card nav-icon"></i>
                        <p>Paiement</p>
                    </a>
                </li>
                <?php } ?> 
            <?php if ($this->session->userdata('ID_PROFIL') <=2) { ?>
                <li class="nav-item">
                    <a href="<?= base_url() ?>section" class="nav-link" data-url="<?= base_url() ?>section">
                        <i class="fas fa-th nav-icon"></i>
                        <p>Section</p>
                    </a>
                </li>
                <?php } ?> 
                <li class="nav-item">
                    <a href="<?= base_url() ?>cycle" class="nav-link" data-url="<?= base_url() ?>cycle">
                        <i class="fas fa-sync-alt nav-icon"></i>
                        <p>Cycles</p>
                    </a>
                </li>
            <?php if ($this->session->userdata('ID_PROFIL') == 2) { ?>
                <li class="nav-item">
                    <a href="<?= base_url() ?>classe" class="nav-link" data-url="<?= base_url() ?>classe">
                        <i class="fas fa-chalkboard-teacher nav-icon"></i>
                        <p>Classes</p>
                    </a>
                </li>
                <?php } ?> 
                <li class="nav-item">
                    <a href="<?= base_url() ?>banque" class="nav-link" data-url="<?= base_url() ?>banque">
                        <i class="fas fa-university nav-icon"></i>
                        <p>Banque</p>
                    </a>
                </li>
            <?php if ($this->session->userdata('ID_PROFIL') ==1) { ?>
                <li class="nav-item">
                    <a href="<?= base_url() ?>profil" class="nav-link" data-url="<?= base_url() ?>profil" >
                        <i class="fas fa-user-circle nav-icon"></i>
                        <p>Profil</p>
                    </a>
                </li>
                <?php } ?>               
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<style>
    .main-sidebar {
        background-color: #343a40; 
        padding: 10px 0;
    }

    .brand-link {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        color: #fff;
        text-decoration: none;
    }

    .brand-icon {
        font-size: 24px; 
        margin-right: 10px;
    }

    .nav-link {
        color: #c2c7d0; 
        padding: 10px 15px;
    }

    .nav-link:hover {
        background-color: #495057; 
        color: #fff; 
    }

    .nav-item.active .nav-link {
        background-color: #007bff; 
        color: #fff;
    }

    .user-panel {
        border-bottom: 1px solid #495057; 
        padding: 10px 15px;
    }

    .user-image {
        position: relative;
        display: inline-block;
    }

    .user-image img {
        border: 3px solid #28a745; 
        border-radius: 50%;
        width: 50px; 
        height: 50px;
    }

    .status-text {
        position: absolute;
        bottom: -15px; 
        left: 50%;
        transform: translateX(-50%);
        font-size: 10px;
        color: #28a745; 
        font-weight: normal; 
    }

    .user-panel .info a {
        color: #fff; 
    }
</style>

<script>
    function getActiveItem() {
        var menu = document.querySelector('.sidebar');
        const links = menu.querySelectorAll('a');
        const currentPageURL = window.location.href;

        links.forEach(link => {
            if (currentPageURL.includes(link.getAttribute('data-url'))) {
                link.classList.add('active');
                link.closest('.nav-item').classList.add('menu-open');
            } else {
                link.classList.remove('active');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', getActiveItem);
</script>