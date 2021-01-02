<?php


class M_room extends CI_Model {

    function show(){
		return $this->db->get('room');
	}
}