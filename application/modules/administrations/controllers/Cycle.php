<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cycle extends CI_Controller {

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
    $this->load->view('Cycle_view');
  }

  function liste()
  {
    
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $escaped_search = addslashes($var_search);

    $query_principal = "SELECT `ID_CYCLES`, `DESCRIPTION`, `NOMBRE_TRANCHES`, `MONTANT_PAR_TRANCHE`,
     `MONTANT_TOTAL`, `STATUT`, `DATE_INSERT` FROM `cycles`
      WHERE 1";

        $order_by_column = 'DESCRIPTION'; 
         $sort_direction = 'DESC'; 

    $columns = array('DESCRIPTION');

    if (isset($_POST['order'])) {
          $column_index = $_POST['order'][0]['column'];
          $sort_direction = $_POST['order'][0]['dir'];
          $order_by_column = isset($columns[$column_index]) ? $columns[$column_index] : 'DESCRIPTION';
        }
      
        $order_by = " ORDER BY DESCRIPTION DESC";

    $search = !empty($_POST['search']['value']) ? (" AND  (DESCRIPTION LIKE '%$escaped_search%' )") : '';


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

      $btnEdit = '<a title="Modifier" onclick="edit_cycle(' . $row->ID_CYCLES . ')" href="javascript:void(0);"
       class="btn btn-xs sharp btn-primary">
                <i class="fa fa-edit"></i>
            </a>';

      $statut = $row->STATUT;

      $statValue = $row->STATUT;

      $statustext = "";
      if ($statut == 1) {
        $statustext = "Arrêter";
        $statut = "<div style='width: 100%; display:flex;'><span class='' style='margin-left:5px; color:green;'> Activé </span></div>";
      } else {
        $statustext = "Activer";
        $statut = "<div style='width: 100%; display:flex;'><span class='' style='margin-left:5px; color:red;'> Arrêté </span></div>";
      }


       $btnDelete = '';
if ($statValue == 1) { 
    $btnDelete = '<a class="btn btn-xs sharp btn-danger" href="javascript:void(0)" onclick="change_status_cycle(' . "'" . $row->ID_CYCLES . "'" . ',' . "'" . $row->STATUT . "'" . ')" title="Arreter">
                     <i class="fa fa-trash"></i>
                  </a>';
} else { 
    $btnDelete = '<a class="btn btn-xs sharp" style="background-color: green; color: white;" href="javascript:void(0)" onclick="change_status_cycle(' . "'" . $row->ID_CYCLES . "'" . ',' . "'" . $row->STATUT . "'" . ')" title="Activer">
                 <i class="fa fa-check" style="color: white;"></i>
              </a>';
}



      $sub_array = array();
      $u+=1;
      $sub_array[] = $u;
      $sub_array[] = $row->DESCRIPTION;
      $sub_array[] = $row->NOMBRE_TRANCHES;
      $sub_array[] = number_format($row->MONTANT_PAR_TRANCHE,0,',',' ');
      $sub_array[] = number_format($row->MONTANT_TOTAL,0,',',' ');
      $sub_array[] = $statut;
      $sub_array[] = '<div class="table-option">
                      '.$btnDelete.'
                      '. $btnEdit.'
                    </div>';
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

  function add()
  {
    $this->_validate();
    $descr = $this->input->post('description');
    $nbre = $this->input->post('nombre');
    $mpt = $this->input->post('montant_tranche');
    $mt = $this->input->post('montant_total');
    $statut = 1;

    $data_cycle = array(
        'DESCRIPTION' => $descr,
        'NOMBRE_TRANCHES' => $nbre,
        'MONTANT_PAR_TRANCHE' => $mpt,
        'MONTANT_TOTAL' => $mt,
        'STATUT' => $statut
    );

    $id = $this->Model->create('cycles', $data_cycle);
    echo json_encode(array('status'=>true));
  }


  function update()
  {
    $this->_validate();
    $id=$this->input->post('ID_CYCLES');
    $descr = $this->input->post('description');
    $nbre = $this->input->post('nombre');
    $mpt = $this->input->post('montant_tranche');
    $mt = $this->input->post('montant_total');

    $data = array(
        'DESCRIPTION' => $descr,
        'NOMBRE_TRANCHES' => $nbre,
        'MONTANT_PAR_TRANCHE' => $mpt,
        'MONTANT_TOTAL' => $mt
    );

    $id = $this->Model->update('cycles',array('ID_CYCLES'=>$id ),$data);
    echo json_encode(array('status'=>true));

  }

  function _validate()
  {
    $data=array();
    $data['error_string']=array();
    $data['inputerror']=array();
    $data['status']=true;

    if ($this->input->post('nombre')=='') 
      {
          $data['inputerror'][]="nombre";
          $data['error_string'][]="Le champ est obligatoire";
          $data['status']=FALSE;
      }

      if ($this->input->post('montant_tranche')=='') 
      {
          $data['inputerror'][]="montant_tranche";
          $data['error_string'][]="Le champ est obligatoire";
          $data['status']=FALSE;
      }

      if ($this->input->post('montant_total')=='') 
      {
          $data['inputerror'][]="montant_total";
          $data['error_string'][]="Le champ est obligatoire";
          $data['status']=FALSE;
      }

      if ($this->input->post('description')=='') 
      {
          $data['inputerror'][]="description";
          $data['error_string'][]="Le champ est obligatoire";
          $data['status']=FALSE;
      }
    

    if ($data['status']==FALSE) 
    {
      # code...
      echo json_encode($data);
      exit();
    }
  }

  function getOne($id)
  {
    $data=$this->Model->getOne('cycles',array('ID_CYCLES'=>$id));
  echo json_encode($data);
  }

  
  function change_status_cycle($id,$stat)
  {
    $STATUS=($stat==1)? 0:1;
    $type = ($STATUS == 1) ? 1 : 0;

    $this->Model->update('cycles',array('ID_CYCLES'=>$id),array('STATUT'=>$STATUS));

    echo json_encode(array('status'=>true));
  }
}