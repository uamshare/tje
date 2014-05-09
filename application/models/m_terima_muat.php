<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_terima_muat extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function getNoMuat($year, $month) {
        $sql = "SELECT MAX(LEFT(NOMOR,4)) AS nomuat FROM 
				mtt_muat WHERE YEAR(TGL)='$year' AND MONTH(TGL)='$month'";
        $query = $this->db->query($sql);
        if ($query) {
            $row = $query->row();
            return $row->nomuat + 1;
        } else {
            return '0001';
        }
    }

    function getRows($noterima) {
        $query = $this->db->query("SELECT * FROM mtt_muat a left join m_pengirim b on a.nmpengirim=b.nmpengirim
				where noterima = '$noterima'");
        return ($query->num_rows() > 0 ) ? $query->result_array() : FALSE;
    }

    public function getData($sWhere, $sDate, $sOrder, $sLimit) {
        $result = array();
        $datemin = $this->input->post('date_min');
        $datemax = $this->input->post('date_max');
        //$sWhere = 
        if(empty($sWhere)){
            $sWhere = '';
            $sWhere .= ' WHERE (tgl>="'.$datemin.'" AND tgl<="'.$datemax.'") ';
        }else{
            $sWhere .= ' AND (tgl>="'.$datemin.'" AND tgl<="'.$datemax.'") ';
        }
        $query = "SELECT * FROM mtt_muat $sWhere ";
        //echo $query;
        $sqlX = $this->db->query($query);
        $result['total'] = $sqlX->num_rows();

        $sqlY = $this->db->query($query . "$sOrder $sLimit");
        $result['data'] = $sqlY->result_array();

        return $result;
    }

    function getNoTerima($query) {
        $query = $this->db->query("SELECT NoTerima as no_terima FROM mtt_antri 
								WHERE NoTerima LIKE '%$query%'");
        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }

    function getDetailAntri($noterima) {
        $query = $this->db->query("select a.NoTerima, a.tglterima, a.nmpengirim, a.nmpenerima, a.`STATUS`, b.alamat 
				from mtt_antri a left join m_pengirim b on a.nmpengirim=b.nmpengirim
				where a.NoTerima = '$noterima'");
        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }

    function getGridAntri($noterima) {
//        $query = $this->db->query("select a.BANYAK,a.Satuan,a.Barang, round(JUMLAH,2) as JUMLAH, a.SAT, 
//				 replace(trim(a.Ongkos),right(trim(a.Ongkos),3),'') as Ongkos 
//				 from tt_antri a where a.NoTerima = '$noterima'");
        $query = $this->db->query("select a.BANYAK,a.Satuan,a.Barang, round(JUMLAH,2) as JUMLAH, a.SAT, 
				 a.Ongkos,a.jml_ongkos 
				 from tt_antri a where a.NoTerima = '$noterima'");
        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }

    function getGridMuat($noterima) {
        $query = $this->db->query("select a.banyak as BANYAK,a.SATUAN as Satuan,a.barang as Barang, 
					round(jumlah,2) as JUMLAH, a.SAT, 
					a.Ongkos,a.Jml_ongkos
					from tt_muat a where a.NoTerima = '$noterima'");
        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }

    function getNopol($query) {
        $query = $this->db->query("SELECT nopol FROM m_kendaraan 
								WHERE nopol LIKE '%$query%'");
        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }

    function saveTtMuat($no, $jml, $status) {
        //echo $no;
        for ($i = 0; $i < $jml; $i++) {
            $data[] = array(
                'NOTERIMA' => $no,
                'banyak' => $_POST['banyak'][$i],
                'barang' => $_POST['jenis_barang'][$i],
                'jumlah' => $_POST['jumlah'][$i],
                'SAT' => $_POST['kg_m3'][$i],
                'SATUAN' => $_POST['satuan'][$i],
                'STATUS' => 'MUAT',
                //'ONGKOS' => $_POST['ongkos'][$i] . '/' . $_POST['kg_m3'][$i],
                'ONGKOS' => $_POST['ongkos'][$i],
                'JML_ONGKOS' => $_POST['jml_ongkos'][$i]
            );
            $data[$i]['JML_ONGKOS'] = str_replace(".", "", $data[$i]['JML_ONGKOS']); 
        }
        return $this->db->insert_batch('tt_muat', $data);
    }

    function delete($where, $id, $table) {
        $this->db->where($where, $id);
        return $this->db->delete($table);
    }

}
