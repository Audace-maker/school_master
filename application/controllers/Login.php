<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

  public function __construct() {
    parent::__construct();
  }

  function index(){
    $data['donn']="";

    $this->load->view('Login_view',$data);
  }

  public function check_login(){

    $this->_validate();
    $email = htmlspecialchars($this->input->post('email'));
    $password=md5($this->input->post('password'));

    $user= $this->Model->getRequeteOne('SELECT * FROM users WHERE EMAIL="'.$email.'" AND PASSWORD="'.$password.'" AND STATUT=1');
    $output = null;

    if (!empty($user)) {

      if ($user['ID_PROFIL']<=3) {
        
      $session = array(
      'USER_ID' => $user['ID_USERS'],
      'NOM' => $user['NOM'],
      'PRENOM' => $user['PRENOM'],
      'EMAIL' => $user['EMAIL'],
      'TELEPHONE' => $user['TELEPHONE'],
      'USERNAME' => $user['USERNAME'],
      'ID_PROFIL' => $user['ID_PROFIL']
    );
        $this->session->set_userdata($session);

        $output = array("status"=>TRUE,'message'=>'Authentification.....');
      }else{
        $output = array("status"=>FALSE,'message'=>'Vous n\'avez pas accès à ce système.');
      }
    }else{
       $output = array("status"=>FALSE,'message'=>'Verifier votre mot de passe ou nom utilisateur.');
    }

    echo json_encode($output);
  }

  public function do_logout()
  {
     $session = array(
      'USER_ID' => '',
      'NOM' => '',
      'PRENOM' => '',
      'EMAIL' => '',
      'TELEPHONE' => '',
      'USERNAME' => '',
      'ID_PROFIL' => '',
    );

    $this->session->set_userdata($session);
    redirect(base_url(''));
  }


  function go_submit() {

    if ($this->session->userdata('ID_PROFIL')==1) {
      
      redirect(base_url('utilisateurs'));
      
    }else if($this->session->userdata('ID_PROFIL')==2){
     redirect(base_url('classe')); 
    }else if($this->session->userdata('ID_PROFIL')==3){
     redirect(base_url('paiement')); 

    }
    
  }

  
  public function _validate()
  {
    $data = null;
    $stat = true;

    $pseudo = $this->input->post('email');
    $pwd =  $this->input->post('password');
    if ($pseudo == '' && $pwd == '') {
     $data = array('status'=>FALSE,'message'=>'Email et mot de passe requis');
     $stat = false;
   }
   elseif($pseudo == '')
   {
    $data = array('status'=>FALSE,'message'=>'L\'adresse mail requise');
    $stat = false;
  }else{
    if($pwd == '')
    {
      $data = array('status'=>FALSE,'message'=>'Mot de passe requis');
      $stat = false;
    }
  }

  if($stat === FALSE)
  {
    echo json_encode($data);
    exit();
  }
}

}
