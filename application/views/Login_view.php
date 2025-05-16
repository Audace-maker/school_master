<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> Log in </title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url()?>dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1"><b>A c c e s s </b>S c h o o l</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">B i e n v e n u e</p>
      <center style="font-weight: 900;">
                                <div id="message_login"></div>
                            </center><br>

      <form action="<?=base_url('index.php/Login/go_submit')?>" id="login_form" method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email" id="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password" id="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox"  id="show" onclick="show_password()">
              <label for="checkbox">
                Voir mot de passe
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-12">
            <button onclick="login()" id="sign" type="button" class="btn btn-primary btn-block">Connexion</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <!-- /.social-auth-links -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?=base_url()?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?=base_url()?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url()?>dist/js/adminlte.min.js"></script>
</body>
</html>


<script type="text/javascript">


    $(document).on('success', function () {
        $('#sign').hide();
        $('#auth').hide();
        $('#redirection').show();
    });

    function login() {
        $('#sign').html('Authentification...'); //change button text
        $('#sign').attr('disabled', true); //set button disable
        $('#message_login').html('')

        var data = $('#login_form').serialize();
        $.ajax({
            url: "<?= base_url() ?>index.php/Login/check_login",
            type: "POST",
            data: data,
            dataType: "JSON",
            success: function (data) {
                if (data.status) {
                    $('#message_login').html("<center><span class='text text-success'>" + data.message + "</span></center>");
                    $('#sign').attr('disabled', true);
                    setTimeout(function () {
                        $('#login_form').submit();
                    }, 2000);

                } else {
                    $('#message_login').html("<span class='text text-danger'>" + data.message + "</span>");
                }
                $('#sign').text('S\'authentifier'); //change button text
                $('#sign').attr('disabled', false); //Activer le bouton
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Echec de connexion : ' + textStatus);
                $('#sign').text('S\'authentifier'); //change button text
                $('#sign').attr('disabled', false); //Activer le bouton
            }
        });
    }
 // **** FIN LOGIN *****




</script>

<script>
    function show_password() {
      
        var x = document.getElementById("password");
        var show = document.getElementById("show");
        if (show.checked) {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>

