<?php

Class Cart extends CI_Model {

    public function add($data){
        if(! $this->db->insert('cart', $data)){
            
            return $this->db->error();
        }
    }

    public function getCartbyId($id){
        return $this->db->get_where('cart', array('cart_id' => $id));
        
    }
}