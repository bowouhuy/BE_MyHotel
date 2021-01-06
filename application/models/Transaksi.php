<?php

Class Transaksi extends CI_Model {

    public function add($data){
        if(! $this->db->insert('transaksi', $data)){
            
            return $this->db->error();
        }
    }

    public function getAll($id, $nama){
        if($id != null) {
            $result = $this->db->get_where('transaksi', array( 'transaksi_id' => $id))->row_array();
        }else{

            // $result = $this->db->get('transaksi')->result_array();
            $result = $this->db->query("SELECT * FROM transaksi 
                        INNER JOIN users ON transaksi.user_id = users.user_id
                        LEFT JOIN (
                            SELECT transaksi_id, GROUP_CONCAT(DISTINCT objek_nama SEPARATOR ', ') AS room
                            FROM cart
                            LEFT JOIN objek ON cart.objek_id = objek.objek_id
                            GROUP BY transaksi_id
                        )a ON transaksi.transaksi_id = a.transaksi_id
                        WHERE user_nama LIKE '%$nama%'
                        ")->result_array();
        }
        return $result;
    }

    public function getTransaksibyNo($no){
        return $this->db->get_where('transaksi', array( 'transaksi_no' => $no));
    
    }

    public function getbyUserId($user_id){
        return $this->db->get_where('transaksi', array( 'user_id' => $user_id));
    }

    public function getLastTransaksiId(){
        $result = $this->db->query(
                    "SELECT transaksi_id FROM transaksi 
                     ORDER BY transaksi_id DESC
                     LIMIT 1
                    ");
        return $result;
    }

    public function updateSt($where){
        $this->db->set('transaksi_status', $where['transaksi_status']);
        $this->db->where('transaksi_id', $where['transaksi_id']);

        if(! $this->db->update('transaksi')){
            return "Success";
        }
        return $this->db->affected_rows();
    }

    public function countAll(){
        return $this->db->count_all_results('transaksi');
    }

    public function chartTransaksiDay(){
        $day = date('d')+1-1;
        $month = date('m');
        $data =array();
        for($i=0; $i<5; $i++){
            $date = $month.$day."%";
            $result = $this->db->query("SELECT COUNT(*) FROM transaksi
            where transaksi_no like '$date'")->row_array();

            $day=$day-1;
            // $data[$i]=$result["COUNT(*)"];
            $data[$i]=$result["COUNT(*)"];
            // print_r($data);exit;
        }
        return $data;
    }


}