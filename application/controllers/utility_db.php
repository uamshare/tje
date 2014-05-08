<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Utility_db extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('download');
        $this->load->library('dblib');
    }

    function index() {
        $this->LogBackUp();
    }

    function backup() {
        $namafile = 'backup-tje_' . date('Ymdhis');
        //echo $namafile;
        $this->dbbackup($namafile);
    }

    function restore() {
        $data['content'] = 'utility_db/restore';
        $this->load->view('template', $data);
    }

    function dbbackup($namafile) {
        //echo $namafile;
        if (!file_exists('C:\\xampp\mysql\\bin\\')) {
            echo "mysql not found";
            return;
        }
        $this->dblib->setfilepath('backup/db/');
        $this->dblib->setfilename('backup' . date('ymd'));
        $this->dblib->setpathmysqlbin('C:\\xampp\mysql\\bin\\');
        $this->dblib->backup();
        $data = file_get_contents($this->dblib->getfilename()); // Read the file's contents
        $namafile = str_replace("%20", "_", $namafile);
        $name = $namafile . '.sql';
        force_download($name, $data);
    }

    function dbrestore() {
        if (!file_exists('C:\\xampp\mysql\\bin\\')) {
            echo "mysql not found";
            return;
        }
        $hasil = $this->do_upload();
        if ($hasil['return']) {
            $this->dblib->setfilepath('backup/db/restore/');
            $this->dblib->setfilename($hasil['filename']);
            //$this->dblib->setfilename("File_ERP.sql");
            $this->dblib->setpathmysqlbin('C:\\xampp\mysql\\bin\\');
            $this->dblib->restore();
        }
        //echo '{success:true, message: "'.$this->dblib->getfilename().'"}';
        //echo $hasil['json'];
        //var_dump($hasil);
        echo $this->fungsi->warning($hasil['message'], base_url('utility_db/restore'));
    }

    function do_upload() {
        //var_dump($_FILES);
        $foldername = 'backup/db/restore';
        $config['upload_path'] = ( $foldername . '/');
        $config['allowed_types'] = 'sql|txt|'; //'sql|zip|txt|gz|rar';
        $config['overwrite'] = TRUE;
        $config['max_size'] = '102400';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $config['mimes'] = array(
            'sql' => 'text/x-sql',//'application/octet-stream', //
            'zip' => array('application/x-zip', 'application/zip', 'application/x-zip-compressed'),
            'rar' => array('application/x-rar', 'application/rar', 'application/x-rar-compressed'),
            'txt' => 'text/plain',
            'gz' => 'application/x-gzip'
        );
        //echo 'path : '.$config['upload_path'];
        if (!file_exists($config['upload_path'])) {
            $create = mkdir($config['upload_path'], 0777);
            $createThumbsFolder = mkdir($config['upload_path'] . '/thumbs', 0777);
            if (!$create || !$createThumbsFolder)
                return;
        }
        $datahasil = array();
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('restorefile')) {
            $error = $this->upload->display_errors();
            $success = 'false';
            $ret = false;
        } else {
            $upload_data = $this->upload->data();
            $error = 'Restore Database Sucsess';
            $success = 'true';
            $ret = true;
            $datahasil['filename'] = $upload_data['file_name'];
        }
        $hasil = '{success:' . $success . ', message: "' . $error . '"}';
        ; //array('success'=>$success,'message'=>$error);
        $datahasil['return'] = $ret;
        $datahasil['json'] = $hasil;
        $datahasil['message'] = $error;
        return $datahasil;
    }

}

?>
