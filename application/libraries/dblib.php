<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DbLib {

    private $CI = null;
    private $dbName;
    private $dbHost;
    private $dbUser;
    private $dbPassword;
    private $dbPort = 3306;
    private $filename = '';
    private $filepath = '';
    private $backup = 'mysqldump';
    private $restore = 'mysql';

    function __construct() {

        try {
            $this->CI = & get_instance();
            $this->CI->load->library('fungsi');
            $this->dbHost = $this->CI->db->hostname;
            $this->dbUser = $this->CI->db->username;
            $this->dbPassword = $this->CI->db->password;
            $this->dbName = $this->CI->db->database;
            //$this->dbName		= 'prima_test';
            //$this->filename         = $this->dbName;
        } catch (Exception $e) {
            throw new Exception('Something really gone wrong', 0, $e);
        }
    }

    public function setpathmysqlbin($path = '') { //Set bin PATH jika OS Windows
        $this->backup = $path . $this->backup;
        $this->restore = $path . $this->restore;
    }

    public function setfilepath($filepath = '') {
        $this->filepath = realpath($filepath);
        // echo 'real Path : '.$this->filepath;
    }

    public function setfilename($filename = '') {
        $this->filename = $this->filepath . '\\' . $filename; //.'.sql';
        $this->filename = $this->CI->fungsi->realPath($this->filename);
        //echo $this->filename;
    }

    public function getfilename() {
        return $this->CI->fungsi->realPath($this->filename);
    }

    public function backup() {
//        echo $this->backup.' --routines --host='.$this->dbHost.
//                        ' --user='.$this->dbUser.
//                        ' --password='.$this->dbPassword.' '.$this->dbName.' > '.$this->filename;
        exec($this->backup . ' --routines --host=' . $this->dbHost .
                ' --user=' . $this->dbUser .
                ' --password=' . $this->dbPassword . ' ' . $this->dbName . ' > ' . $this->filename);
    }

    public function restore() {
        exec($this->restore . ' --host=' . $this->dbHost .
                ' --user=' . $this->dbUser .
                ' --password=' . $this->dbPassword . ' ' . $this->dbName . ' < ' . $this->filename);
    }

}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
