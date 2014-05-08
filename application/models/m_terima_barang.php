<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_terima_barang extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function getTypehead($table, $field, $query) {
        $query = $this->db->query("SELECT $field FROM $table WHERE $field LIKE '%$query%'");
        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }

    function checkNoTerima($noterima) {
        $query = $this->db->query("SELECT * FROM mtt_antri WHERE NoTerima = '$noterima'");
        return $query->num_rows();
    }

    public function getData($sWhere, $sOrder, $sLimit) {
        $result = array();
        $query = "SELECT * FROM mtt_antri $sWhere ";

        $sqlX = $this->db->query($query);
        $result['total'] = $sqlX->num_rows();

        $sqlY = $this->db->query($query . "$sOrder $sLimit");
        $result['data'] = $sqlY->result_array();

        return $result;
    }

    function getRows($noterima) {
        $query = $this->db->query("SELECT * FROM mtt_antri WHERE NoTerima = '$noterima'");
        return ($query->num_rows() > 0 ) ? $query->result_array() : FALSE;
    }

    function getGridAntri($noterima) {
        $query = $this->db->query("select a.BANYAK,a.Satuan,a.Barang, round(JUMLAH) as JUMLAH, a.SAT, 
				 Ongkos,jml_ongkos
				 from tt_antri a where a.NoTerima = '$noterima'");
        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }

    function saveTtAntri($no, $jml, $status) {
        for ($i = 0; $i < $jml; $i++) {
            $data[$i] = array(
                'Noterima' => $no,
                'BANYAK' => $_POST['banyak'][$i],
                'Satuan' => $_POST['satuan'][$i],
                'Barang' => $_POST['jenis_barang'][$i],
                'JUMLAH' => $_POST['jumlah'][$i],
                'SAT' => $_POST['kg_m3'][$i],
                'STATUS' => $status,
                'Ongkos' => $_POST['ongkos'][$i],
                'jml_ongkos' => $_POST['jml_ongkos'][$i]
            );
            $data[$i]['jml_ongkos'] = str_replace(".", "", $data[$i]['jml_ongkos']); 
        }
        return $this->db->insert_batch('tt_antri', $data);
    }

}
