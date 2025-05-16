<?php

class Mylibrary
{
      protected $CI;
	function __construct()
	{
	    $this->CI = & get_instance();
      $this->CI->load->library('upload');
      $this->CI->load->library('email');
      $this->CI->load->model('Model');
	}

	public function getOne($table, $array)
	{
		return $this->CI->Model->getOne($table,$array);
	}


	public function get_permission($url)
	{
		//echo $url;
		$autorised = 0;
		if(empty($this->CI->Model->getOne('admin_fonctionnalites',array('FONCTIONNALITE_URL'=>$url)))){
          $autorised =1;
		}else{
			$data = $this->CI->Model->get_permission($url);

		  if(!empty($data))
		  	{
		  		$autorised =1;
		  	}
	  }

	  return $autorised;
	}

  public function built_steps($step)
  {
   $etapes = array(
                   '1'=>''.lang("personel_info").'',
                   '2'=>'Documents Medicaux',
                   '3'=>''.lang("bank_data").'',
                   '4'=>"Contacts",
                   '5'=>''.lang('Relation_familiale').'',
                   '6'=>''.lang('title_personne_reference').'',
                   '7'=>"Documents"
                 );
    $html_etapes = "";
    foreach ($etapes as $key => $value) {
      //#337ab7, ,  ffeb99
      $step_bg = ($step ==$key)?'#d2ff4d':(($step > $key)?'#5cb85c':'white');
      $step_txt = ($step <= $key)?'#111':"#FFF";
     
      $variable = "<center><div class='col-md-2'>"; 
      if($key == 7 || $key == 4){
        $variable = "<center><div class='col-md-1'>";
      }
      $html_etapes .= $variable.
                      "<div class='img-circle' style='width:20px;height:20px;background-color:".$step_bg.";border:1px solid #d9d9d9'>
                      <span style='color:".$step_txt."'>".$key."</span>
                      </div>".$value."</div></center>";
    }

    return $html_etapes;
  }
   public function built_steps2($step)
  {
   $etapes = array(
                   '1'=>'Identification',
                   '2'=>'Affectation',
                  
                 );
    $html_etapes = "";
    foreach ($etapes as $key => $value) {
      //#337ab7, ,  ffeb99
      $step_bg = ($step ==$key)?'#d2ff4d':(($step > $key)?'#5cb85c':'white');
      $step_txt = ($step <= $key)?'#111':"#FFF";
     
      $variable = "<center><div class='col-md-2'>"; 
     
      $html_etapes .= $variable.
                      "<div class='img-circle' style='width:20px;height:20px;background-color:".$step_bg.";border:1px solid #d9d9d9'>
                      <span style='color:".$step_txt."'>".$key."</span>
                      </div>".$value."</div></center>";
    }

    return $html_etapes;
  }

  public function upload_file($post_file,$folder_dest,$name_file,$file_typs="*")
  {
        if(!is_dir(FCPATH.$folder_dest)){
            mkdir(FCPATH.$folder_dest, 0777, TRUE);
        }

        $config['upload_path']          = '.'.$folder_dest;
        $config['allowed_types']        = $file_typs;
        $config['max_height']           = 2048;
        $config['file_name']           = $name_file.'_'.$_FILES[$post_file]['name'];
        $config['max_size']             = 1000;
        $config['overwrite']           = TRUE;

        $this->CI->upload->initialize($config);

        if (!$this->CI->upload->do_upload($post_file))
        {
          //return $this->CI->upload->display_errors();
          return '';
        }
        else
        {
          return $config['upload_path'].  $config['file_name'];
        }
  }

public function built_steps3($step)
  {
   $etapes = array(
                   '1'=>'Identification',
                   '2'=>'Ajout membre(s)',
                   '3'=>'Lieu d\'Affectation',
                   '4'=>'Ajout matériel(s)',
                  
                 );
    $html_etapes = "";
    foreach ($etapes as $key => $value) {
      //#337ab7, ,  ffeb99
      $step_bg = ($step ==$key)?'#d2ff4d':(($step > $key)?'#5cb85c':'white');
      $step_txt = ($step <= $key)?'#111':"#FFF";
     
      $variable = "<center><div class='col-md-2'>"; 
     
      $html_etapes .= $variable.
                      "<div class='img-circle' style='width:20px;height:20px;background-color:".$step_bg.";border:1px solid #d9d9d9'>
                      <span style='color:".$step_txt."'>".$key."</span>
                      </div>".$value."</div></center>";
    }

    return $html_etapes;
  }
  public function upload_document($post_file,$folder_dest,$file_typs="*")
  {
    if(!is_dir(FCPATH.$folder_dest)){
        mkdir(FCPATH.$folder_dest, 0777, TRUE);
    }

    $config['upload_path']          = '.'.$folder_dest;
    $config['allowed_types']        = $file_typs;
    $config['max_height']           = 2048;
    $config['file_name']            = $_FILES[$post_file]['name'];
    $config['max_size']             = 1000;
    $config['overwrite']            = TRUE;

    $this->CI->upload->initialize($config);

    if (!$this->CI->upload->do_upload($post_file))
    {
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }

}
?>
