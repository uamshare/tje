<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penagihan_ongkos extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('penagihan_ongkos_m');
    }

    function index() {
        $data['content'] = 'penagihan_ongkos/list';
        $this->load->view('template', $data);
    }

    function add() {
        $data['content'] = 'penagihan_ongkos/add';
        $this->load->view('template', $data);
    }

    function view($id = '') {
        $data['content'] = 'penagihan_ongkos/edit';
        $no = $this->uri->segment(3) . '/' . $this->uri->segment(4) . '/' . $this->uri->segment(5);
        $data['rowedit'] = $this->penagihan_ongkos_m->getListByid($no);
        $this->load->view('template', $data);
    }

    function get_data() {
        $configs = array(
            'id' => 'm_tagih_no',
            'aColumns' => array('m_tagih_tgl', 'm_tagih_no', 'm_tagih_fk', 'm_cek_tgl', 'NOPOL', 'KETERANGAN'),
            'datamodel' => 'penagihan_ongkos_m',
            'actiontable' => array(
                'print' => array(
                    'href' => 'report/r_penagihan_ongkos',
                    'label' => '<i class="icon-print"></i>',//"<img src='" . base_url() . "/assets/ico/ubah.png' />",
                    'title' => 'Ubah Data',
                    'target' => '_blank'
                ),
                'edit' => array(
                    'href' => 'penagihan_ongkos/view',
                    'label' => '<i class="icon-pencil"></i>',//"<img src='" . base_url() . "/assets/ico/ubah.png' />",
                    'title' => 'Ubah Data'
                ),
                'delete' => array(
                    'href' => 'penagihan_ongkos/delete',
                    'label' => '<i class="icon-remove"></i>',//"<img src='" . base_url() . "/assets/ico/hapus.png' />",
                    'title' => 'Hapus Data',
                    'onclick' => 'return deleteData()'
                )
            )
        );
        //echo "<pre>"; 
        echo $this->crud_m->get_data($configs);
        //echo "</pre>";
    }

    function get_no_kirim($date = '') {
        $pdate = (!empty($date)) ? $date : date('Y-m-d');
        $month = date('m', strtotime($pdate));
        $year = date('Y', strtotime($pdate));

        $query = $this->db->query("SELECT MAX(LEFT(m_tagih_no,4)) as lastno FROM m_tagih WHERE YEAR(m_tagih_tgl)=$year AND MONTH(m_tagih_tgl)=$month");
        $lastno = $query->row()->lastno;

        $lastno = (int) $lastno + 1;

        if ($lastno < 10) {
            $lastno = '000' . $lastno;
        } else if ($lastno < 100) {
            $lastno = '00' . $lastno;
        } else if ($lastno < 1000) {
            $lastno = '0' . $lastno;
        } else if ($lastno < 10000) {
            $lastno = $lastno;
        }
        //echo $lastno;
        $rest['no_kirim'] = $lastno . '/' . $this->fungsi->blnTOromawi($month) . '/' . date('y', strtotime($pdate));
        echo json_encode($rest);
    }

    function get_detail_kirim($edit = false) {
        //var_dump($edit);
        $nopol = $_POST['no_polisi'];
        $result = array();
        $result['grid'] = $this->penagihan_ongkos_m->getDataKirim($nopol, $edit);
        echo json_encode($result);
    }

    function get_nopol() {
        $query = $_POST['query'];
        $result = $this->penagihan_ongkos_m->getNopol($query);
        echo json_encode($result);
    }

    function simpan() {
        $data = array(
            'm_tagih_no' => $this->input->post('m_tagih_no'),
            'm_tagih_tgl' => $this->input->post('m_tagih_tgl'),
            'm_tagih_fk' => $this->input->post('m_tagih_fk')
        );
        $nokirim = $this->input->post('nokirim');

        $stats = $this->input->post('stat');
        //var_dump($stat);

        $insertData = $this->crud_m->insert('m_tagih', $data,true);
        //echo $insertData;
        if ($insertData === true) {
            //echo $this->fungsi->warning('Data telah tersimpan', base_url('users'));
            if (!empty($stats)) {
                foreach ($stats as $key => $val) {
                    $insertDataDetail = $this->crud_m->update('tt_kirim', array('stat' => '3'), array('id' => $val));
                    if (!$insertDataDetail) {
                        $this->crud_m->delete('m_tagih', array('m_tagih_no' => $data['m_tagih_no']));
                        $this->crud_m->update('tt_kirim', array('stat' => '2'), array('nokirim' => $nokirim));
                        $error = $this->crud_m->result($insertDataDetail);
                        $msg['resp'] = 'KESALAHAN SAAT INSERT DETAIL : ' . $error['message'];
                        break;
                    }
                }
            }
            if (isset($msg['resp'])) {
                $msg['success'] = false;
                $msg['resp'] = $msg['resp'];
            } else {
                $qcek = $this->db->query("SELECT nokirim FROM tt_kirim WHERE stat='2' AND nokirim='" . $nokirim . "'")->num_rows();
                //echo $qcek;
                if ($qcek <= 0) {
                    $this->crud_m->update('m_kirim', array('stat' => '3'), array('nokirim' => $nokirim));
                }else{
                    $this->crud_m->update('m_kirim', array('stat' => '2'), array('nokirim' => $nokirim));
                }
                $msg['success'] = true;
                $msg['resp'] = 'SUKSES : Data berhasil disimpan.';
            }
        } else {
            $error = $this->crud_m->result($insertData);
            //echo $this->fungsi->warning($error['message'], base_url('users'));
            $msg['success'] = false;
            $msg['resp'] = 'KESALAHAN SAAT INSERT HEADER : ' . $error['error'];
        }
        echo json_encode($msg);
        //exit();
    }

    function update() {
        $data = array(
            'm_tagih_no' => $this->input->post('m_tagih_no'),
            'm_tagih_tgl' => $this->input->post('m_tagih_tgl'),
            'm_tagih_fk' => $this->input->post('m_tagih_fk')
        );
        $nokirim = $this->input->post('nokirim');
        $stats = $this->input->post('stat');
        //var_dump($stat);

        $insertData = $this->crud_m->update('m_tagih', $data, array('m_tagih_no' => $data['m_tagih_no']),true);
        //echo $insertData;
        $this->crud_m->update('tt_kirim', array('stat' => '2'), array('nokirim' => $nokirim));
        if ($insertData === true) {
            //echo $this->fungsi->warning('Data telah tersimpan', base_url('users'));
            if (!empty($stats)) {
                foreach ($stats as $key => $val) {
                    $insertDataDetail = $this->crud_m->update('tt_kirim', array('stat' => '3'), array('id' => $val));
                    if (!$insertDataDetail) {
                        $this->crud_m->delete('m_tagih', array('m_tagih_no' => $data['m_tagih_no']));
                        $this->crud_m->update('tt_kirim', array('stat' => '2'), array('nokirim' => $nokirim));
                        $error = $this->crud_m->result($insertDataDetail);
                        $msg['resp'] = 'KESALAHAN SAAT INSERT DETAIL : ' . $error['message'];
                        break;
                    }
                }
            }
            if (isset($msg['resp'])) {
                $msg['success'] = false;
                $msg['resp'] = $msg['resp'];
            } else {
                $qcek = $this->db->query("SELECT nokirim FROM tt_kirim WHERE stat='2' AND nokirim='" . $nokirim . "'")->num_rows();
                //echo $qcek;
                if ($qcek <= 0) {
                    $this->crud_m->update('m_kirim', array('stat' => '3'), array('nokirim' => $nokirim));
                } else {
                    $this->crud_m->update('m_kirim', array('stat' => '2'), array('nokirim' => $nokirim));
                }
                $msg['success'] = true;
                $msg['resp'] = 'SUKSES : Data berhasil disimpan.';
            }
        } else {
            $error = $this->crud_m->result($insertData);
            //echo $this->fungsi->warning($error['message'], base_url('users'));
            $msg['success'] = false;
            $msg['resp'] = 'KESALAHAN SAAT INSERT HEADER : ' . $error['error'];
        }
        echo json_encode($msg);
        //exit();
    }

    function delete($id = '') {
        $no = $this->uri->segment(3) . '/' . $this->uri->segment(4) . '/' . $this->uri->segment(5);
        
        $query = $this->db->query("SELECT nokirim FROM v_m_tagih WHERE m_tagih_no = '$no'");
        //var_dump($query->row());
        $nofk = $query->row()->NOKIRIM;
        
        $deleteData = $this->crud_m->delete('m_tagih', array('m_tagih_no' => $no),true);
        if ($deleteData) {
            $this->crud_m->update('tt_kirim', array('stat' => '2'), array('nokirim' => $nofk));
            $this->crud_m->update('m_kirim', array('stat' => '2'), array('nokirim' => $nofk));
            echo $this->fungsi->warning('Data telah terhapus', base_url('penagihan_ongkos'));
        } else {
            $error = $this->crud_m->result($deleteData);
            echo $this->fungsi->warning($error['error'], base_url('penagihan_ongkos'));
        }
    }

}
