<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function index() {
        //$data['listmenu'] = $this->getMenu();
        if ($this->privileges->is_logged_in()) {
            $data['content'] = 'dashboard/dashboard';
            $this->load->view('template', $data);
        } else {
            $this->load->view('login');
        }
    }
    
    function doLogin(){
//        echo "tes";
        $post = $this->securepost->postMethod();
        $login['username'] = $post['tje_username'];
        $login['password'] = $post['tje_password'];
        $log = $this->privileges->process_login($login);
        
        if($log['success']== true){
            //redirect('dashboard');
            $this->index();
        }else{
            //echo "tes";
            $data['message'] = 'Username atau pasword tidak cocok';
            $this->load->view('login',$data);
            //echo "tes";
        }
    }
    
    function doLogout(){
        $log = $this->privileges->logout();
        if($log){
            redirect('dashboard');
            //$this->index();
        }
    }
    
    function _404(){
        $this->load->view('404');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */