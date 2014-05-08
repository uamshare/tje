<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of crud_m
 *
 * @author user
 */
class Crud_m extends CI_Model {

    var $CI = null;

    function __construct() {
        parent::__construct();
        //$this->db->where('user_id', $this->session->userdata('erp_user_id'));
        //$this->db->update('online', array('last_activity' => date('Y-m-d H:i:s')));
    }

    function insert($namaTabel, $data, $log = false) {
        $result = $this->db->insert($namaTabel, $data);
        if ($result == true) {
            if ($log == true)
                $this->privileges->catat($data, '', 'insert', true);
            return $result;
        } else {
            return $this->db->_error_message();
        }
    }

    function update($namaTabel, $data, $where, $log = false) {
        $result = $this->db->update($namaTabel, $data, $where);
        if ($log == true) {
            $this->privileges->catat($data, '', 'update', true);
        }
        if (!empty($result) && $result == true) {
            return $result;
        } else {
            //echo 'Terst'.$this->db->_errornumber();
            return $this->db->_error_message();
        }
    }

    function delete($namaTabel, $where, $log = false, $child = false) {
        $namaTabelChild = '';
        $sql = $this->db->get_where($namaTabel, $where);
        $old_data = $sql->row_array();
//        if ($log == true && $child = true) {
//            $namaTabelChild = substr($namaTabel, 0, strlen($namaTabel) - 1);
//            $sql_child = $this->db->get_where($namaTabelChild, $where);
//            $old_data_child = $sql_child->result();
//        }
        //$hasil = $this->db->update($namaTabel,array('isdelete'=>'1'),$where);
        $hasil = $this->db->delete($namaTabel, $where);
        if (!empty($hasil) && $hasil == true) {
            if ($log == true) {
                $this->privileges->catat($old_data, '', 'delete', true);
//                $sampah['pkey'] = (isset($old_data['no'])) ? $old_data['no'] : $old_data['id_brg'];
//                $sampah['name_table'] = $namaTabel;
//                $sampah['name_table_child'] = $namaTabelChild;
//                $sampah['user_input'] = $old_data['id_user'];
//                $sampah['user_delete'] = $this->session->userdata('erp_user_id');
//                $sampah['time'] = date('Y-m-d H:i:s', time());
//
//
//
//                $lostdata['header'] = json_encode($old_data);
//                $lostdata['detail'] = json_encode($old_data_child);
//                $sampah['data'] = json_encode($lostdata);
//
//                $this->movetoSampah($sampah);
            }
            return true;
        } else {
            //echo 'no : '.$this->db->_errornumber();
            return $this->db->_error_message();
        }
    }

    function realDelete($namaTabel, $where) {
        $old_data = $this->db->get_where($namaTabel, $where)->result();

        $hasil = $this->db->delete($namaTabel, $where);
        if (!empty($hasil) && $hasil == true) {
            $this->privileges->catat($old_data, '', 'delete', true);
            return true;
        } else {
            return $this->db->_error_message();
        }
    }

    function result($result = false) {
        $user_message = '';
        if ($result === true) {
            $ret = array(
                'success' => true
            );
        } else {
            $erno = $this->db->_error_number(); 
            //echo $erno;
            switch ($erno) {
                default :
                    $ret = array(
                        'success' => false,
                        'error' => !empty($result) ? $result : $this->db->_error_message(),
                    );
                    break;
                case 1451 :
                    $user_message = 'Nomor transaksi yg anda pilih telah digunakan sebagai referensi di transaksi yang lain.<br> 
											Hapus terlebih dahulu transaksi yang menggunakan no tersebut';
                    $ret = array(
                        'success' => false,
                        'error' => $user_message//.$this->db->_error_message()
                    );
                    break;
                case 1062 :
                    //echo $erno;
                    $user_message = 'Nomor transaksi yg anda masukan sudah terpakai!';
                    $ret = array(
                        'success' => false,
                        'error' => $user_message
                    );
                    break;
            }
            // Catat Error Database yang terjasi
            //$kegiatan = 'Error Db No : ' . $this->db->_error_number() . '<br>' .
//                    'Error Message : ' . $this->db->_error_message() . '<br>' .
//                    'User Message : ' . $user_message . '<br>' .
//                    'User Active : ' . $this->session->userdata(SESS_PREFIK . 'nama') . '<br>' .
//                    'Controller Name : ' . $this->uri->segment(1) . '<br>' .
//                    'Fumnction Name : ' . $this->uri->segment(2);
//
//            $this->privileges->catat($kegiatan, '', 'error');
        }
        return $ret;
    }

    function movetoSampah($sampah) {

        return $this->db->insert('t_sampah', $sampah);
    }

    public function get_data($configs) {

        $default = array(
            'id' => 'id',
            'aColumns' => array(),
            'sLimit' => '',
            'sOrder' => '', // Pdf,Docx dll
            'sWhere' => array(),
            'datamodel' => '',
            'actiontable' => ''
        );
        //Set Aksi Tabel


        if (is_array($configs)) {

            foreach ($configs as $key => $val) {
                if (isset($default[$key])) {
                    $default[$key] = $val;
                }
            }
        }
        

        //$default['aColumns'] = array('nopol', 'merk', 'jenis', 'warna', 'supir');
        /*
         * Paging
         */
        //$sLimit = "";
        if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
            $default['sLimit'] = "LIMIT " . intval($_POST['iDisplayStart']) . ", " .
                    intval($_POST['iDisplayLength']);
        }

        /*
         * Ordering
         */
        //$sOrder = "";
        if (isset($_POST['iSortCol_0'])) {
            $default['sOrder'] = "ORDER BY  ";
            for ($i = 0; $i < intval($_POST['iSortingCols']); $i++) {
                if ($_POST['bSortable_' . intval($_POST['iSortCol_' . $i])] == "true") {
                    $default['sOrder'] .= "`" . $default['aColumns'][intval($_POST['iSortCol_' . $i])] . "` " .
                            ($_POST['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $default['sOrder'] = substr_replace($default['sOrder'], "", -2);
            if ($default['sOrder'] == "ORDER BY") {
                $default['sOrder'] = "";
            }
        }
        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        //$sWhere = "";
        if (isset($_POST['sSearch']) && $_POST['sSearch'] != "") {
            $default['sWhere'] = "WHERE (";
            for ($i = 0; $i < count($default['aColumns']); $i++) {
                if (isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true") {
                    $default['sWhere'] .= "`" . $default['aColumns'][$i] . "` LIKE '%" . mysql_real_escape_string($_POST['sSearch']) . "%' OR ";
                }
            }
            $default['sWhere'] = substr_replace($default['sWhere'], "", -3);
            $default['sWhere'] .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($default['aColumns']); $i++) {
            if (isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true" && $_POST['sSearch_' . $i] != '') {
                if ($default['sWhere'] == "") {
                    $default['sWhere'] = "WHERE ";
                } else {
                    $default['sWhere'] .= " AND ";
                }
                $default['sWhere'] .= "`" . $default['aColumns'][$i] . "` LIKE '%" . mysql_real_escape_string($_POST['sSearch_' . $i]) . "%' ";
            }
        }
        /* Output */
        //echo $default['datamodel'];
        //$model = $default['datamodel'];
        if (!empty($default['actiontable']))$default['aColumns'][] = 'actiontable';
        $this->load->model($default['datamodel']);
        $data = $this->$default['datamodel']->getData($default['sWhere'], $default['sOrder'], $default['sLimit']);
        $dataRow = array();
        $dataRow = $data['data'];

        if (!empty($default['actiontable'])) {
            $actiontable = $default['actiontable'];
            foreach ($dataRow as $keyd => $vald) {
//                $aksi = "<a href='".$actiontable['url'].$dataRow[$key][$default['id']]."' title='Ubah'>
//                            <img src='" . base_url() . "/assets/ico/ubah.png' >
//                         </a>    
//                         <a href='users/delete/{$dataRow[$key][$default['id']]}' onclick='return deleteData(this)' title='Hapus'>
//                                <img src='" . base_url() . "/assets/ico/hapus.png'>
//                         </a>";
                $aksi = '';
                foreach ($actiontable as $elemens) {
                    if (isset($elemens['tag'])) {
                        $aksi .= "<" . $elemens['tag'] . ' ';
                    } else {
                        $aksi .= "<a ";
                    }
                    foreach ($elemens as $key => $val) {
                        if ($key != 'label') {
                            if ($key == 'href') {
                                $aksi .= $key . '="' . $val . '/' . $dataRow[$keyd][$default['id']] . '" ';
                            } else {
                                $aksi .= $key . '="' . $val . '" ';
                            }
                        }
                    }
                    $aksi .= '>';
                    if (isset($elemens['label']))
                        $aksi .= $elemens['label'];
                    if (isset($el['tag'])) {
                        $aksi .= "</" . $elemens['tag'] . '>';
                    } else {
                        $aksi .= "</a>&nbsp";
                    }
                }
                //$aksi = htmlentities($aksi);
                //echo "<br/><br/><br/>".$aksi;
                //return;
                $dataRow[$keyd]['actiontable'] = $aksi;
            }
        }
//        var_dump($dataRow);
//        return;
        $iTotal = count($dataRow);
        $iFilteredTotal = $data['total'];
        $output = array(
            "sEcho" => intval($_POST['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        //var_dump($default['aColumns']);
        foreach ($dataRow as $aRow) {
            $row = array();
            for ($i = 0; $i < count($default['aColumns']); $i++) {
                $row[] = $aRow[$default['aColumns'][$i]];
            }
            $output['aaData'][] = $row;
        }
        return json_encode($output);
    }

//    function cetak($configs) {
//        $default = array(
//            'reportPath' => '',
//            'strSql' => '',
//            'format' => '', // Pdf,Docx dll
//            'paramsReport' => array(),
//            'imagePath' => '',
//        );
//        if (is_array($configs)) {
//
//            foreach ($configs as $key => $val) {
//                if (isset($default[$key])) {
//                    $default[$key] = $val;
//                }
//            }
//            $params = array('hostname' => '192.168.1.91', 'port' => '8080');
//            $this->load->library('jasperreportphp', $params); //Inisialisasi class
//            $report = $this->jasperreportphp; // Instansiasi Class
//            //$path = (!empty($path)) ? $path : FCPATH.'public/reports/sales/rekap_do.jrxml'; //Default Path 
//            //$format = 'pdf',$strSql,$paramsReport,$path = '';
//
//            $report->setQuerySql($default['strSql']);
//            $report->setFileReportName(FCPATH . $default['reportPath']); // Set Path File Report
//            $report->setPathImage(base_url() . $default['imagePath']); // Set Path Image
//            $report->setParamReport($default['paramsReport']); // Set Parameter Report
//
//            $report->Output($default['format']); //Export  Report
//
//            /*             * ***************
//              /*Catat Kegiatan*******
//             * ********* */
//            if (!isset($default['paramsReport']['filterTgl'])) {
//                $default['paramsReport']['filterTgl'] = '';
//            }
//            $kegiatan = 'Report Title : ' . $default['paramsReport']['title'] . '<br>' .
//                    'Report Periode : ' . $default['paramsReport']['filterTgl'] . '<br>' .
//                    'Controller Name : ' . $this->uri->segment(1) . '<br>' .
//                    'Fumnction Name : ' . $this->uri->segment(2);
//
//            $this->privileges->catat($kegiatan, '', 'print');
//        }
//    }
//    
//    function getPostSession(){
//        $dataSession = array();
//        $postKlss = $this->input->post('id_kelas') ? $this->input->post('id_kelas') : $this->uri->segment(4);
//        $semester = $this->input->post('semester') ? $this->input->post('semester') : $this->uri->segment(5);
//        $tahun = $this->input->post('id_tahun') ? $this->input->post('id_tahun') : $this->uri->segment(3);
//        $id_rombel = $this->input->post('id_rombel') ? $this->input->post('id_rombel') : $this->uri->segment(6);
//        
//        if($postKlss){
//            $postKls = explode("-", $postKlss);
//        }else{
//            $postKls[0] = '';
//            $postKls[1] = '';
//        }
//        
//        $dataSession['tingkat'] = ($postKls[0]) ? $postKls[0] : $this->session->userdata(SESS_PREFIK . 'tingkat');
//        $this->session->set_userdata(SESS_PREFIK . 'tingkat', $dataSession['tingkat']);
//        
//        $dataSession['id_kelas'] = ($postKls[1]) ? $postKls[1] : $this->session->userdata(SESS_PREFIK . 'kelas');
//        $this->session->set_userdata(SESS_PREFIK . 'kelas', $dataSession['id_kelas']);
//        
//        $dataSession['semester'] = ($semester) ? $semester : $this->session->userdata(SESS_PREFIK . 'semester');
//        $this->session->set_userdata(SESS_PREFIK . 'semester', $dataSession['semester']);
//        
//        $dataSession['id_tahun'] = ($tahun) ? $tahun : $this->session->userdata(SESS_PREFIK . 'tahun_ajaran');
//        $this->session->set_userdata(SESS_PREFIK . 'tahun_ajaran', $dataSession['id_tahun']);
//        
//        $dataSession['id_rombel'] = ($id_rombel) ? $id_rombel : $this->session->userdata(SESS_PREFIK . 'id_rombel');
//        $this->session->set_userdata(SESS_PREFIK . 'id_rombel', $dataSession['id_rombel']);
//        
//        return $dataSession;
//    }
}

?>
