<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Terima_muat extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('M_terima_muat');
    }

    public function index() {
        $data['content'] = 'terima_muat/index';
        $this->load->view('template', $data);
    }

    public function add() {
        $data['content'] = 'terima_muat/add';
        $this->load->view('template', $data);
    }

    public function edit() {
        //$noterima = $this->uri->segment(3);
        $noterima = $this->uri->segment(3) . '/' . $this->uri->segment(4) . '/' . $this->uri->segment(5);
        $data['rows'] = $this->M_terima_muat->getRows($noterima);
        $data['content'] = 'terima_muat/edit';
        $this->load->view('template', $data);
    }

    public function simpan() {
        $data = array(
            'noterima' => $_POST['no_terima'],
            'tglterima' => $_POST['tgl_terima'],
            'nmpengirim' => $_POST['pengirim'],
            'NMPENERIMA' => $_POST['penerima'],
            'nopol' => $_POST['no_polisi'],
            'TOTAL' => $_POST['total'],
            'NOMOR' => $_POST['no_muat'],
            'tgl' => $_POST['tanggal'],
            'status' => $_POST['status']
        );
        $query = $this->db->insert('mtt_muat', $data);
        if ($query) {
            //$this->M_terima_muat->delete('NoTerima', $_POST['no_terima'], 'mtt_antri');
            $this->db->update('mtt_antri', array('status' => 'MUAT'), array('NoTerima' => $_POST['no_terima']));
            $jml = count($_POST['banyak']);
            if ($this->M_terima_muat->saveTtMuat($_POST['no_terima'], $jml, $_POST['status'])) {
                //$this->M_terima_muat->delete('Noterima', $_POST['no_terima'], 'tt_antri');
                $msg['resp'] = 'DATA BERHASIL DISIMPAN';
                echo json_encode($msg);
                exit();
            }
        }
    }

    public function get_no_terima() {
        $query = $_POST['query'];
        $result = $this->M_terima_muat->getNoTerima($query);
        echo json_encode($result);
    }

    public function get_no_muat() {

        $day = date('d');
        $romawi = array('01' => 'I', '02' => 'II', '03' => 'III', '04' => 'IV', '05' => 'V',
            '06' => 'VI', '07' => 'VII', '08' => 'VIII', '09' => 'IX', '10' => 'X', '11' => 'XI', '12' => 'XII');
        $month = $romawi[date('m')];
        $year = date('y');
        $nomuat = $this->M_terima_muat->getNoMuat(date('Y'), date('m'));
        $rest['no_muat'] = str_pad($nomuat, 4, 0, STR_PAD_LEFT) . '/' . $month . '/' . $year;
        echo json_encode($rest);
    }

    public function get_table() {
        $noterima = $_POST['no_terima'];
        $result['grid'] = $this->M_terima_muat->getGridMuat($noterima);
        echo json_encode($result);
    }

    public function update() {
        $status = "";
        $data = array(
            'tglterima' => $_POST['tgl_terima'],
            'nmpengirim' => $_POST['pengirim'],
            'NMPENERIMA' => $_POST['penerima'],
            'nopol' => $_POST['no_polisi'],
            'TOTAL' => $_POST['total'],
            'NOMOR' => $_POST['no_muat'],
            'tgl' => $_POST['tanggal'],
            'status' => $_POST['status']
        );
        $this->db->where('noterima', $_POST['no_terima']);
        $query = $this->db->update('mtt_muat', $data);
        if ($query) {
            $this->db->where('NOTERIMA', $_POST['no_terima']);
            $this->db->delete('tt_muat');
            $jml = count($_POST['banyak']);
            if ($this->M_terima_muat->saveTtMuat($_POST['no_terima'], $jml, $_POST['status'])) {
                $this->M_terima_muat->delete('Noterima', $_POST['no_terima'], 'tt_antri');
                $msg['resp'] = 'DATA BERHASIL DIEDIT';
                echo json_encode($msg);
                exit();
            }
        }
    }

    public function get_nopol() {
        $query = $_POST['query'];
        $result = $this->M_terima_muat->getNopol($query);
        echo json_encode($result);
    }

    public function get_detail_terima() {
        $noterima = $_POST['no_terima'];
        $result = array();
        $result['detail'] = $this->M_terima_muat->getDetailAntri($noterima);
        $result['grid'] = $this->M_terima_muat->getGridAntri($noterima);
        echo json_encode($result);
    }

    public function get_data() {
        $aColumns = array('NOMOR', 'TGL', 'nopol', 'nmpengirim', 'NMPENERIMA', 'noterima', 'STATUS', 'action');
        /*
         * Paging
         */
        $sLimit = "";
        if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_POST['iDisplayStart']) . ", " .
                    intval($_POST['iDisplayLength']);
        }

        /*
         * Ordering
         */
        $sOrder = "";
        if (isset($_POST['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_POST['iSortingCols']); $i++) {
                if ($_POST['bSortable_' . intval($_POST['iSortCol_' . $i])] == "true") {
                    $sOrder .= "`" . $aColumns[intval($_POST['iSortCol_' . $i])] . "` " .
                            ($_POST['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }
        /*
         * Filtering
         */
        $sWhere = "";
        if (isset($_POST['sSearch']) && $_POST['sSearch'] != "") {
            $sTotal = count($aColumns) - 1;
            $sWhere = "WHERE (";
            for ($i = 0; $i < $sTotal; $i++) {
                if (isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true") {
                    $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR ";
                }
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
        /*
         * Date Filtering
         */
        $sDate = "";
//        if (isset($_POST['date_min']) && $_POST['date_max'] != "") {
//            $sDate = "WHERE TGL BETWEEN '" . $_POST['date_min'] . "' and '" . $_POST['date_max'] . "'";
//        }
        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if (isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true" && $_POST['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_POST['sSearch_' . $i]) . "%' ";
            }
        }
        /* Output */
        $data = $this->M_terima_muat->getData($sWhere, $sDate, $sOrder, $sLimit);
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
        foreach ($dataRow as $aRow) {
            $row = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == 'action') {
                    $row[] = '<a href="' . base_url() . 'report/r_terima_muat/' . $aRow['NOMOR'] . '/muat_barang" target="_blank">
                                <i class="icon-print"></i>
                              </a><a href="' . base_url() . 'terima_muat/edit/' . $aRow['NOMOR'] . '">
                                <i class="icon-pencil"></i>
                              </a>
                              <a href="' . base_url() . 'terima_muat/delete/' . $aRow['NOMOR'] . '" 
                                  onclick="return confirm(\'Apakah Anda benar-benar mau menghapus ' . $aRow['NOMOR'] . '?\');">
                                      <i class="icon-remove"></i>
                              </a>';
                } elseif ($aColumns[$i] != ' ') {
                    $row[] = $aRow[$aColumns[$i]];
                }
            }
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }

//    function delete() {
//        //$noterima = rawurldecode($this->uri->segment(3));
//        $nomor = $this->uri->segment(3) . '/' . $this->uri->segment(4) . '/' . $this->uri->segment(5);
//        $query = $this->db->query("SELECT noterima FROM mtt_muat WHERE nomor='$nomor'");
//        $noterima = $query->row()->noterima;
//        $this->db->where('nomor', $noterima);
//        $this->db->delete('mtt_muat');
//
//        $this->session->set_flashdata('msg', '<div class="alert alert-info fade in">
//						<button class="close" data-dismiss="alert" type="button">x</button>
//						<strong>Data berhasil dihapus</strong>
//						</div>');
//        $this->db->update('mtt_antri', array('status' => 'ANTRI'), array('NoTerima' => $_POST['no_terima']));
//        redirect('/muat_barang');
//    }

    function delete($id = '') {
        $nomor = $this->uri->segment(3) . '/' . $this->uri->segment(4) . '/' . $this->uri->segment(5);
        $query = $this->db->query("SELECT noterima FROM mtt_muat WHERE nomor='$nomor'");
        $noterima = $query->row()->noterima;
        $deleteData = $this->crud_m->delete('mtt_muat', array('nomor' => $nomor));
        if ($deleteData) {
            $this->db->update('mtt_antri', array('status' => 'ANTRI'), array('NoTerima' => $noterima));
            echo $this->fungsi->warning('Data telah terhapus', base_url('terima_muat'));
        } else {
            $error = $this->crud_m->result($updateData);
            echo $this->fungsi->warning($error['error'], base_url('terima_muat'));
        }
        
    }

}
