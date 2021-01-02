<?php

Class Users extends CI_Model {

    public function login($where){

        return $this->db->get_where('users',$where);
    }

    public function register($data){
        if(! $this->db->insert('users', $data)){
            
            return $this->db->error();
        }
    }

}