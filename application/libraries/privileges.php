<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Privileges {

    var $CI = null;

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->load->library(array('loader', 'fungsi'));
    }

    function checkIP() {
        $domain = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        $this->CI->db->from('allowed_ip');
        $this->CI->db->where('ip_address', $domain);
        $exist = $this->CI->db->count_all_results();
        if ($exist == 0) {
            /* NTAR DIAKTIFKAN LAGI */
            /* echo $this->CI->fungsi->warning($this->CI->config->item('project_name').' tidak memperbolehkan Login di Area Anda',site_url()); */
            //die(); 
        }
    }

    function stat_Online($id) {
        $this->CI->db->where('user_id', $id);
        $this->CI->db->from('online');
        return $this->CI->db->count_all_results();
    }

    function getLastActivity($id) {
        $this->CI->db->select('last_activity');
        $this->CI->db->from('online');
        $this->CI->db->where('user_id', $id);
        $data = $this->CI->db->get();
        $row = $data->row();
        return $row->last_activity;
    }

    function getDiff($old, $now) {
        if ($old == '' OR $now == '') {
            return TRUE;
        }
        //echo $old;

        $old_y = date('Y', $old);
        $old_m = date('n', $old);
        $old_d = date('j', $old);
        $old_g = date('G', $old);
        $old_i = date('i', $old);
        //$old_s = date('s',$old);
        $now_y = date('Y', $now);
        $now_m = date('n', $now);
        $now_d = date('j', $now);
        $now_g = date('h', $now);
        $now_i = date('i', $now);
        //$now_s = date('s',$now);
        //start checking
        //echo 'Test '.$now_i.' '.$old_i;

        if ($now_y != $old_y) {
            return TRUE;
        }
        if ($now_m != $old_m) {
            return TRUE;
        }
        if ($now_d != $old_d) {
            return TRUE;
        }
        if ($now_g != $old_g) {
            return TRUE;
        }
        // ignore second
        $diff_minute = $now_i - $old_i;
        if ($diff_minute >= 10) {
            return TRUE;
        }
        return FALSE;
    }
    
    function cekAksesMenu($keyMenu){
        $sessKeyMenu = $this->CI->session->userdata(SESS_PREFIK.'key_menu');
        $has= 0;
        foreach ($sessKeyMenu as $value){
            if($keyMenu==$value->keymenu){
                $has=1;
            }
        };
        return $has;
        
    }
    
    function is_allowed_page(){
        $sessKeyMenu = $this->CI->session->userdata(SESS_PREFIK.'key_menu');
        $has= false;
        $controller = $this->CI->uri->segment(1);
        if($this->CI->uri->segment(2)){
            $controller .= '/'.$this->CI->uri->segment(2);
        }
        foreach ($sessKeyMenu as $value){
            if($controller == $value->controller){
                $has = true;
            }
        };
        return $has;
        
    }

    function process_login($login = NULL) {
        // A few safety checks
        // Our array has to be set
        $this->checkIP();
        if (!isset($login)) {
            //$hasil = array('success' => 'false','message' => 'Login gagal, Ip Not Registered');
            $hasil = array('success' => false, 'message' => 'Login gagal, data login tidak boleh kosong');
            return $hasil;
        }
        //Our array has to have 2 values
        //No more, no less!
        if (count($login) != 2) {
            $hasil = array('success' => false, 'message' => 'Login gagal, username atau password salah');
            return $hasil;
        }

        $username = $login['username'];
        $password = $this->CI->encrypt->sha1($login['password']);

        $this->CI->db->from('users');
        $this->CI->db->where('user_username', $username);
        $this->CI->db->where('user_aktif', '1');
        $this->CI->db->where("user_password", $password);
        $query = $this->CI->db->get();

        //var_dump($query->result());
        //echo $query->num_rows();
        $user_id = '';
        foreach ($query->result() as $row) {
            $user_id = $row->user_id;
            $username = $row->user_username;
            $level = $row->user_level;
            $namafull = $row->user_name;
            $count = $row->user_logincount;
            $status_online = $this->stat_Online($user_id);

            //$this->session->set_userdata('usergrup', $row['user_level']);
            //$jabatan 		= $row->user_jabatan;
//            echo "test";
//            $count++;
//              if($status_online == 1){
//                $now = time();
//                $old = strtotime($this->getLastActivity($user_id));
//                
//                if(!$this->getDiff($old,$now)){
//                      $pesan = 'Anda masih tercatat dalam database sebagai user online.
//                      <br>Ini mungkin terjadi karena :
//                      <br/>
//                      <br>1. Anda belum \'Logout\' pada waktu terakhir kali Anda login, atau
//                      <br>2. Ada orang lain yang sedang menggunakan user Anda.
//                      <br>
//                      <p align=justify>
//                      Jika kemungkinan pertama memang benar, Anda hanya perlu menunggu sekitar 10 menit dari sejak
//                      aktivitas terakhir Anda. Jika 10 menit berselang namun Anda masih tetap tidak
//                      bisa login, maka kemungkinan kedua bisa jadi benar. Jika Anda tidak yakin, silakan hubungi
//                      Administrator untuk konfirmasi. Hal ini penting untuk mengindari adanya pemakaian user oleh
//                      orang yang tidak bertanggung jawab.<br><br></p>';
//
//                      $hasil = array('success' => false,'message' => $pesan);
//                      $this->CI->session->set_userdata($hasil);
//                      return $hasil;
//
//                }else{
//                      $this->CI->db->delete('online',array('user_id'=>$user_id));
//                }
//              }

        }

        // die();
        $this->CI->db->select('lm.keymenu, lm.controller');
        $this->CI->db->join('listmenu lm', 'lm.menu_id = ma.menu_id');
        $this->CI->db->where(array('ma.user_id'=>  $user_id));
        $dataKeyMenu = $this->CI->db->get('menu_akses ma')->result();
        if ($query->num_rows() == 1) {
            $newdata = array(
                SESS_PREFIK . 'user_id' => $user_id,
                SESS_PREFIK . 'username' => $username,
                SESS_PREFIK . 'level' => $level,
                SESS_PREFIK . 'key_menu' => $dataKeyMenu,
                
                SESS_PREFIK . 'usergrup' => $level,
                SESS_PREFIK . 'nama' => $namafull,
                //SESS_PREFIK +'erp_jabatan'  => $jabatan,
                SESS_PREFIK . 'logged_in' => TRUE,
                SESS_PREFIK . 'login_ke' => $count,
            );
            //echo $newdata['user_id'];
            // Our user exists, set session.
            $this->CI->session->set_userdata($newdata);
            $kegiatan = 'Login ' . $this->CI->config->item('project_name') . ' by ' . $namafull;
            $this->catat($kegiatan, '', 'login');
            // update counter login
            $this->CI->db->where('user_id', $user_id);
            $this->CI->db->update('users', array('user_logincount' => $count));
            // insert user_id to table 'online'
            //$last_date = date('Y-m-d h:i:s');
            $this->CI->db->insert('online', array('user_id' => $user_id,'status_user'=>'online'));
            //return TRUE;
            $hasil = array('success' => true);
        } else {
            $hasil = array('success' => false, 'message' => 'Login gagal, username atau password salah');
            $this->CI->session->set_userdata($hasil);
        }

        return $hasil;
    }

    /**
     *
     * This function restricts users from certain pages.
     * use restrict(TRUE) if a user can't access a page when logged in
     *
     * @access	public
     * @param	boolean	wether the page is viewable when logged in
     * @return	void
     */
    function restrict($logged_out = FALSE) {
        $this->checkIP();
        // If the user is logged in and he's trying to access a page
        // he's not allowed to see when logged in,
        // redirect him to the index!
        if ($logged_out && $this->is_logged_in()) {
            //echo $this->CI->fungsi->warning('Maaf, sepertinya Anda sudah login...',site_url());
            die();
        }

        // If the user isn' logged in and he's trying to access a page
        // he's not allowed to see when logged out,
        // redirect him to the login page!
        if (!$logged_out && !$this->logged_in()) {
            // echo $this->CI->fungsi->warning('Anda diharuskan untuk Login bila ingin mengakses halaman Administrasi.',site_url());
            die();
        }
    }

    /**
     *
     * Checks if a user is logged in
     *
     * @access	public
     * @return	boolean
     */
    function is_logged_in() {
        if ($this->CI->session->userdata(SESS_PREFIK . 'username') == FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function logout() {
        $kegiatan = 'Logout ' . $this->CI->config->item('project_name') . ' by ' . $this->CI->session->userdata(SESS_PREFIK . 'nama');
        $this->catat($kegiatan, '', 'logout');
        // delete the 'online' status 
        $user_id = $this->CI->session->userdata(SESS_PREFIK . 'user_id');

        $this->CI->db->delete('online', array('user_id' => $user_id));
        // destroy the session
        $this->CI->session->sess_destroy();
        return TRUE;
    }

    /**
     *
     * Catat Aktivitas User
     *
     * @access	public
     */
    function catat($kegiatan, $awal = '', $aksi = '', $isData = false) {
        //$this->CI->load->database();
        if ($isData) {
            $gab = '';
            foreach ($kegiatan as $key => $val):
                if ($val == '') {
                    $val = 'kosong';
                }
                $keg = '<li><b>' . $key . '</b> dengan value <b>' . $val . '</b></li>';
                $gab = $gab . $keg;
            endforeach;
            $str = $awal . '<br />
				<ul>' . $gab . '</ul>';
        } else {
            $str = $kegiatan;
        }
        $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        $ip_name = gethostbyaddr($ip); //masuk ke server
        $waktu = date('Y-m-d H:i:s');
        $creator = $this->CI->session->userdata(SESS_PREFIK . 'user_id');
        if ($creator == '') {
            $creator = 'Tamu';
        }
        //catat ke log
        $this->CI->db->insert('catatan', array('ip' => $ip,
            'server' => $ip_name,
            'user' => $creator,
            'aksi' => $aksi,
            'kegiatan' => $str,
            'waktu' => $waktu));
        //if(!$act){ echo mysql_error();}
    }

}

// End of library class
// Location: system/application/libraries/Privileges.php
