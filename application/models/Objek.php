<?php

Class Objek extends CI_Model {

    public function list($where){
        $this->db->select('*');    
        $this->db->from('objek');
        $this->db->join('hotel', 'objek.hotel_id = hotel.hotel_id');
        $result = $this->db->query("SELECT * FROM objek 
                    LEFT JOIN hotel ON objek.hotel_id = hotel.hotel_id 
                    WHERE objek_jenis LIKE '%$where[objek_jenis]%' 
                    && objek_nama LIKE '%$where[objek_nama]%'")->result_array();

            return $result;
    }


}