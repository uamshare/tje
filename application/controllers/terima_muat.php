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
            'noterima' => $_POST['no_muat'],
            'tglterima' => $_POST['tgl_terima'],
            'nmpengirim' => $_POST['pengirim'],
            'NMPENERIMA' => $_POST['penerima'],
            'nopol' => $_POST['no_polisi'],
            'TOTAL' => $_POST['total'],
            'NOMOR' => $_POST['no_terima'],
            'tgl' => $_POST['tanggal'],
            'status' => $_POST['status']
        );
        $query = $this->db->insert('mtt_muat', $data);
        if ($query) {
            $this->M_terima_muat->delete('NoTerima', $_POST['no_terima'], 'mtt_antri');
            //$this->db->update('mtt_antri', array('status' => 'MUAT'), array('NoTerima' => $_POST['no_terima']));
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
            'NOMOR' => $_POST['no_terima'],
            'tgl' => $_POST['tanggal'],
            'status' => $_POST['status']
        );
        $this->db->where('noterima', $_POST['no_muat']);
        $query = $this->db->update('mtt_muat', $data);
        //echo "tes";
        if ($query) {
            $this->db->where('noterima', $_POST['no_muat']);
            $this->db->delete('tt_muat');
            $jml = count($_POST['banyak']);
            if ($this->M_terima_muat->saveTtMuat($_POST['no_muat'], $jml, $_POST['status'])) {
                //$this->M_terima_muat->delete('nomor', $_POST['no_terima'], 'tt_antri');
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
        $aColumns = array('noterima', 'TGL', 'nopol', 'nmpengirim', 'NMPENERIMA', 'NOMOR', 'STATUS', 'action');
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
                $sOrder .= " noterima desc";
            }
        }
//        if ($sOrder == "") {
//                $sOrder .= "ORDER BY noterima desc";
//            }
//            echo $sOrder;
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
                    $row[] = '<a href="' . base_url() . 'report/r_terima_muat/' . $aRow['noterima'] . '/muat_barang" target="_blank">
                                <i class="icon-print"></i>
                              </a><a href="' . base_url() . 'terima_muat/edit/' . $aRow['noterima'] . '">
                                <i class="icon-pencil"></i>
                              </a>
                              <a href="' . base_url() . 'terima_muat/delete/' . $aRow['noterima'] . '" 
                                  onclick="return confirm(\'Apakah Anda benar-benar mau menghapus ' . $aRow['noterima'] . '?\');">
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
        $queryH = $this->db->query("SELECT * FROM mtt_muat WHERE noterima='$nomor'");
        $queryD = $this->db->query("SELECT * FROM tt_muat WHERE noterima='$nomor'");

        $noterima = $queryH->row()->noterima;
        $deleteData = $this->crud_m->delete('mtt_muat', array('noterima' => $nomor));
        if ($deleteData) {
            //$this->db->update('mtt_antri', array('status' => 'ANTRI'), array('NoTerima' => $noterima));
            $dataH = array(
                'NoTerima' => $queryH->row()->NOMOR,
                'tglterima' => $queryH->row()->tglterima,
                'nmpenerima' => $queryH->row()->NMPENERIMA,
                'nmpengirim' => $queryH->row()->nmpengirim,
                'STATUS' => $queryH->row()->STATUS
            );
            $this->db->insert('mtt_antri', $dataH);
            $dataD = array();
            $i=0;
            foreach($queryD->result() as $detail) {
                $dataD[$i] = array(
                    'Noterima' => $dataH['NoTerima'],
                    'BANYAK' => $detail->banyak,
                    'Satuan' => $detail->SATUAN,
                    'Barang' => $detail->barang,
                    'JUMLAH' => $detail->jumlah,
                    'SAT' => $detail->SAT,
                    'STATUS' => 'ANTRI',
                    'Ongkos' => $detail->ONGKOS,
                    'jml_ongkos' => $detail->JML_ONGKOS
                );
                $i++;
                //$data[$i]['jml_ongkos'] = str_replace(".", "", $data[$i]['jml_ongkos']);
            }
            $this->db->insert_batch('tt_antri', $dataD);

            echo $this->fungsi->warning('Data telah terhapus', base_url('terima_muat'));
        } else {
            $error = $this->crud_m->result($updateData);
            echo $this->fungsi->warning($error['error'], base_url('terima_muat'));
        }
    }

}
