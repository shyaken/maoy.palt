<?php
class home_model extends model{
    function __construct(){
        parent::__construct();
    }
    
    function get_list(){
        return $this->db->result("SELECT productname FROM product LIMIT 2000");
    }
    
    function check_email_exit($email){
        return $this->db->row("SELECT email FROM user WHERE email ='$email'");
    }
}
