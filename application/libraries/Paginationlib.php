<?php
class Paginationlib {
function __construct() {
$this->ci =& get_instance();
}

public function initPagination($base_url,$total_rows){
$config['per_page']          = 12;
$config['uri_segment']       = 3;
$config['base_url']          = base_url().$base_url;
$config['total_rows']        = $total_rows;
$config['use_page_numbers']  = TRUE;

$config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
$config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
         
$config['cur_tag_open'] = "<li><span><b>";
$config['cur_tag_close'] = "</b></span></li>";

$this->ci->pagination->initialize($config);
return $config;    
}    
}