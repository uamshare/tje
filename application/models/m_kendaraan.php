<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_kendaraan extends CI_Model {

	
	public function __construct(){
		parent::__construct();
	}
	
	public function getData($sWhere,$sOrder,$sLimit){
		$result = array();
		$query = "SELECT nopol, merk, jenis, warna, supir FROM m_kendaraan $sWhere ";
		
		$sqlX = $this->db->query($query);
		$result['total'] = $sqlX->num_rows();
		
		$sqlY = $this->db->query($query . "$sOrder $sLimit");
		$result['data'] = $sqlY->result_array();
		
		return $result;
	}
	
}
