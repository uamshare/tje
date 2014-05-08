<?php

class Menu_m extends CI_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    function getListMenu($userid, $parent) {
        $strQuery = "SELECT lm.menu_id
,lm.menu as menu
,(CASE WHEN ma.user_id is null THEN false ELSE true END) as checked 
FROM listmenu lm LEFT JOIN (
SELECT * FROM menu_akses ma WHERE ma.user_id='" . $userid . "'
)ma
ON ma.menu_id = lm.menu_id WHERE parent='" . $parent . "' AND lm.active='1'";
        $menu = $this->db->query($strQuery);
//        $this->db->select('ma.menu_id, lm.menu, lm.parent, lm.keymenu, lm.controller, lm.image_location');
//        $this->db->join('menu_akses ma', 'ma.menu_id = lm.menu_id', 'left');
//        $this->db->where(array('lm.parent' => $parent));
//        $this->db->order_by('lm.urut', 'ASC');
//        $data = $this->db->get('listmenu lm')->result();
        //echo $this->db->last_query().'===';
        return $menu->result();
    }
    
    function getAccordionListMenu($userid, $parent){
        $this->db->select('ma.menu_id, lm.menu, lm.parent, lm.keymenu, lm.controller, lm.image_location, lm.image_hover, lm.ajax, lm.icon_cls');
        $this->db->join('menu_akses ma', 'ma.menu_id = lm.menu_id');
        $this->db->where(array('ma.user_id' => $userid));
        $this->db->where(array('lm.parent' => $parent));
        $this->db->order_by('lm.urut', 'ASC');
        $data = $this->db->get('listmenu lm')->result();
        return $data;
    }
    
    function getAccordionMenu($parent = 0, $user_id = '') {
        $user = $this->session->userdata(SESS_PREFIK . 'user_id');
        $output = '';
        $query = $this->getAccordionListMenu($user, $parent);
        //var_dump($query);
        if (count($query) > 0) {
            $i = 0;
            foreach ($query as $menu) {
                $i++;
                $output .= '<div class="accordion-group">';
                //$in = $this->sidebar['accordian'] == $menu->keymenu ? 'in' : '';
                $in = '';
                $output .= '<div class="accordion-title">';
                $output .= '<a class="accordion-toggle" style="font-size: small;font-weight: bold;" data-toggle="collapse" data-parent="#accordion2" href="#collapse' . $i . '">';
                $output .= '<img src="' . $menu->image_location . '" width="25px" height="25px" style="margin-right: 10px;">' . $menu->menu . '';
                $output .= '</a>';
                $output .= '</div>';
                $output .= '<div id="collapse' . $i . '" class="accordion-body collapse ' . $in . '">';
                $output .= '<div class="accordion-content">';
                $output .= '<ul style="list-style: circle;">';
                $query2 = $this->getAccordionListMenu($user, $menu->menu_id);
                foreach ($query2 as $sub) {
                    $output .= '<li><a href="' . $sub->controller . '"><i class="' . $sub->image_location . '"></i>&nbsp;';
                    $output .= $sub->menu;
                    $output .= '</a></li>';
                }
                $output .= '</ul>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= "</div>\n";
                //$output .= $this->getAccordionMenu($menu->menu_id, $user_id);
            }
        }

        return $output;
    }
    
    function getMenuUtamaById() {
        $this->db->select('ma.menu_id, lm.menu, lm.parent, lm.keymenu, lm.controller, lm.image_location, lm.image_hover, lm.icon_cls');
        $this->db->join('listmenu lm', 'lm.menu_id = ma.menu_id');
        $this->db->where(array('lm.parent' => '0', 'ma.user_id' => $this->session->userdata(SESS_PREFIK . 'user_id')));
        $data = $this->db->get('menu_akses ma')->result();
        return $data;
    }
    
    function getMenuUtama($keymenu){
        $this->db->select('ma.menu_id, lm.menu, lm.parent, lm.keymenu, lm.controller, lm.image_location, lm.image_hover, lm.icon_cls');
        $this->db->join('listmenu lm', 'lm.menu_id = ma.menu_id');
        $this->db->where(array('lm.keymenu'=>$keymenu));
        $data = $this->db->get('menu_akses ma')->result();
        return $data;
    }

}

?>
