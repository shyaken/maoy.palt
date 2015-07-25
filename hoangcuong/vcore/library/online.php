<?php
class online{
    function __construct(){
        $this->v = get_instance();
        $this->session_id = $this->v->session->sessionid();
        $this->is_online();
    }
    
    function getIp() {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $ip;
    }
    
    function is_online(){
        $time_expre = time() - 300; 
        $this->v->db->query("DELETE FROM counter WHERE timestamp < $time_expre");
        //$this->v->db->delete('counter',array('timestamp <'=>$time_expre));
        $session_id = $this->session_id;
        $row = $this->v->db->row("SELECT session_id FROM counter WHERE session_id = '$session_id'");
        if($row){
            $vdata['timestamp'] = time();
            $this->v->db->update('counter',$vdata,array('session_id'=>$this->session_id));
        }else{
            $vdata['session_id'] = $this->session_id;
            $vdata['ip_address'] = $this->getIp();
            //$vdata['accountid'] = 1;
            $vdata['timestamp'] = time();
            $this->v->db->insert('counter',$vdata);
            
             $row1 = $this->v->db->row("SELECT * FROM `counter_history` WHERE id = 1");
             $month = date('m',time());
             $today = date('d',time());
             if($row1->c_month_name != $month){
                 $vday['c_month_name'] = $month;
                 $vday['c_month_hits'] = 0;
                 $this->v->db->update('counter_history',$vday,array('id'=>1));                 
             }
             
             if($row1->c_day_name != $today){
                 $vday['c_day_name'] = $today;
                 $vday['c_today_hits'] = 0;
                 $this->v->db->update('counter_history',$vday,array('id'=>1));
             }
             $row1 = $this->v->db->row("SELECT * FROM `counter_history` WHERE id = 1");
             $vdatas['c_total'] = $row1->c_total + 1;
             $vdatas['c_today_hits'] = $row1->c_today_hits + 1;
             $vdatas['c_month_hits'] = $row1->c_month_hits + 1;
             $this->v->db->update('counter_history',$vdatas,array('id'=>1));
        } 
    }
    
    function hitsonline(){
        return $this->v->db->row("SELECT c_total, c_today_hits, c_month_hits FROM counter_history");
    }
    
    function get_is_online(){
        return $this->v->db->num_rows("SELECT session_id FROM counter");
    }    
    
    function getDigits( $number, $length=0 ){
        $strlen = strlen($number);
        
        $arr    =    array();
        $diff    =    $length -  $strlen;
        
        // Push Leading Zeros
        while ( $diff>0 ){
            array_push( $arr,0 );
            $diff--;
        }
        
        // For PHP 4.x
        $arrNumber    =    array();
        for ($i = 0; $i < $strlen; $i++) {
            $arrNumber[] = substr($number,$i,1);
        }
        
        // For PHP 5.x: $arrNumber    =    str_split( $number );
        
        $arr        =    array_merge( $arr,$arrNumber );
        
        return $arr;
    }
    
    function showDigitImage( $digit_type="default", $digit )
    {    
        $path = base_url().'site/mod/mod_online/number'; 
        $ret    =    '<img src="'.$path.'/'.$digit_type.'/'.$digit.'.png"';
        $ret    .=    ' />';
        
        return $ret;
    }

}
