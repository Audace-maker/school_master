<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paiement extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->is_auth();
    require('fpdf184/fpdf.php');
  }

  function is_auth()
  {
    if (empty($this->session->userdata('USER_ID'))) {
      redirect(base_url(''));
    }
  }

  function index(){

    $data['etudiants']=$this->Model->getRequete("SELECT i.ID_INSCRIPTION,i.ID_ETUDIANT,e.MATRICULE FROM inscription i LEFT JOIN etudients e ON e.ID_ETUDIENT=i.ID_ETUDIANT  WHERE 1");
    $data['banque']=$this->Model->getRequete("SELECT * FROM banque WHERE 1");
    $this->load->view('Paiement_view',$data);
  }

  function liste()
  {
    
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $escaped_search = addslashes($var_search);

    $query_principal = "SELECT pfs.NUMERO_FACTURE as numfact, pfs.ID_PAIEMENT as id_paiement ,pfs.ID_INSCRIPTION,pfs.MONTANT as montant,pfs.ID_BANQUE, pfs.ID_USER,
                        pfs.NUMERO_BORDEREAU as numbordereau, pfs.DATE_ACTION as dateaction, i.ID_ETUDIANT,i.ID_CLASSE, i.ID_CYCLE,
                        e.MATRICULE as matricule,e.NOM as nom,e.PRENOM as prenom, c.NOM as classe, cy.DESCRIPTION as cycle,b.NOM as banque
                         FROM paiement_frais_scolaire pfs 
                         LEFT JOIN inscription i ON i.ID_INSCRIPTION = pfs.ID_INSCRIPTION
                        LEFT JOIN etudients e ON e.ID_ETUDIENT = i.ID_ETUDIANT 
                        LEFT JOIN classe c ON c.ID_CLASSE = i.ID_CLASSE
                        LEFT JOIN cycles cy ON cy.ID_CYCLES = i.ID_CYCLE
                        LEFT JOIN banque b ON b.ID_BANQUE = pfs.ID_BANQUE
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
    $nom = $row->nom;
    $prenom = $row->prenom;
    $cycle = $row->cycle;
    $classe = $row->classe;
    $btnImprimer = '<a style="margin-left: 40%; cursor: pointer;" onclick="Export_recus(' . $row->id_paiement . ')"><i class="fa fa-print"></i></a>';

      $sub_array = array();
      $u+=1;
      $sub_array[] = $u;
      $sub_array[] = date('d-m-Y H:i', strtotime($row->dateaction));
      $sub_array[] = '<b>'.$row->matricule.'</b><br/>'.$nom.' '.$prenom.' <br/><div style="font-size: 13px; color: green;">'.$cycle.' '.$classe.'</div>';
      $sub_array[] = $row->numfact;
      $sub_array[] = number_format($row->montant,0,',',' ');
      $sub_array[] = $row->banque;
      $sub_array[] = $row->numbordereau;
      $sub_array[] = $btnImprimer;

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
  

  public function fetchStudentInfo() {
    $id_inscription = $this->input->post('id_inscription');

    $data = $this->Model->getRequete("
        SELECT 
            c.DESCRIPTION as cycle,
            s.DESCRIPTION as section,
            cl.NOM as classe,
            cy.NOMBRE_TRANCHES as nbre_tranche,
            cy.MONTANT_PAR_TRANCHE as montant_tranche,
            cy.MONTANT_TOTAL as montant_apayer,
            e.NOM as nom, e.PRENOM as prenom, e.DATE_NAISSANCE as date_naissance
        FROM 
            inscription i
            JOIN etudients e ON e.ID_ETUDIENT = i.ID_ETUDIANT
            JOIN cycles c ON c.ID_CYCLES = i.ID_CYCLE
            JOIN classe cl ON cl.ID_CLASSE = i.ID_CLASSE
            JOIN section s ON s.ID_SECTION = cl.ID_SECTION
            JOIN cycles cy ON cy.ID_CYCLES = i.ID_CYCLE
        WHERE 
            i.ID_INSCRIPTION = $id_inscription
    ");

    if ($data) {
        $data = $data[0];

        $paiement = $this->Model->getRequete("
            SELECT SUM(MONTANT) as montant_paye 
            FROM paiement_frais_scolaire 
            WHERE ID_INSCRIPTION = $id_inscription
        ");

        if ($paiement && $paiement[0]['montant_paye']) {
            $data['montant_restant'] = $data['montant_apayer'] - $paiement[0]['montant_paye'];
        } else {
            $data['montant_restant'] = $data['montant_apayer'];
        }

        echo json_encode($data);
    }
}


public function payer()
{
  $this->validate();

  $montant = $this->input->post('montant_payer');
  $user = $this->session->userdata('USER_ID');
  $id_eleve = $this->input->post('id_eleve');
  $id_banque = $this->input->post('id_banque');
  $numero_banque = $this->input->post('numero_banque');
  $date = date('y');
  $randomDigits = sprintf("%02d", rand(0, 99)); 
  $numero = '00' . $date . $user . $id_eleve . $randomDigits; 

  $data_paiement = array(
      'NUMERO_FACTURE' => $numero,
      'ID_INSCRIPTION' => $id_eleve,
      'ID_USER' => $user,
      'MONTANT' => $montant,
      'ID_BANQUE' => $id_banque,
      'NUMERO_BORDEREAU' => $numero_banque
  );

  $this->Model->create('paiement_frais_scolaire', $data_paiement);

  $this->db->select('e.NOM, e.PRENOM, e.DATE_NAISSANCE,cl.NOM AS classe, s.DESCRIPTION as section,e.MATRICULE as matricule');
  $this->db->from('inscription i');
  $this->db->join('etudients e', 'e.ID_ETUDIENT = i.ID_ETUDIANT');
  $this->db->join('classe cl', 'cl.ID_CLASSE = i.ID_CLASSE');
  $this->db->join('section s', 's.ID_SECTION = cl.ID_SECTION');
  $this->db->where('i.ID_INSCRIPTION', $id_eleve);
  $query = $this->db->get();
  $etudiant = $query->row();

  $config = array(
    'protocol' => 'smtp',
    'smtp_host' => 'ssl://smtp.gmail.com', 
    'smtp_user' => 'ericiranezereza@gmail.com', 
    'smtp_pass' => 'hgizhaukjhbijfuw', 
    'smtp_port' => 465,
    'mailtype' => 'html',
    'charset' => 'utf-8',
    'wordwrap' => TRUE,
    'newline' => "\r\n", 
    'crlf' => "\r\n" 
  );

  $this->email->initialize($config);

  $this->email->from('ericiranezereza@gmail.com');
  //$this->email->to('ericiranezereza@gmail.com');
  $this->email->cc('ndayisabaaudace68@gmail.com'); 
  $this->email->subject('Notification de Paiement');
  $this->email->message('
    <html>
    <head>
      <style>
        .email-container {
          font-family: Arial, sans-serif;
          color: #333;
          line-height: 1.6;
        }
        .header {
          background-color: #4CAF50;
          color: white;
          padding: 10px;
          text-align: center;
        }
        .content {
          padding: 20px;
        }
        .student-details {
          margin-bottom: 20px;
        }
        .footer {
          background-color: #f1f1f1;
          text-align: center;
          padding: 10px;
          margin-top: 20px;
          border-top: 1px solid #ddd;
        }
      </style>
    </head>
    <body>
      <div class="email-container">
        <div class="header">
          <h1>Notification de Paiement</h1>
        </div>
        <div class="content">
          <p>Salut,</p>
          <div class="student-details">
            <p>L\' élève ' . $etudiant->NOM .' '. $etudiant->PRENOM .' de matricule '.$etudiant->matricule. ' section '.$etudiant->section.', classe '.$etudiant->classe. ' né le '.date('d-m-Y', strtotime($etudiant->DATE_NAISSANCE)).' vient d\'effectuer un paiement de '.$montant .'FBU</p>
          </div>
          <p>Merci.</p>
        </div>
        <div class="footer">
          <p>&copy; School Master</p>
        </div>
      </div>
    </body>
    </html>
  ');

  if ($this->email->send()) {
    echo json_encode(array("status" => true));
  } else {

    $error = $this->email->print_debugger(array('headers'));
    echo json_encode(array("status" => false, "inputerror" => array(), "error_string" => array("Erreur d'envoi de l'email", $error)));
  }
}


function validate()
{
  $data=array();
  $data['error_string']=array();
  $data['inputerror']=array();
  $data['status']=true;


    if (empty($this->input->post('montant_payer'))) 
    {
      $data['error_string'][]="Ce champ est obligatoire";
      $data['inputerror'][]="montant_payer";
      $data['status']=FALSE;
    }

    if (empty($this->input->post('id_eleve'))) 
    {
      $data['error_string'][]="Ce champ est obligatoire";
      $data['inputerror'][]="id_eleve";
      $data['status']=FALSE;
    }

    if (empty($this->input->post('id_banque'))) 
    {
      $data['error_string'][]="Ce champ est obligatoire";
      $data['inputerror'][]="id_banque";
      $data['status']=FALSE;
    }

    if (empty($this->input->post('numero_banque'))) 
    {
      $data['error_string'][]="Ce champ est obligatoire";
      $data['inputerror'][]="numero_banque";
      $data['status']=FALSE;
    }

    if ($data['status']==FALSE) 
    {
      echo json_encode($data);
      exit();
    }
  }


    public function Export_recus($encrypted_facture_id = NULL) {
    $idclientfact = base64_decode(urldecode($encrypted_facture_id));

    $query = "SELECT pfs.NUMERO_FACTURE as numfact, pfs.ID_PAIEMENT as id_paiement ,pfs.ID_INSCRIPTION,pfs.MONTANT as montant,pfs.ID_BANQUE, pfs.ID_USER,
    pfs.NUMERO_BORDEREAU as numbordereau, pfs.DATE_ACTION as dateaction, i.ID_ETUDIANT,i.ID_CLASSE, i.ID_CYCLE,
    e.MATRICULE as matricule,e.NOM as nom,e.PRENOM as prenom, c.NOM as classe, cy.DESCRIPTION as cycle,b.NOM as banque
     FROM paiement_frais_scolaire pfs 
     LEFT JOIN inscription i ON i.ID_INSCRIPTION = pfs.ID_INSCRIPTION
    LEFT JOIN etudients e ON e.ID_ETUDIENT = i.ID_ETUDIANT 
    LEFT JOIN classe c ON c.ID_CLASSE = i.ID_CLASSE
    LEFT JOIN cycles cy ON cy.ID_CYCLES = i.ID_CYCLE
    LEFT JOIN banque b ON b.ID_BANQUE = pfs.ID_BANQUE
     WHERE id_paiement=".$idclientfact;
     $data =  $this->Model->getRequeteOne($query); 

    $pdf = new FPDF('P', 'mm', array(95, 120));
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(255, 255, 255); 

    $image = 'images/logom.jpg';
    if ($image != "" && $image != null && $image != "null") {
        $pdf->Image($image, 10, 10, 25);
    }
    $pdf->SetLeftMargin(5); 
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 0, '', 0, 0, '');
    $pdf->Cell(20, 5,''.utf8_decode('Reçu numéro '), 0, 1, 'R', true);
    $pdf->Cell(0, 0, '', 0, 1, '');
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->Cell(62, 0, '', 0, 0, '');
    $pdf->Cell(22, 5, $data['numfact'], 0, 1, 'R', true);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, '', 0, 1, '');
    $pdf->Cell(62, 5, '', 0, 0, '');
    $pdf->Cell(22, 8, 'ACCESS SCHOOL', 0, 1, 'R', true);

    $pdf->Line(5, $pdf->GetY(), 90, $pdf->GetY()); 
    $pdf->SetXY(5, 55);
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Cell(180, 5,''.utf8_decode('L\' élève '.$data['nom'].' '.$data['prenom'].' ayant une matricule '.$data['matricule']), 0, 1);
    $pdf->Cell(180, 5,''.utf8_decode('de cycle '.$data['cycle'].' dans une classe '.$data['classe'].' a subit'), 0, 1);
    $pdf->Cell(180, 5,''.utf8_decode('opération de paiement du minérval de '.number_format($data['montant'],0,',',' ').' Fbu dans'), 0, 1);
    $pdf->Cell(180, 5,''.utf8_decode('une banque '.$data['banque'].' et il a un bordereau no '.$data['numbordereau'].'.'), 0, 1);

    $pdf->Output();
}

}