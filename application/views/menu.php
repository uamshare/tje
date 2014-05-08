<div class="accordion" id="accordion2">
<?php
$CI = & get_instance();
$CI->load->model('menu_m','menu');
echo $CI->menu->getAccordionMenu();
unset($CI);
?>    
</div>