<?php

Class Hotel extends CI_Model{

    public function addHotel($data){
        if(! $this->db->insert('hotel', $data)){
            
            return $this->db->error();
        }
    }

    public function list(){
        $result = $this->db->query("SELECT * FROM hotel")->result_array();
                        
        return $result;
    }
}