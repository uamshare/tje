<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kendaraan extends CI_Controller {

	function  __construct()
    {
        parent::__construct();
		$this->load->model('M_kendaraan');
    }
	
	public function index()
	{
		$data['content'] = 'kendaraan/index';
		$this->load->view('template',$data);
	}
	
	public function get_data(){
		$aColumns = array( 'nopol', 'merk', 'jenis', 'warna', 'supir', 'action' );
		/* 
		* Paging
		*/
		$sLimit = "";
		if ( isset( $_POST['iDisplayStart'] ) && $_POST['iDisplayLength'] != '-1' )
		{
			$sLimit = "LIMIT ".intval( $_POST['iDisplayStart'] ).", ".
				intval( $_POST['iDisplayLength'] );
		}
		
		/*
		 * Ordering
		 */
		$sOrder = "";
		if ( isset( $_POST['iSortCol_0'] ) )
		{
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_POST['iSortingCols'] ) ; $i++ )
			{
				if ( $_POST[ 'bSortable_'.intval($_POST['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= "`".$aColumns[ intval( $_POST['iSortCol_'.$i] ) ]."` ".
						($_POST['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
				}
			}
			
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}
		/* 
		 * Filtering
		 */
		$sWhere = "";
		if ( isset($_POST['sSearch']) && $_POST['sSearch'] != "" )
		{
			$sTotal = count($aColumns) - 1;
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<$sTotal ; $i++ )
			{
				if ( isset($_POST['bSearchable_'.$i]) && $_POST['bSearchable_'.$i] == "true" )
				{
					$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string( $_POST['sSearch'] )."%' OR ";
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		
		/* Individual column filtering */
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( isset($_POST['bSearchable_'.$i]) && $_POST['bSearchable_'.$i] == "true" && $_POST['sSearch_'.$i] != '' )
			{
				if ( $sWhere == "" )
				{
					$sWhere = "WHERE ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string($_POST['sSearch_'.$i])."%' ";
			}
		}
		/*Output*/
		$data = $this->M_kendaraan->getData($sWhere,$sOrder,$sLimit);
		$dataRow = array();
		$dataRow = $data['data'];
		$iTotal = count($dataRow);
		$iFilteredTotal = $data['total'];
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		foreach($dataRow as $aRow){
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if ($aColumns[$i] == 'action'){
					$row[] = '<a href="'.base_url().'kendaraan/form/'.$aRow['nopol'].'"><i class="icon-pencil"></i></a> <a href="'.base_url().'kendaraan/delete/'.$aRow['nopol'].'" onclick="return confirm(\'Apakah Anda benar-benar mau menghapus '.$aRow['nopol'].'?\');"><i class="icon-remove"></i></a>';
				}elseif ( $aColumns[$i] != ' ' ){
					$row[] = $aRow[ $aColumns[$i] ];
				}
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}
	
	function form(){
		$nopol = rawurldecode($this->uri->segment(3));
		$this->form_validation->set_rules('nopol', 'Nopol', 'required');
		$this->form_validation->set_message('required', '<span style="color:red;font-weight:bold;">*wajib diisi!!</span>');
		if ($this->form_validation->run()) {
			if ($_POST) {
				if ($nopol) {
					foreach($_POST as $row => $key){
						$data[$row] = $key;
					}
					$this->db->where('nopol',$nopol);
					$this->db->update('m_kendaraan',$data);
					$this->session->set_flashdata('msg', '<div class="alert alert-info fade in">
							<button class="close" data-dismiss="alert" type="button">x</button>
							<strong>Data berhasil diupdate</strong>
							</div>');
					redirect('/kendaraan');
				}else{
					foreach($_POST as $row => $key){
						$data[$row] = $key;
					}
					$this->db->insert('m_kendaraan',$data);
					$this->session->set_flashdata('msg', '<div class="alert alert-info fade in">
							<button class="close" data-dismiss="alert" type="button">x</button>
							<strong>Data berhasil disimpan</strong>
							</div>');
					redirect('/kendaraan');
				}
			}
		}
		$query = $this->db->query("SELECT * FROM m_kendaraan WHERE nopol='$nopol'");
		$data['result'] = $query->result_array();
		$data['content'] = 'kendaraan/form';
		$this->load->view('template',$data);
	}
	
	function delete(){
		$nopol = rawurldecode($this->uri->segment(3));
		$this->db->where('nopol', $nopol);
		$this->db->delete('m_kendaraan');
		
		$this->session->set_flashdata('msg', '<div class="alert alert-info fade in">
						<button class="close" data-dismiss="alert" type="button">x</button>
						<strong>Data berhasil dihapus</strong>
						</div>');
		redirect('/kendaraan');
	}
}
