<?php

Class Hotel extends CI_Model{

    public function addHotel($data){
        if(! $this->db->insert('hotel', $data)){
            
            return $this->db->error();
        }
    }

    public function list($id){
        if($id != null){
            $result = $this->db->query("SELECT * FROM hotel WHERE hotel_id = '$id'")->row_array();
        }else{
            $result = $this->db->query("SELECT * FROM hotel")->result_array();
        }
                        
        return $result;
    }

    public function editHotel($data){
        $this->db->set($data);
        $this->db->where('hotel_id', $data['hotel_id']);
        if (! $this->db->update('hotel')){
            return $this->db->error();
        }
        
    }

    public function countAll(){
        return $this->db->count_all_results('hotel');
    }
}