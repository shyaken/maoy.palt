<?php
class session{
    public $ar_key = array();
    public $data = array();
            
    public function __construct() {        
        $this->APP =& get_instance();
        $this->APP->config->item('store_session_table');
        if (!session_id()) {
            ini_set('session.use_cookies', 'On');
            ini_set('session.use_trans_sid', 'Off');
            
            session_set_cookie_params(0, '/');
            session_start();
        }
    
        $this->data =& $_SESSION;
        
        
        
    }
    function sessionid(){
        return session_id();
    }

    
    function set_flashdata($key, $value){
        $_SESSION[$key] = $value;
        
    }
    
    function get_flashdata($key){
        return $_SESSION[$key];
    }
    
    function unset_flashdata($array = array()){
        foreach($array as $key):
            $_SESSION[$key] = '';
        endforeach;
        
    }
}