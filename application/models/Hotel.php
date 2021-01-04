<?php

Class Hotel extends CI_Model{

    public function addHotel($data){
        if(! $this->db->insert('hotel', $data)){
            
            return $this->db->error();
        }
    }

}