<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utilisateurs extends CI_Controller {

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
    $data['profils']=$this->Model->getRequete("SELECT `ID_PROFIL`, `DESCRIPTION`, `STATUT_PROFIL` FROM `profil` WHERE STATUT_PROFIL=1 ORDER BY `DESCRIPTION` ASC");
    $this->load->view('Utilisateurs_view',$data);
  }

  function liste()
  {
    
    $var_search = !empty($_POST['search']['value']) ? $_POST['search']['value'] : null;
    $escaped_search = addslashes($var_search);

    $query_principal = "SELECT ID_USERS, NOM, PRENOM, EMAIL, TELEPHONE, PASSWORD, ADRESSE, PHOTOS,USERNAME, p.DESCRIPTION AS DESCRIP, u.STATUT 
                    FROM users u 
                    JOIN profil p ON p.ID_PROFIL = u.ID_PROFIL  
                    WHERE  1";

        $order_by_column = 'NOM'; 
         $sort_direction = 'DESC'; 

    $columns = array('NOM');

    if (isset($_POST['order'])) {
          $column_index = $_POST['order'][0]['column'];
          $sort_direction = $_POST['order'][0]['dir'];
          $order_by_column = isset($columns[$column_index]) ? $columns[$column_index] : 'NOM';
        }
      
        $order_by = " ORDER BY NOM DESC";

    $search = !empty($_POST['search']['value']) ? (" AND  (NOM LIKE '%$escaped_search%' OR PRENOM LIKE '%$escaped_search%' OR EMAIL LIKE '%$escaped_search%' OR TELEPHONE LIKE '%$escaped_search%' OR ADRESSE LIKE '%$escaped_search%')") : '';


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

      $btnEdit = '<a title="Modifier" onclick="edit_users(' . $row->ID_USERS . ')" href="javascript:void(0);"
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
    $btnDelete = '<a class="btn btn-xs sharp btn-danger" href="javascript:void(0)" onclick="change_status_users(' . "'" . $row->ID_USERS . "'" . ',' . "'" . $row->STATUT . "'" . ')" title="Arreter">
                     <i class="fa fa-trash"></i>
                  </a>';
} else { 
    $btnDelete = '<a class="btn btn-xs sharp" style="background-color: green; color: white;" href="javascript:void(0)" onclick="change_status_users(' . "'" . $row->ID_USERS . "'" . ',' . "'" . $row->STATUT . "'" . ')" title="Activer">
                 <i class="fa fa-check" style="color: white;"></i>
              </a>';
}

      $image_path = base_url($row->PHOTOS);
      if ($row->PHOTOS == null || !file_exists('uploads/members/' . basename($row->PHOTOS))) {
        $image_path = base_url('assets/images/user.png');
      }

      $sub_array = array();
      $u+=1;
      $sub_array[] = $u;
      $sub_array[] = '<div style="display: flex; align-items: center; gap:15px;">' .
                '<img src="' . $image_path . '" alt="User Image" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;cursor:pointer;" onclick="openPhotoModal(\'' . $image_path . '\')">' .
        '<div style="display: flex; flex-direction:column; align-items:center;"><strong>' . $row->NOM . '&nbsp;' . $row->PRENOM . '</strong></div></div>';
      $sub_array[] = '<div style="display: flex; align-items: center; gap:15px;">'.
        '<div style="display: flex; flex-direction:column; align-items:center;">' . $row->EMAIL . '<br>' . $row->TELEPHONE . '</div></div>';
        $sub_array[] = $row->USERNAME;
         $sub_array[] = $row->DESCRIP;
      $sub_array[] = $row->ADRESSE;
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
    $nom = $this->input->post('nom');
    $prenom = $this->input->post('prenom');
    $email = $this->input->post('email');
    $telephone = $this->input->post('telephone');
    $adresse = $this->input->post('adresse');
    $profile = $this->input->post('profile');
    $username = $this->input->post('username');
    $statut = 1;
    $mot_de_passe = md5('12345');

    if (!empty($_FILES['photo']['name'])) {
            $photo = $this->upload_document($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
            if (!$photo) {
                throw new Exception('Erreur lors de l\'upload de l\'image.');
            }
        } else {
      $photo = ''; 
        }

    $data_users = array(
        'NOM' => $nom,
        'PRENOM' => $prenom,
        'EMAIL' => $email,
        'TELEPHONE' => $telephone,
        'ADRESSE' => $adresse,
        'USERNAME' => $username,
        'PHOTOS' => $photo,
        'ID_PROFIL' => $profile,
        'PASSWORD' => $mot_de_passe,
        'STATUT' => $statut
    );

    $id = $this->Model->create('users', $data_users);
    echo json_encode(array('status'=>true));
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


  function update()
  {
    $this->_validate();
    $ID_USERS=$this->input->post('ID_USERS');
    $nom = $this->input->post('nom');
    $prenom = $this->input->post('prenom');
    $email = $this->input->post('email');
    $telephone = $this->input->post('telephone');
    $adresse = $this->input->post('adresse');
    $username = $this->input->post('username');
    $profile = $this->input->post('profile');
    $existing_photo = $this->input->post('existing_photo'); 

    if (!empty($_FILES['photo']['name'])) {
            $new_photo = $this->upload_document($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
            if (!$new_photo) {
                throw new Exception('Erreur lors de l\'upload de l\'image.');

        if (!empty($existing_photo)) {
          $is_photo_used = $this->Model->getRequete("SELECT COUNT(*) as count FROM users WHERE PHOTOS = '$existing_photo'");
          
          if (isset($is_photo_used[0]['count']) && $is_photo_used[0]['count'] == 1) {
  
            $photo_path = FCPATH . $existing_photo;
            if (file_exists($photo_path)) {
              unlink($photo_path); 
            }
          }
        }
            }
        $photo = $new_photo;
        } else {
            $photo = $existing_photo;
        }

    $data_users = array(
        'NOM' => $nom,
        'PRENOM' => $prenom,
        'EMAIL' => $email,
        'TELEPHONE' => $telephone,
        'ADRESSE' => $adresse,
        'PHOTOS' => $photo,
        'ID_PROFIL' => $profile,
        'USERNAME' => $username
    );

    $id = $this->Model->update('users',array('ID_USERS'=>$ID_USERS ),$data_users);
    echo json_encode(array('status'=>true));

  }


  function _validate()
  {
    $data=array();
    $data['error_string']=array();
    $data['inputerror']=array();
    $data['status']=true;

    $check_product=$this->Model->getOne('users',array('ID_USERS'=>$this->input->post('ID_USERS')));

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

    if ($this->input->post('email')=='') 
      {
          $data['inputerror'][]="email";
          $data['error_string'][]="Le champ est obligatoire";
          $data['status']=FALSE;
      }
   
        $user_mail = $this->input->post('email');
        $existing_user = $this->Model->getOne('users', array('EMAIL' => $user_mail));

        if (!empty($existing_user) && $existing_user['ID_USERS'] != $this->input->post('ID_USERS')) {
            $data['inputerror'][] = "email";
            $data['error_string'][] = "L'adresse e-mail est déjà utilisée";
            $data['status'] = false;
        }
        
        

    $phone_number = $this->input->post('telephone');

    if (!empty($phone_number)) {
        $phone_number = preg_replace("/[^0-9]/", "", $phone_number);

        if (strlen($phone_number) > 12) {
            $data['inputerror'][] = "telephone";
            $data['error_string'][] = "Vérifiez votre numéro";
            $data['status'] = false;
        }
    }
     $phone_number = $this->input->post('telephone');
        $existing_user = $this->Model->getOne('users', array('TELEPHONE' => $phone_number));

        if (!empty($existing_user) && $existing_user['ID_USERS'] != $this->input->post('ID_USERS')) {
            $data['inputerror'][] = "telephone";
            $data['error_string'][] = "Le numéro est déjà utilisée";
            $data['status'] = false;
        }
    if (empty($this->input->post('email'))) 
    {
      # code...
      $data['error_string'][]="Ce champ est obligatoire";
      $data['inputerror'][]="email";
      $data['status']=FALSE;
    }
    if (empty($this->input->post('telephone'))) 
    {
      # code...
      $data['error_string'][]="Ce champ est obligatoire";
      $data['inputerror'][]="telephone";
      $data['status']=FALSE;
    }

    if (empty($this->input->post('adresse'))) 
    {
      # code...
      $data['error_string'][]="Ce champ est obligatoire";
      $data['inputerror'][]="adresse";
      $data['status']=FALSE;
    }

     if (empty($this->input->post('username'))) 
    {
      # code...
      $data['error_string'][]="Ce champ est obligatoire";
      $data['inputerror'][]="username";
      $data['status']=FALSE;
    }
    
    if (empty($this->input->post('profile'))) 
    {
      # code...
      $data['error_string'][]="Ce champ est obligatoire";
      $data['inputerror'][]="profile";
      $data['status']=FALSE;
    }
    

    if (empty($this->input->post('MOTIF')) && $this->input->post('stat') != 0) {
        $data['error_string'][] = "Le champ est obligatoire";
        $data['inputerror'][] = "MOTIF";
        $data['status'] = FALSE;
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
    $data=$this->Model->getOne('users',array('ID_USERS'=>$id));
  echo json_encode($data);
  }


  function change_status_users($id,$stat)
  {
    $STATUS=($stat==1)? 0:1;
    $type = ($STATUS == 1) ? 1 : 0;

    $this->Model->update('users',array('ID_USERS'=>$id),array('STATUT'=>$STATUS));

    echo json_encode(array('status'=>true));
  }
}