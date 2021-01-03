<?php

Class Objek extends CI_Model {

    public function list($where, $objek_id=null){

        if($objek_id != null){
            $result = $this->db->query("SELECT * FROM objek 
                        LEFT JOIN hotel ON objek.hotel_id = hotel.hotel_id 
                        WHERE objek_id = '$objek_id'")->row_array();
            
        }else{
            $result = $this->db->query("SELECT * FROM objek 
                        LEFT JOIN hotel ON objek.hotel_id = hotel.hotel_id 
                        WHERE objek_jenis LIKE '%$where[objek_jenis]%' 
                        && objek_nama LIKE '%$where[objek_nama]%'")->result_array();
                        
        }
        return $result;

    }

    public function addObjek($data){
        if(! $this->db->insert('objek', $data)){
            
            return $this->db->error();
        }
    }

    public function destroy($id){
        $this->db->delete('objek', array('objek_id' => $id));
    }


}