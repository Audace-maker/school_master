<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->is_auth();
  }

  function is_auth()
  {
    if (empty($this->session->userdata('USER_ID'))) {
      redirect(base_url(''));
    }
  }

  function index(){

    $data['datte']=$this->Model->getRequete("SELECT DISTINCT DATE_FORMAT(	DATE_ACTION, '%Y') as annee 
    FROM paiement_frais_scolaire WHERE 1 ORDER BY annee ASC ");
    $data['actuel'] = date('Y');
    $this->load->view('Dashboard_view',$data);
  }

  function getmontant() {
    $annee = $this->input->post('annee');
    $mois = $this->input->post('mois');
    $jour = $this->input->post('jour');

    $where_clauses = [];
    if ($annee) {
        $where_clauses[] = "YEAR(DATE_ACTION) = '$annee'";
    }
    if ($mois) {
        $where_clauses[] = "MONTH(DATE_ACTION) = '$mois'";
    }
    if ($jour) {
        $where_clauses[] = "DAY(DATE_ACTION) = '$jour'";
    }
    $where_clause = implode(' AND ', $where_clauses);
    $where_clause = !empty($where_clause) ? "AND " . $where_clause : "";

    $query = "SELECT ID_PAIEMENT, 
                     DATE_FORMAT(DATE_ACTION, '". ($mois ? '%d-%m-%Y' : '%m-%Y') ."') as dateaction,
                     MONTANT as montant 
              FROM paiement_frais_scolaire 
              WHERE 1 $where_clause";

    $results = $this->Model->getRequete($query);

    $vente_data = [];
    foreach ($results as $result) {
        $date = $result['dateaction'];
        if (!isset($vente_data[$date])) {
            $vente_data[$date] = [ 'montant_total' => 0];
        }

        $montant_donne = $result['montant'];
        $vente_data[$date]['montant_total'] += $montant_donne;
    }

    $data = [];
    foreach ($vente_data as $date => $values) {
        
        $data[] = [

            'montant' => max($values['montant_total'], 0),
            'date' => $date
        ];
    }

    echo json_encode($data);
}

  function getMonths() {
    $annee = $this->input->post('annee');
    $query = "SELECT DISTINCT DATE_FORMAT(DATE_ACTION, '%m-%Y') as mois 
              FROM paiement_frais_scolaire 
              WHERE YEAR(DATE_ACTION) = '$annee' 
              ORDER BY mois ASC";
    $results = $this->Model->getRequete($query);
    
    $months = [];
    foreach ($results as $result) {
        $months[] = $result['mois'];
    }
    echo json_encode($months);
}

function getDays() {
    $annee = $this->input->post('annee');
    $mois = $this->input->post('mois');
    $query = "SELECT DISTINCT DATE_FORMAT(DATE_ACTION, '%d-%m-%Y') as jour 
              FROM paiement_frais_scolaire 
              WHERE YEAR(DATE_ACTION) = '$annee' AND MONTH(DATE_ACTION) = '$mois' 
              ORDER BY jour ASC";
    $results = $this->Model->getRequete($query);
    
    $days = [];
    foreach ($results as $result) {
        $days[] = $result['jour'];
    }
    echo json_encode($days);
}
}