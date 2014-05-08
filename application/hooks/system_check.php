<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class System_check {

    var $CI = null;

    function __construct() {
        $this->CI = & get_instance();
        //$this->CI->load->database();
        $this->CI->load->library(array('privileges'));
    }

    function login_check() {
        $ctl = $this->CI->uri->segment(1);
        if ($ctl != 'dashboard') {
            if (!$this->CI->privileges->is_logged_in()) {
                redirect(base_url('dashboard'));
            } else {
//                if (!$this->page_check()) {
//                    redirect(base_url('dashboard'));
//                }
            }
        }
    }

    function page_check() {
        return $this->CI->privileges->is_allowed_page();
    }

}

?>
