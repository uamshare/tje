<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tagih_ongkos extends CI_Controller {

	function  __construct()
    {
        parent::__construct();
		$this->load->model('M_tagih_ongkos');
    }
	
	public function index()
	{
		$data['content'] = 'tagih_ongkos/index';
		$this->load->view('template',$data);
	}
	
	public function add()
	{
		$data['content'] = 'tagih_ongkos/add';
		$this->load->view('template',$data);
	}
	
	public function get_no_kirim(){
		$day = date('d');
		$romawi = array('01'=>'I','02'=>'II','03'=>'III','04'=>'IV','05'=>'V',
						'06'=>'VI','07'=>'VII','08'=>'VIII','09'=>'IX','10'=>'X','11'=>'XI','12'=>'XII');
		$month = $romawi[date('m')];
		$year = date('y');
		$nokirim = $this->M_tagih_ongkos->getNoKirim(date('Y'),date('m'));
		$rest['no_kirim'] = str_pad($nokirim,4,0,STR_PAD_LEFT).'/'.$month.'/'.$year;
		echo json_encode($rest);
	}
	
	public function simpan(){
		$data = array(
			'NOKIRIM'		=> $_POST['no_kirim'],
			'TGLKIRIM'		=> $_POST['tanggal'],
			'NOPOL'			=> $_POST['no_polisi'],
			'KETERANGAN'	=> $_POST['keterangan']
		);
		$query = $this->db->insert('m_kirim',$data);
		if ($query){
			$save = $this->M_tagih_ongkos->getMuatToKirim($_POST['no_polisi']);
			if ($save){
				$this->M_tagih_ongkos->delete('nopol',$_POST['no_polisi'],'mtt_muat');
				$msg['resp'] = 'DATA BERHASIL DISIMPAN';
				echo json_encode($msg);
				exit();
			}
		}
	}
	
	public function get_nopol(){
		$query = $_POST['query'];
		$result = $this->M_tagih_ongkos->getNopol($query);
		echo json_encode($result);
	}
	
	public function get_detail_muat(){
		$nopol = $_POST['no_polisi'];
		$result = array();
		$result['grid'] = $this->M_tagih_ongkos->getDataMuat($nopol);
		echo json_encode($result);
	}
	
	public function get_data(){
		$aColumns = array( 'NOKIRIM', 'TGLKIRIM', 'NOPOL', 'KETERANGAN', 'action' );
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
		$data = $this->M_tagih_ongkos->getData($sWhere,$sOrder,$sLimit);
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
					$row[] = '<a href="'.base_url().'tagih_ongkos/form/'.$aRow['NOKIRIM'].'"><i class="icon-pencil"></i></a> <a href="'.base_url().'tagih_ongkos/delete/'.$aRow['NOKIRIM'].'" onclick="return confirm(\'Apakah Anda benar-benar mau menghapus '.$aRow['NOKIRIM'].'?\');"><i class="icon-remove"></i></a>';
				}elseif ( $aColumns[$i] != ' ' ){
					$row[] = $aRow[ $aColumns[$i] ];
				}
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}
}
