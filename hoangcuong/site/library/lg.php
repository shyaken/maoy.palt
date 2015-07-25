<?php
class lg{
    function __construct(){
         $this->V = get_instance();
         if(!isset($this->V->session->data["lg_admin"])){
             $this->V->session->data["lg_admin"] = $this->V->config->item("language");
         }
         $this->V->load->language('admin',$this->V->session->data["lg_admin"]);
    }
    
    function active(){
        return $this->V->session->data['lg_admin'];
    }
    
    function lang_url(){
       if($this->V->config->item('muti_language')){
           return $this->active().'/';
       }else{
           return '';
       } 
    }
}