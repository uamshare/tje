<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Terima_barang extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('M_terima_barang');
    }

    public function index() {
        $data['content'] = 'terima_barang/index';
        $this->load->view('template', $data);
    }

    public function add() {
        $data['content'] = 'terima_barang/add';
        $this->load->view('template', $data);
    }

    public function edit() {
        $noterima = $this->uri->segment(3);
        $data['rows'] = $this->M_terima_barang->getRows($noterima);
        $data['content'] = 'terima_barang/edit';
        $this->load->view('template', $data);
    }

    public function get_table() {
        $noterima = $_POST['no_terima'];
        $result['grid'] = $this->M_terima_barang->getGridAntri($noterima);
        echo json_encode($result);
    }

    public function get_data() {
        $aColumns = array('NoTerima', 'tglterima', 'nmpenerima', 'nmpengirim', 'STATUS', 'action');
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
        $data = $this->M_terima_barang->getData($sWhere, $sOrder, $sLimit);
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
                    $row[] = '<a href="' . base_url() . 'report/r_terima_barang/' . $aRow['NoTerima'] . '/terima_barang" target="_blank">
                                <i class="icon-print"></i>
                              </a><a href="' . base_url() . 'terima_barang/edit/' . $aRow['NoTerima'] . '">
                                <i class="icon-pencil"></i>
                              </a> 
                              <a href="' . base_url() . 'terima_barang/delete/' . $aRow['NoTerima'] . 
                                 '" onclick="return confirm(\'Apakah Anda benar-benar mau menghapus ' . 
                                  $aRow['NoTerima'] . '?\');">
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

    public function simpan() {
        $msg = array();
        $check = $this->M_terima_barang->checkNoTerima($_POST['no_terima']);
        if ($check > 0) {
            $msg['save'] = false;
            $msg['resp'] = 'NO TERIMA SUDAH ADA';
            echo json_encode($msg);
            exit();
        }
        $status = "";
        if (isset($_POST['status'])) {
            $status = "LUNAS";
        }
        $data = array(
            'NoTerima' => $_POST['no_terima'],
            'tglterima' => $_POST['tanggal'],
            'nmpenerima' => $_POST['penerima'],
            'nmpengirim' => $_POST['pengirim'],
            'STATUS' => $status
        );
        $query = $this->db->insert('mtt_antri', $data);
        if ($query) {
            $jml = count($_POST['banyak']);
            if ($this->M_terima_barang->saveTtAntri($_POST['no_terima'], $jml, 'ANTRI')) {
                $msg['save'] = true;
                $msg['resp'] = 'DATA BERHASIL DISIMPAN';
                echo json_encode($msg);
                exit();
            }
        }
    }

    public function update() {
        $status = "";
        if (isset($_POST['status'])) {
            $status = "LUNAS";
        }
        $data = array(
            'tglterima' => $_POST['tanggal'],
            'nmpenerima' => $_POST['penerima'],
            'nmpengirim' => $_POST['pengirim'],
            'STATUS' => $status
        );
        $this->db->where('NoTerima', $_POST['no_terima']);
        $query = $this->db->update('mtt_antri', $data);
        if ($query) {
            $this->db->where('Noterima', $_POST['no_terima']);
            $this->db->delete('tt_antri');
            $jml = count($_POST['banyak']);
            if ($this->M_terima_barang->saveTtAntri($_POST['no_terima'], $jml, $status)) {
                $msg['save'] = true;
                $msg['resp'] = 'DATA BERHASIL DIUBAH';
                echo json_encode($msg);
                exit();
            }
        }
    }

    public function get_penerima() {
        $query = $_POST['query'];
        $result = $this->M_terima_barang->getTypehead('m_penerima', 'nmpenerima', $query);
        echo json_encode($result);
    }

    public function get_pengirim() {
        $query = $_POST['query'];
        $result = $this->M_terima_barang->getTypehead('m_pengirim', 'nmpengirim', $query);
        echo json_encode($result);
    }

    function delete() {
        $noterima = rawurldecode($this->uri->segment(3));
        $this->db->where('NoTerima', $noterima);
        $this->db->delete('mtt_antri');

        $this->session->set_flashdata('msg', '<div class="alert alert-info fade in">
						<button class="close" data-dismiss="alert" type="button">x</button>
						<strong>Data berhasil dihapus</strong>
						</div>');
        redirect('/terima_barang');
    }

}
