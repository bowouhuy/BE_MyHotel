<?php

Class Users extends CI_Model {

    public function login($where){

        return $this->db->get_where('users',$where);
    }

    public function get_user($user_id){
        return $this->db->get_where('users',array('user_id' => $user_id));
    }

    public function register($data){
        if(! $this->db->insert('users', $data)){
            
            return $this->db->error();
        }
    }

}