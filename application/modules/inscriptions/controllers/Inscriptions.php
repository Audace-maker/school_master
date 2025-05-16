<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inscriptions extends CI_Controller {

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
    $data['etudiants']=$this->Model->getRequete("SELECT * FROM etudients WHERE STATUT=1");
    $data['classes']=$this->Model->getRequete("SELECT * FROM classe WHERE STATUT =1");
    $data['cycles']=$this->Model->getRequete("SELECT * FROM cycles WHERE STATUT=1");
    $data['statut']=$this->Model->getRequete("SELECT * FROM `statut_eleve` WHERE 1");
    $this->load->view('Inscriptions_view',$data);
  }

  function liste()
  {
    
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $escaped_search = addslashes($var_search);

    $query_principal = "SELECT i.ID_INSCRIPTION, i.ID_ETUDIANT, i.ID_CLASSE, i.ID_CYCLE, i.ID_USERS,
                       i.ANNEE_SCOLAIRE, i.DATE_INSCRIPTION, i.ID_STATUT_ELEVE,
                        e.MATRICULE,e.NOM as nom_eleve,e.PRENOM ,c.NOM,cy.DESCRIPTION as cycledescr, u.PRENOM as prenom,
                        st.DESCRIPTION FROM inscription i 
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
      $sub_array[] = $row->prenom;
      $sub_array[] = date('d-m-Y H:i', strtotime($row->DATE_INSCRIPTION));
      $sub_array[] = $row->DESCRIPTION;

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

  
  public function upload_document($nom_file, $nom_champ)
  {
    $rep_doc = FCPATH . 'uploads/members/';
    $file_extension = pathinfo($nom_champ, PATHINFO_EXTENSION);
    $file_extension = strtolower($file_extension);
    $valid_ext = array('png', 'jpeg', 'jpg', 'pdf');
  
    if (!is_dir($rep_doc)) { 
      mkdir($rep_doc, 0777, TRUE);
    }
  
    $pathfile = 'uploads/members/' . $nom_champ;  
    move_uploaded_file($nom_file, $rep_doc . $nom_champ);
    
    return $pathfile;
  }


  public function inscrire()
  {
    $this->validate();

      $choix = $this->input->post('choix');
      $user = $this->session->userdata('USER_ID');
  
      if ($choix == 'B') { 

        if (!empty($_FILES['photo']['name'])) {
          $photo = $this->upload_document($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
          if (!$photo) {
              throw new Exception('Erreur lors de l\'upload du document.');
          }
      } else {
    $photo = ''; 
      }
      $nom =$this->input->post('nom');
      $prenom=$this->input->post('prenom');
      
      $deux = substr($nom, 0, 2);
      $deuxpre = substr($prenom, 0, 2);
      $date = date('y');
      $matricule = '000'.$deux.$deuxpre.$date.$user;

          $data_eleve = array(
              '	MATRICULE' =>$matricule,
              'NOM' => $nom,
              'PRENOM' => $prenom,
              'SEXE' => $this->input->post('sexe'),
              'ADRESSE' => $this->input->post('adresse'),
              'DATE_NAISSANCE' => $this->input->post('date_naissance'),
              'LIEU_NAISSANCE' => $this->input->post('lieu_naissance'),
              'DOCUMENTS' =>$photo
          );
  
          $this->Model->create('etudients', $data_eleve);
          $id_eleve = $this->db->insert_id(); 
      } else { 
          $id_eleve = $this->input->post('id_eleve');
      }
  
      $data_inscription = array(
          'ID_ETUDIANT' => $id_eleve,
          'ID_USERS' =>$user,
          'ID_CLASSE' => $this->input->post('id_classe'),
          'ID_CYCLE' => $this->input->post('id_cycle'),
          'ID_STATUT_ELEVE' => $this->input->post('id_statut_eleve'),
          'ANNEE_SCOLAIRE' => $this->input->post('annee_scolaire')
      );
  
      $this->Model->create('inscription', $data_inscription);
  
      echo json_encode(array('status' => true));
  }



  function validate()
  {
    $data=array();
    $data['error_string']=array();
    $data['inputerror']=array();
    $data['status']=true;

    $choix = $this->input->post('choix');

    if ($choix == 'B') { 

      if (empty($this->input->post('nom'))) 
      {
        # code...
        $data['error_string'][]="Ce champ est obligatoire";
        $data['inputerror'][]="nom";
        $data['status']=FALSE;
      }
      if (empty($this->input->post('prenom'))) 
      {
        # code...
        $data['error_string'][]="Ce champ est obligatoire";
        $data['inputerror'][]="prenom";
        $data['status']=FALSE;
      }

      if (empty($this->input->post('sexe'))) 
      {
        # code...
        $data['error_string'][]="Ce champ est obligatoire";
        $data['inputerror'][]="sexe";
        $data['status']=FALSE;
      }
      if (empty($this->input->post('date_naissance'))) 
      {
        # code...
        $data['error_string'][]="Ce champ est obligatoire";
        $data['inputerror'][]="date_naissance";
        $data['status']=FALSE;
      }

      if (empty($this->input->post('lieu_naissance'))) 
      {
        # code...
        $data['error_string'][]="Ce champ est obligatoire";
        $data['inputerror'][]="lieu_naissance";
        $data['status']=FALSE;
      }
      if (empty($this->input->post('adresse'))) 
      {
        # code...
        $data['error_string'][]="Ce champ est obligatoire";
        $data['inputerror'][]="adresse";
        $data['status']=FALSE;
      }

      if (empty($_FILES['photo']['name'])) {
        $data['error_string'][] = "Le champ d'image est obligatoire";
        $data['inputerror'][] = "photo";
        $data['status'] = FALSE;
      }


      if (empty($this->input->post('id_cycle'))) 
      {
        # code...
        $data['error_string'][]="Ce champ est obligatoire";
        $data['inputerror'][]="id_cycle";
        $data['status']=FALSE;
      }

      if (empty($this->input->post('id_classe'))) 
      {
        # code...
        $data['error_string'][]="Ce champ est obligatoire";
        $data['inputerror'][]="id_classe";
        $data['status']=FALSE;
      }

      if (empty($this->input->post('id_statut_eleve'))) 
      {
        # code...
        $data['error_string'][]="Ce champ est obligatoire";
        $data['inputerror'][]="id_statut_eleve";
        $data['status']=FALSE;
      }

    }else 
    {

      if (empty($this->input->post('id_cycle'))) 
      {
        # code...
        $data['error_string'][]="Ce champ est obligatoire";
        $data['inputerror'][]="id_cycle";
        $data['status']=FALSE;
      }

      if (empty($this->input->post('id_classe'))) 
      {
        # code...
        $data['error_string'][]="Ce champ est obligatoire";
        $data['inputerror'][]="id_classe";
        $data['status']=FALSE;
      }

      if (empty($this->input->post('id_statut_eleve'))) 
      {
        # code...
        $data['error_string'][]="Ce champ est obligatoire";
        $data['inputerror'][]="id_statut_eleve";
        $data['status']=FALSE;
      }

      
      if (empty($this->input->post('id_eleve'))) 
      {
        # code...
        $data['error_string'][]="Ce champ est obligatoire";
        $data['inputerror'][]="id_eleve";
        $data['status']=FALSE;
      }

    }

    if ($data['status']==FALSE) 
    {
      # code...
      echo json_encode($data);
      exit();
    }
  }
  
}