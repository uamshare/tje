<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_penerima extends CI_Model {

	
	public function __construct(){
		parent::__construct();
	}
	
	public function getData($sWhere,$sOrder,$sLimit){
		$result = array();
		$query = "SELECT nmpenerima, alamat, telp, email FROM m_penerima $sWhere ";
		
		$sqlX = $this->db->query($query);
		$result['total'] = $sqlX->num_rows();
		
		$sqlY = $this->db->query($query . "$sOrder $sLimit");
		$result['data'] = $sqlY->result_array();
		
		return $result;
	}
	
}
