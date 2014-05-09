<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('phpjasperxml');
    }

    function index() {
        $data['content'] = 'cross_cek_kirim_barang/list';
        $this->load->view('template', $data);
    }

    function r_terima_barang($noterima) {
        //echo "tes";
        //$this->phpjasperxml->tescoba();
        $xml = simplexml_load_file(base_url()."assets/reportfile/terima_barang.jrxml");
        $this->phpjasperxml = new PHPJasperXML();
//$this->phpjasperxml->debugsql=true;
//$this->phpjasperxml->arrayParameter=array("parameter1"=>1);
        $this->phpjasperxml->xml_dismantle($xml);
        $this->phpjasperxml->queryString_handler("SELECT CONCAT(MTT_ANTRI.NOTERIMA,' ')AS NOTERIMA,MTT_ANTRI.TGLTERIMA,M_PENGIRIM.NMPENGIRIM,(M_PENGIRIM.ALAMAT)AS ALAMAT1,M_PENERIMA.NMPENERIMA,(M_PENERIMA.ALAMAT)AS ALAMAT2,
TT_ANTRI.BANYAK,TT_ANTRI.BANYAK,TT_ANTRI.SATUAN,TT_ANTRI.BARANG,TT_ANTRI.JUMLAH,TT_ANTRI.SAT,TT_ANTRI.STATUS 
FROM (MTT_ANTRI INNER JOIN TT_ANTRI ON MTT_ANTRI.NOTERIMA=TT_ANTRI.NOTERIMA),M_PENERIMA,M_PENGIRIM 
WHERE MTT_ANTRI.NMPENGIRIM=M_PENGIRIM.NMPENGIRIM AND MTT_ANTRI.NMPENERIMA=M_PENERIMA.NMPENERIMA AND 
MTT_ANTRI.NOTERIMA='$noterima' AND TT_ANTRI.STATUS='ANTRI'");

        $this->phpjasperxml->transferDBtoArray($this->db->hostname, $this->db->username, $this->db->password, $this->db->database);
        $this->phpjasperxml->outpage("I");    //page output method I:standard output  D:Download file
    }
    
    function r_terima_muat($noterima) {
        //echo "tes";
        //$this->phpjasperxml->tescoba();
        $noterima = $this->uri->segment(3) . '/' . $this->uri->segment(4) . '/' . $this->uri->segment(5);
        //echo $noterima;
        $xml = simplexml_load_file(base_url()."assets/reportfile/terima_muat.jrxml");
        $this->phpjasperxml = new PHPJasperXML();
//$this->phpjasperxml->debugsql=true;
//$this->phpjasperxml->arrayParameter=array("parameter1"=>1);
        $this->phpjasperxml->xml_dismantle($xml);
        $this->phpjasperxml->queryString_handler("SELECT MTT_MUAT.NOTERIMA,MTT_MUAT.TGLTERIMA,MTT_MUAT.NOPOL,MTT_MUAT.NMPENGIRIM,(M_PENGIRIM.ALAMAT)AS ALAMAT1,
MTT_MUAT.NMPENERIMA,(M_PENERIMA.ALAMAT)AS ALAMAT2,MTT_MUAT.STATUS,TT_MUAT.BANYAK,TT_MUAT.SATUAN,TT_MUAT.BARANG,TT_MUAT.JUMLAH,TT_MUAT.SAT,TT_MUAT.ONGKOS,TT_MUAT.JML_ONGKOS,CONCAT(MTT_MUAT.NOMOR,' ') AS NOMOR
FROM (MTT_MUAT INNER JOIN TT_MUAT ON MTT_MUAT.NOTERIMA=TT_MUAT.NOTERIMA),M_PENGIRIM,M_PENERIMA
WHERE MTT_MUAT.NMPENGIRIM=M_PENGIRIM.NMPENGIRIM AND MTT_MUAT.NMPENERIMA=M_PENERIMA.NMPENERIMA AND MTT_MUAT.NOTERIMA='$noterima'");

        $this->phpjasperxml->transferDBtoArray($this->db->hostname, $this->db->username, $this->db->password, $this->db->database);
        $this->phpjasperxml->outpage("I");    //page output method I:standard output  D:Download file
    }
    
    function r_crosscek_kirim_barang($no=''){
        $no = $this->uri->segment(3) . '/' . $this->uri->segment(4) . '/' . $this->uri->segment(5);
        $xml = simplexml_load_file(base_url()."assets/reportfile/crosscek_kirim_barang.jrxml");
        $this->phpjasperxml = new PHPJasperXML();
//$this->phpjasperxml->debugsql=true;
//$this->phpjasperxml->arrayParameter=array("parameter1"=>1);
        $this->phpjasperxml->xml_dismantle($xml);
        $this->phpjasperxml->queryString_handler("SELECT h.*,d.NOTERIMA,d.NOMUAT,d.BANYAK,d.BARANG,d.JUMLAH,
                                                    d.SATUAN,d.NMPENGIRIM,d.NMPENERIMA,d.STATUS,d.stat,d.statname,
                                                    d.jml_ongkos FROM v_m_cek h INNER JOIN v_tt_cek d
                                                    ON h.NOKIRIM=d.NOKIRIM WHERE h.m_cek_no='$no'");

        $this->phpjasperxml->transferDBtoArray($this->db->hostname, $this->db->username, $this->db->password, $this->db->database);
        $this->phpjasperxml->outpage("I");
    }
    
    function r_penagihan_ongkos($no=''){
        $no = $this->uri->segment(3) . '/' . $this->uri->segment(4) . '/' . $this->uri->segment(5);
        $xml = simplexml_load_file(base_url()."assets/reportfile/penagihan_ongkos.jrxml");
        $this->phpjasperxml = new PHPJasperXML();
//$this->phpjasperxml->debugsql=true;
//$this->phpjasperxml->arrayParameter=array("parameter1"=>1);
        $this->phpjasperxml->xml_dismantle($xml);
        $this->phpjasperxml->queryString_handler("SELECT h.*,d.NOTERIMA,d.NOMUAT,d.BANYAK,d.BARANG,d.JUMLAH,
                                                    d.SATUAN,d.NMPENGIRIM,d.NMPENERIMA,d.STATUS,d.stat,d.statname,
                                                    d.jml_ongkos FROM v_m_tagih h INNER JOIN v_tt_tagih d
                                                    ON h.NOKIRIM=d.NOKIRIM WHERE h.m_tagih_no='$no'");

        $this->phpjasperxml->transferDBtoArray($this->db->hostname, $this->db->username, $this->db->password, $this->db->database);
        $this->phpjasperxml->outpage("I");
    }

}