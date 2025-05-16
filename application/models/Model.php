<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model extends CI_Model{

	function create($table, $data) {

        $query = $this->db->insert($table, $data);
        return ($query) ? true : false;

    }

    function Requetedelete($requete){
      $this->db->query($requete);
      
    }

  function update($table, $criteres, $data) {
        $this->db->where($criteres);
        $query = $this->db->update($table, $data);
        return ($query) ? true : false;
    } 

    function insert_last_id($table, $data) {

        $query = $this->db->insert($table, $data);
       
       if ($query) {
            return $this->db->insert_id();
        }

    }   

    function getOne($table, $criteres) {
        $this->db->where($criteres);
        $query = $this->db->get($table);
        return $query->row_array();
    }

   function delete($table,$criteres){
        $this->db->where($criteres);
        $query = $this->db->delete($table);
        return ($query) ? true : false;
    }

     function fetch_table($table,$limit,$start,$order,$ordervalue)
    {
     $this->db->limit($limit,$start);
     $this->db->order_by($order,$ordervalue);
     $query= $this->db->get($table);
     
      if($query)
       {
           return $query->result_array();
       }   
    }



        function getRequete($requete){
      $query=$this->db->query($requete);
      if ($query) {
         return $query->result_array();
      }
    }
     function getRequeteOne($requete){
      $query=$this->db->query($requete);
      if ($query) {
         return $query->row_array();
      }
    }

/**/
 //Didace Dady: Automatiser les list avec Json : on met en parametre un requet concu avec tous les jointure possible
 public function datatable($requete)//make_datatables : requete avec Condition,LIMIT start,length
 { 
        $query =$this->maker($requete);//call function make query
        return $query->result();
      }  
    public function maker($requete)//make query
    {
      return $this->db->query($requete);
    }

    public function all_data($requete)//count_all_data : requete sans Condition sans LIMIT start,length
    {
       $query =$this->maker($requete); //call function make query
       return $query->num_rows();   
     }
     public function filtrer($requete)//get_filtered_data : requete avec Condition sans LIMIT start,length
     {
         $query =$this->maker($requete);//call function make query
         return $query->num_rows();
         
       }
}