<?php

Class Users extends CI_Model {

    public function login($where){

        return $this->db->get_where('users',$where);
    }

    public function register($data){
        if(! $this->db->insert('users', $data)){
            
            $data = array(
                'affected_row' => $this->db->affected_rows(),
                'status' => $this->db->error()
            );
            return $data;
        }
        $data = array(
            'affected_row' => $this->db->affected_rows(),
            'status' => "Successfully"
        );
        return $data;
    }

}