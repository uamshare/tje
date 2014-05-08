<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends CI_Controller {

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->model('user_m', 'user');
        $this->load->model('menu_m', 'menu');
        $this->load->library('encrypt');
    }

    function index() {
//        $cek =$this->privileges->cekAksesMenu('data_user');
//        if($cek == 1){
        $this->getList();
//        }else{
//            redirect('home');
//        }
    }

    public function getList() {
        $data['content'] = 'users/list';
        //$data['listtabel'] = $this->user->getList();
        $this->load->view('template', $data);
    }

    function get_data() {
        $configs = array(
            'id' => 'user_id',
            'aColumns' => array('user_name', 'user_username', 'e_mail', 'user_aktif'),
            'datamodel' => 'user_m',
            'actiontable' => array(
                'edit' => array(
                    'href' => 'users/edit',
                    'label' => '<i class="icon-pencil"></i>',//"<img src='" . base_url() . "/assets/ico/ubah.png' >",
                    'title' => 'Ubah Data'
                ),
                'delete' => array(
                    'href' => 'users/delete',
                    'label' => '<i class="icon-remove"></i>',//"<img src='" . base_url() . "/assets/ico/hapus.png'>",
                    'title' => 'Hapus Data'
                )
            )
        );
        //echo "<pre>"; 
        echo $this->crud_m->get_data($configs);
        //echo "</pre>";
    }

    function add() {
        $data['content'] = 'users/add';
        $this->load->view('template', $data);
    }

//    
    function edit($id = '') {
        $data['content'] = 'users/edit';
        $data['rowedit'] = $this->user->getListByid($id);
        $this->load->view('template', $data);
    }

    function password() {
        $data['content'] = 'users/password';
        $this->load->view('template', $data);
    }

    function menu($id = '', $nama = '') {
        //$cek = $this->privileges->cekAksesMenu('data_user');
        //if ($cek == 1) {
            $data['content'] = 'users/menu';
            $data['listtabel'] = $this->user->getList();
            $data['aksesmenu'] = $this->getAksesMenu($id);
            $data['user_id'] = $id;
            $data['nama'] = $nama;
            $this->load->view('template', $data);
        //}else{
          //  redirect(base_url());
        //}
    }

    function getAksesMenu($user_id = '', $parent = 0) {
        // $user = $this->session->userdata(SESS_PREFIK . 'user_id');

        $user = $user_id;
        $output = '';
        $query = $this->menu->getListMenu($user, $parent);
        if (count($query) > 0) {
            $output .= ($parent == 0) ? '<ul id="chektree">' : '<ul>';
            foreach ($query as $menu) {
                $output .='<li>';
                if ($menu->checked == 1) {
                    $output .="<input type='checkbox' name='menu[$menu->menu_id]' checked /> $menu->menu";
                } else {
                    $output .="<input type='checkbox' name='menu[$menu->menu_id]' /> $menu->menu";
                }
                $output .=$this->getAksesMenu($user_id, $menu->menu_id);

                $output .="</li>\n";
            }
            $output .= "</ul>\n";
        } else {
            // echo 'Data is Empty';
        }

        return $output;
    }

//    
    public function create() {
        $post = $this->securepost->postMethod();
        $post['user_password'] = $this->encrypt->sha1($post['user_password']);
        $insertData = $this->crud_m->insert('users', $post);
        if ($insertData) {
            echo $this->fungsi->warning('Data telah tersimpan', base_url('users'));
        } else {
            $error = $this->crud_m->result($insertData);
            echo $this->fungsi->warning($error['error'], base_url('users'));
        }
    }

//    
    function update() {
        $post = $this->securepost->postMethod();
        $updateData = $this->crud_m->update('users', $post, array('user_id' => $post['user_id']));
        if ($updateData) {
            echo $this->fungsi->warning('Data telah tersimpan', base_url('users'));
        } else {
            $error = $this->crud_m->result($updateData);
            echo $this->fungsi->warning($error['error'], base_url('users'));
        }
    }

//    
    function delete($id = '') {
//        $updateData = $this->crud_m->delete('users', array('user_id'=>$id));
        $updateData = $this->crud_m->update('users', array('user_aktif' => 0), array('user_id' => $id));
        if ($updateData) {
            $tes = "Data telah terhapus";
//            echo "<script>
//                            alert('Data telah terhapus');
//                            window.location.href='users';
//                    </script>";
            redirect('users');
        } else {
            $error = $this->crud_m->result($updateData);
            echo $this->fungsi->warning($error['error'], base_url('users'));
        }
    }

    function updatepass() {
        $this->load->library('encrypt');
        $post = $this->securepost->postMethod();
        $user_id = $this->session->userdata(SESS_PREFIK . 'user_id');

        $old = $this->db->query("SELECT user_id,user_password FROM users WHERE user_id = '$user_id'")->row();
        if ($this->encrypt->sha1($post['password']) != $old->user_password) {
            $message = 'Password lama tidak sesuai';
            echo $this->fungsi->warning($message, base_url('users/password'));
        } else {
            if ($post['newpassword'] != $post['confirmpassword']) {
                $message = 'Password baru yang anda input harus sama';
                echo $this->fungsi->warning($message, base_url('users/password'));
            } else {
                $updateData = $this->crud_m->update(
                        'users', array(
                    'user_password' => $this->encrypt->sha1($post['newpassword'])
                        ), array(
                    'user_id' => $user_id,
                    'user_password' => $this->encrypt->sha1($post['password'])
                        )
                );
                if ($updateData) {
                    //redirect('users/password');
                    $message = 'Update password berhasil';
                    echo $this->fungsi->warning($message, base_url('dashboard'));
                } else {
                    $error = $this->crud_m->result($updateData);
                    echo $this->fungsi->warning($error['message'], base_url('users/password'));
                }
            }
        }
    }

    function updatemenu() {
        $user = $this->input->post('user_id');
        $postMenu = $this->input->post('menu');
        $this->db->delete('menu_akses', array('user_id' => $user));
        $arrTmp = array();
        foreach ($postMenu as $key => $val) {
            array_push($arrTmp, array('menu_id' => $key, 'user_id' => $user));
            //$insertMenuAkses = $this->db->insert('menu_akses',array('menu_id'=>$key,'user_id'=>$user));
        }
        $insertMenuAkses = $this->db->insert_batch('menu_akses', $arrTmp);
        //var_dump($arrTmp);
        if ($insertMenuAkses) {
            //redirect(base_url('users/menu'));
            echo $this->fungsi->warning('Udpdate Menu Akses Sukses', base_url('users/menu'));
        } else {
            echo $this->fungsi->warning('Udpdate Menu Akses gagal', base_url('users/menu'));
        }
    }

//    
}

?>
