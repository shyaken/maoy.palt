<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class mod{
    var $_mod_index = 'index';
    var $_mod_path = '';
    function __construct(){
        $this->v = get_instance(); 
    }

    function show($position){
        $data['position'] = $position;
        $this->root_mod($data);
    }
    
    function display($mod_name, $data = array()){
        $_ci_file = $mod_name . DS . 'index' .EXT;
        $_ci_path = APPPATH. 'mod'.DS.$mod_name.DS.'index'.EXT;
         if ( ! file_exists($_ci_path))
        {
            die('Unable to load the requested file mod: '.$_ci_file);
        }
        extract($data);
        ob_start();
        include($_ci_path);
        
    }
    
    
    
    function root_mod($vars = array()){
        $_ci_path = APPPATH. DS . 'mod' . DS .'index'.EXT;
        if ( ! file_exists($_ci_path))
        {
            die('Unable to load the requested file mod: '.$_ci_path);
        }
        extract($vars);
        ob_start();
        include($_ci_path);
    }
    
    function get_position($position){
        return $this->v->db->result("SELECT * FROM modules WHERE position = '$position' AND published = 1 ORDER BY ordering DESC");
    }
    
    function get_params($name,$params){
        parse_str($params, $output);
        return $output[$name];
    }
}
