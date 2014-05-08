<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_m extends CI_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    function getList() {
        $result = $this->db->get('users');
        return $result->result();
    }

    function getListById($id) {
        $this->db->where('user_id', $id);
        $result = $this->db->get('users');
        return $result->row();
    }

    function getData($sWhere, $sOrder, $sLimit) {
        $result = array();
        $query = "SELECT * FROM users $sWhere ";

        $sqlX = $this->db->query($query);
        $result['total'] = $sqlX->num_rows();

        $sqlY = $this->db->query($query . "$sOrder $sLimit");
        $result['data'] = $sqlY->result_array();

        return $result;
    }

}

?>
