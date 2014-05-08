<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_tagih_ongkos extends CI_Model {

	
	public function __construct(){
		parent::__construct();
	}
	
	public function getData($sWhere,$sOrder,$sLimit){
		$result = array();
		$query = "SELECT * FROM m_kirim $sWhere ";
		
		$sqlX = $this->db->query($query);
		$result['total'] = $sqlX->num_rows();
		
		$sqlY = $this->db->query($query . "$sOrder $sLimit");
		$result['data'] = $sqlY->result_array();
		
		return $result;
	}
	
	function getDataMuat($nopol){
		$query = $this->db->query("select b.NOMOR as no_stt, b.noterima as kode,
					a.banyak, a.SATUAN as satuan, b.nmpengirim as pengirim, b.NMPENERIMA as penerima,
					b.`STATUS` as `status` from tt_muat a left join mtt_muat b on a.NOTERIMA=b.noterima
					where b.nopol = '$nopol'");
		return ($query->num_rows() > 0)?$query->result_array():FALSE;
	}
	
	function getNopol($query){
		$query = $this->db->query("SELECT DISTINCT nopol FROM mtt_muat 
								WHERE nopol LIKE '%$query%'");
		return ($query->num_rows() > 0)?$query->result_array():FALSE;
	}
	
	function getNoKirim($year,$month){
		$sql = "SELECT MAX(LEFT(NOKIRIM,4)) AS nokirim FROM 
				m_kirim WHERE YEAR(TGLKIRIM)='$year' AND MONTH(TGLKIRIM)='$month'";
		$query = $this->db->query($sql);
		if ($query){
			$row = $query->row();
			return $row->nokirim + 1;
		}else{
			return '0001';
		}
	}
	
	function getMuatToKirim($nopol){
		$sql = "select  a.NOTERIMA, b.tglterima as TGLTERIMA, b.NOMOR as NOMUAT, 
				b.TGL as TGLMUAT, a.banyak as BANYAK, a.barang as BARANG, a.JML_ONGKOS as JUMLAH,
				a.SAT, a.SATUAN, b.nmpengirim as NMPENGIRIM, b.NMPENERIMA, b.`STATUS`
				from tt_muat a left join mtt_muat b on a.NOTERIMA=b.noterima 
				where b.nopol = '$nopol'";
		$query = $this->db->query($sql);
		foreach($query->result_array() as $row){
			$data[] = array(
				'NOKIRIM' => $_POST['no_kirim'], 
				'NOTERIMA' => $row['NOTERIMA'], 
				'TGLTERIMA' => $row['TGLTERIMA'], 
				'NOMUAT' => $row['NOMUAT'], 
				'TGLMUAT' => $row['TGLMUAT'], 
				'BANYAK' => $row['BANYAK'], 
				'BARANG' => $row['BARANG'], 
				'JUMLAH' => $row['JUMLAH'], 
				'SAT' => $row['SAT'], 
				'SATUAN' => $row['SATUAN'], 
				'NMPENGIRIM' => $row['NMPENGIRIM'], 
				'NMPENERIMA' => $row['NMPENERIMA'], 
				'STATUS' => $row['STATUS'] 
			);
		}
		return $this->db->insert_batch('tt_kirim',$data);
	}
	
	function delete($where,$id,$table){
		$this->db->where($where,$id);
		return $this->db->delete($table);
	}
}
