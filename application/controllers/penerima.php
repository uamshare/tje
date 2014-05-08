<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penerima extends CI_Controller {

	function  __construct()
    {
        parent::__construct();
		$this->load->model('M_penerima');
    }
	
	public function index()
	{
		$data['content'] = 'penerima/index';
		$this->load->view('template',$data);
	}
	
	public function get_data(){
		$aColumns = array( 'nmpenerima', 'alamat', 'telp', 'email', 'action' );
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
		$data = $this->M_penerima->getData($sWhere,$sOrder,$sLimit);
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
					$row[] = '<a href="'.base_url().'penerima/form/'.$aRow['nmpenerima'].'"><i class="icon-pencil"></i></a> <a href="'.base_url().'penerima/delete/'.$aRow['nmpenerima'].'" onclick="return confirm(\'Apakah Anda benar-benar mau menghapus '.$aRow['nmpenerima'].'?\');"><i class="icon-remove"></i></a>';
				}elseif ( $aColumns[$i] != ' ' ){
					$row[] = $aRow[ $aColumns[$i] ];
				}
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}
	
	function form(){
		$nama = rawurldecode($this->uri->segment(3));
		$this->form_validation->set_rules('nmpenerima', 'Nmpenerima', 'required');
		$this->form_validation->set_message('required', '<span style="color:red;font-weight:bold;">*wajib diisi!!</span>');
		if ($this->form_validation->run()) {
			if ($_POST) {
				if ($nama) {
					foreach($_POST as $row => $key){
						$data[$row] = $key;
					}
					$this->db->where('nmpenerima',$nama);
					$this->db->update('m_penerima',$data);
					$this->session->set_flashdata('msg', '<div class="alert alert-info fade in">
							<button class="close" data-dismiss="alert" type="button">x</button>
							<strong>Data berhasil diupdate</strong>
							</div>');
					redirect('/penerima');
				}else{
					foreach($_POST as $row => $key){
						$data[$row] = $key;
					}
					$this->db->insert('m_penerima',$data);
					$this->session->set_flashdata('msg', '<div class="alert alert-info fade in">
							<button class="close" data-dismiss="alert" type="button">x</button>
							<strong>Data berhasil disimpan</strong>
							</div>');
					redirect('/penerima');
				}
			}
		}
		$query = $this->db->query("SELECT * FROM m_penerima WHERE nmpenerima='$nama'");
		$data['result'] = $query->result_array();
		$data['content'] = 'penerima/form';
		$this->load->view('template',$data);
	}
	
	function delete(){
		$nama = rawurldecode($this->uri->segment(3));
		$this->db->where('nmpenerima', $nama);
		$this->db->delete('m_penerima');
		
		$this->session->set_flashdata('msg', '<div class="alert alert-info fade in">
						<button class="close" data-dismiss="alert" type="button">x</button>
						<strong>Data berhasil dihapus</strong>
						</div>');
		redirect('/penerima');
	}
}
