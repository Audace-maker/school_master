<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paie_frais_inscription extends CI_Controller {

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

    $data['etudiants']=$this->Model->getRequete("SELECT i.ID_INSCRIPTION,i.ID_ETUDIANT,e.MATRICULE FROM inscription i LEFT JOIN etudients e ON e.ID_ETUDIENT=i.ID_ETUDIANT  WHERE 1");
    $this->load->view('Paie_frais_inscriptionview',$data);
  }


  function liste()
  {
    
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $escaped_search = addslashes($var_search);

    $query_principal = "SELECT i.ID_INSCRIPTION, i.ID_ETUDIANT, i.ID_CLASSE, i.ID_CYCLE, i.ID_USERS,
                       i.ANNEE_SCOLAIRE, i.DATE_INSCRIPTION, i.ID_STATUT_ELEVE,pi.MONTANT,pi.DATE_PAIEMENT,
                        e.MATRICULE,e.NOM as nom_eleve,e.PRENOM ,c.NOM,cy.DESCRIPTION as cycledescr, u.PRENOM as prenom,
                        st.DESCRIPTION FROM paiement_frais_inscription pi
                        LEFT JOIN inscription i ON i.ID_INSCRIPTION = pi.ID_INSCRIPTION 
                        LEFT JOIN etudients e ON e.ID_ETUDIENT = i.ID_ETUDIANT 
                        LEFT JOIN classe c ON c.ID_CLASSE = i.ID_CLASSE
                        LEFT JOIN cycles cy ON cy.ID_CYCLES = i.ID_CYCLE
                        LEFT JOIN users u ON u.ID_USERS = i.ID_USERS 
                        LEFT JOIN statut_eleve st ON st.ID_STATUT_ELEVE =i.ID_STATUT_ELEVE
                         WHERE 1";

        $order_by_column = 'e.NOM'; 
         $sort_direction = 'DESC'; 

    $columns = array('NOM');

    if (isset($_POST['order'])) {
          $column_index = $_POST['order'][0]['column'];
          $sort_direction = $_POST['order'][0]['dir'];
          $order_by_column = isset($columns[$column_index]) ? $columns[$column_index] : 'NOM';
        }
      
        $order_by = " ORDER BY e.NOM DESC";

    $search = !empty($_POST['search']['value']) ? (" AND  (e.NOM LIKE '%$escaped_search%' OR e.PRENOM LIKE '%$escaped_search%')") : '';


    $limit = 'LIMIT 0,10';
    if ($_POST['length'] != -1) {
      $limit = 'LIMIT ' . $_POST["start"] . ',' . $_POST["length"];
    }

    $critaire = "";

    $query_secondaire = $query_principal . ' ' . $critaire . ' ' . $search . ' ' . $order_by . ' ' . $limit;
    $query_filter = $query_principal . '  ' . $critaire . ' ' . $search;
    $fetch_data = $this->Model->datatable($query_secondaire);

    $u = 0;
    $data = array();

   foreach ($fetch_data as $row) {
    $nom = $row->nom_eleve;
    $prenom = $row->PRENOM;

      $sub_array = array();
      $u+=1;
      $sub_array[] = $u;
      $sub_array[] = '<b>'.$row->MATRICULE.'</b><br/>'.$nom.' '.$prenom;
      $sub_array[] = $row->cycledescr;
      $sub_array[] = $row->NOM;
      $sub_array[] = $row->ANNEE_SCOLAIRE;
      $sub_array[] = number_format($row->MONTANT,'0','',' ');
      $sub_array[] = $row->prenom;
      $sub_array[] = date('d-m-Y H:i', strtotime($row->DATE_PAIEMENT));

      $data[] = $sub_array;
    }


    $output = array(
      "draw" => intval($_POST['draw']),
      "recordsTotal" => $this->Model->all_data($query_principal),
      "recordsFiltered" => $this->Model->filtrer($query_filter),
      "data" => $data
    );

    echo json_encode($output);
  }



  public function payer()
  {
    $this->validate();

      $montant = $this->input->post('montant');
      $user = $this->session->userdata('USER_ID');
      $id_eleve = $this->input->post('id_eleve');
  
      $data_inscription = array(
          'ID_INSCRIPTION' => $id_eleve,
          'ID_USERS' =>$user,
          'MONTANT' => $montant
      );
  
      $this->Model->create('paiement_frais_inscription', $data_inscription);
  
      echo json_encode(array('status' => true));
  }


  function validate()
  {
    $data=array();
    $data['error_string']=array();
    $data['inputerror']=array();
    $data['status']=true;


      if (empty($this->input->post('montant'))) 
      {
        # code...
        $data['error_string'][]="Ce champ est obligatoire";
        $data['inputerror'][]="montant";
        $data['status']=FALSE;
      }

      
      if (empty($this->input->post('id_eleve'))) 
      {
        # code...
        $data['error_string'][]="Ce champ est obligatoire";
        $data['inputerror'][]="id_eleve";
        $data['status']=FALSE;
      }


    if ($data['status']==FALSE) 
    {
      # code...
      echo json_encode($data);
      exit();
    }
  }
}