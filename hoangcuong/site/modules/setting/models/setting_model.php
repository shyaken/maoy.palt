<?php
class setting_model extends model{
    function __construct(){
        parent::__construct();
    }
    
    public function getSetting($key){
        return $this->db->row("SELECT * FROM settings WHERE setting_key = '$key'");
    }
}
