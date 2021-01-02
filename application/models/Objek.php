<?php

Class Objek extends CI_Model {

    public function list($objek = null){
        $this->db->select('*');    
        $this->db->from('objek');
        $this->db->join('hotel', 'objek.hotel_id = hotel.hotel_id');

        if($objek === null){
            return $this->db->get()->result_array();
        }else{
            return $this->db->get_where('', ['objek_id' => $objek])->result_array();
        }
    }


}