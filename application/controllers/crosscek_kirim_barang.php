<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Crosscek_kirim_barang extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('cross_cek_kirim_barang_m');
    }

    function index() {
        $data['content'] = 'cross_cek_kirim_barang/list';
        $this->load->view('template', $data);
    }

    function add() {
        $data['content'] = 'cross_cek_kirim_barang/add';
        $this->load->view('template', $data);
    }

    function view($id = '') {
        $data['content'] = 'cross_cek_kirim_barang/edit';
        $no = $this->uri->segment(3) . '/' . $this->uri->segment(4) . '/' . $this->uri->segment(5);
        $data['rowedit'] = $this->cross_cek_kirim_barang_m->getListByid($no);
        $this->load->view('template', $data);
    }

    function get_data() {
        $configs = array(
            'id' => 'm_cek_no',
            'aColumns' => array('m_cek_tgl', 'm_cek_no', 'NOKIRIM', 'TGLKIRIM', 'NOPOL', 'KETERANGAN'),
            'datamodel' => 'cross_cek_kirim_barang_m',
            'actiontable' => array(
                'print' => array(
                    'href' => 'report/r_crosscek_kirim_barang',
                    'label' => '<i class="icon-print"></i>',//"<img src='" . base_url() . "/assets/ico/ubah.png' />",
                    'title' => 'Ubah Data',
                    'target' => '_blank'
                ),
                'edit' => array(
                    'href' => 'crosscek_kirim_barang/view',
                    'label' => '<i class="icon-pencil"></i>',//"<img src='" . base_url() . "/assets/ico/ubah.png' />",
                    'title' => 'Ubah Data'
                ),
                'delete' => array(
                    'href' => 'crosscek_kirim_barang/delete',
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

        $query = $this->db->query("SELECT MAX(LEFT(m_cek_no,4)) as lastno FROM m_cek WHERE YEAR(m_cek_tgl)=$year AND MONTH(m_cek_tgl)=$month");
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

    function get_detail_kirim($edit = faLSE) {
        //var_dump($edit);
        $nopol = $_POST['no_polisi'];
        $result = array();
        $result['grid'] = $this->cross_cek_kirim_barang_m->getDataKirim($nopol, $edit);
        echo json_encode($result);
    }

    function get_nopol() {
        $query = $_POST['query'];
        $result = $this->cross_cek_kirim_barang_m->getNopol($query);
        echo json_encode($result);
    }

    function simpan() {
        $data = array(
            'm_cek_no' => $this->input->post('m_cek_no'),
            'm_cek_tgl' => $this->input->post('m_cek_tgl'),
            'm_cek_fk' => $this->input->post('m_cek_fk')
        );

        $stats = $this->input->post('stat');
        //var_dump($stat);

        $insertData = $this->crud_m->insert('m_cek', $data);
        //echo $insertData;
        if ($insertData === true) {
            //echo $this->fungsi->warning('Data telah tersimpan', base_url('users'));
            if (!empty($stats)) {
                foreach ($stats as $key => $val) {
                    $insertDataDetail = $this->crud_m->update('tt_kirim', array('stat' => '2'), array('id' => $val));
                    if (!$insertDataDetail) {
                        $this->crud_m->delete('m_cek', array('m_cek_no' => $data['m_cek_no']));
                        $this->crud_m->update('tt_kirim', array('stat' => '1'), array('nokirim' => $data['m_cek_fk']));
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
                $qcek = $this->db->query("SELECT nokirim FROM tt_kirim WHERE stat='1' AND nokirim='" . $data['m_cek_fk'] . "'")->num_rows();
                //echo $qcek;
                if ($qcek <= 0) {
                    $this->crud_m->update('m_kirim', array('stat' => '2'), array('nokirim' => $data['m_cek_fk']));
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
            'm_cek_no' => $this->input->post('m_cek_no'),
            'm_cek_tgl' => $this->input->post('m_cek_tgl'),
            'm_cek_fk' => $this->input->post('m_cek_fk')
        );
        $parentstat = $this->input->post('parentstat');
        $stats = $this->input->post('stat');
        //var_dump($parentstat);

        if ($parentstat == '3') {
            $msg['success'] = false;
            $msg['resp'] = 'PERINGATAN : Data sudah terpakai ditransaksi lain.';
        } else {
            $insertData = $this->crud_m->update('m_cek', $data, array('m_cek_no' => $data['m_cek_no']));
            //echo $insertData;

            if ($insertData === true) {
                //echo $this->fungsi->warning('Data telah tersimpan', base_url('users'));
                if (!empty($stats)) {
                    $this->crud_m->update('tt_kirim', array('stat' => '1'), array('nokirim' => $data['m_cek_fk']));
                    foreach ($stats as $key => $val) {
                        $insertDataDetail = $this->crud_m->update('tt_kirim', array('stat' => '2'), array('id' => $val));
                        if (!$insertDataDetail) {
                            $this->crud_m->delete('m_cek', array('m_cek_no' => $data['m_cek_no']));
                            $this->crud_m->update('tt_kirim', array('stat' => '1'), array('nokirim' => $data['m_cek_fk']));
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
                    $qcek = $this->db->query("SELECT nokirim FROM tt_kirim WHERE stat='1' AND nokirim='" . $data['m_cek_fk'] . "'")->num_rows();
                    //echo $qcek;
                    if ($qcek <= 0) {
                        $this->crud_m->update('m_kirim', array('stat' => '2'), array('nokirim' => $data['m_cek_fk']));
                    } else {
                        $this->crud_m->update('m_kirim', array('stat' => '1'), array('nokirim' => $data['m_cek_fk']));
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
        }
        //return;

        echo json_encode($msg);
        //exit();
    }

    function delete($id = '') {
        $no = $this->uri->segment(3) . '/' . $this->uri->segment(4) . '/' . $this->uri->segment(5);

        $query = $this->db->query("SELECT m_cek_fk FROM m_cek WHERE m_cek_no = '$no' AND m_cek_no NOT IN (SELECT m_tagih_fk FROM m_tagih)");
        //echo $query->num_rows();
        if ($query->num_rows() > 0) {
            $nofk = $query->row()->m_cek_fk;

            $deleteData = $this->crud_m->delete('m_cek', array('m_cek_no' => $no));
            if ($deleteData) {
                $this->crud_m->update('tt_kirim', array('stat' => '1'), array('nokirim' => $nofk));
                $this->crud_m->update('m_kirim', array('stat' => '1'), array('nokirim' => $nofk));
                echo $this->fungsi->warning('Data telah terhapus', base_url('crosscek_kirim_barang'));
            } else {
                $error = $this->crud_m->result($updateData);
                echo $this->fungsi->warning($error['error'], base_url('crosscek_kirim_barang'));
            }
            //echo $this->fungsi->warning('Data telah terhapus', base_url('crosscek_kirim_barang'));
        } else {
            echo $this->fungsi->warning('PERINGATAN : Data sudah terpakai ditransaksi lain.', base_url('crosscek_kirim_barang'));
        }
    }

}
