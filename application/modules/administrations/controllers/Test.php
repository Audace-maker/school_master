<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

  public function __construct() {
    parent::__construct();
    // $this->load->library('email'); 
  }

  function index(){
    $this->load->view('Test_view');
  }

  function add() {
    $this->form_validation->set_rules('montant', 'Montant', 'required|numeric');
    
    if ($this->form_validation->run() == FALSE) {
      echo json_encode(array("status" => false, "inputerror" => array_keys($this->form_validation->error_array()), "error_string" => array_values($this->form_validation->error_array())));
      return;
    }

    $data = array(
      'MONTANT' => $this->input->post('montant')
    );

    $id = $this->Model->create('frais_inscription', $data);

    if ($id) {
      // Configuration de l'email dans le contrôleur pour Gmail avec SSL
      $config = array(
        'protocol' => 'smtp',
        'smtp_host' => 'ssl://smtp.gmail.com', // Utilisez SSL avec le port 465
        'smtp_user' => 'ericiranezereza@gmail.com', // Remplacez par votre adresse Gmail
        'smtp_pass' => 'hgizhaukjhbijfuw', // Remplacez par votre mot de passe d'application
        'smtp_port' => 465,
        'mailtype' => 'html',
        'charset' => 'utf-8',
        'wordwrap' => TRUE,
        'newline' => "\r\n", 
        'crlf' => "\r\n" // Ajoutez cette ligne
      );

      $this->email->initialize($config);
      
      // Envoie de l'email
      $this->email->from('ericiranezereza@gmail.com', 'Lumicash');
      $this->email->to('ericiranezereza@gmail.com'); //premier email
      $this->email->cc('ndayisabaaudace68@gmail.com'); // deuxieme email
      $this->email->subject('Notification de Paiement');
      $this->email->message('Vous avez effectué un paiement de ' . $data['MONTANT'] . ' FBU.');

      if ($this->email->send()) {
        echo json_encode(array("status" => true));
      } else {
        // Capturez les erreurs d'envoi d'email et les loggez
        $error = $this->email->print_debugger(array('headers'));
        echo json_encode(array("status" => false, "inputerror" => array(), "error_string" => array("Erreur d'envoi de l'email", $error)));
      }
    } else {
      echo json_encode(array("status" => false, "inputerror" => array(), "error_string" => array("Erreur d'insertion dans la base de données")));
    }
  }
}
