<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Change Size
    function vnit_order($url,$name) { 
        
        // Phan tich url truyen vao
        $url_page = explode('/',$url);
        $field = $url_page[count($url_page)-2];
        $order = end(explode('/',$url));
        
        // Phan tich Url page
        
        $url_site = explode('/',uri_string());
        $field_url = $url_site[count($url_site)-2];
        $order_url = end(explode('/',uri_string()));    
        if($field == $field_url){
            if($order == $order_url){
            $class_order = $order_url;  
            $order_new = ($order_url == 'asc')?'desc':'asc';
              
              $url =  str_replace($order_url,$order_new,$url);
              $select = ($order_url=='asc')?'asc':'desc';
              return '<a class="'.$select.'" href="'.base_url().$url.'">'.$name.'</a>';
              }else{
              return '<a class="desc" href="'.base_url().$url.'">'.$name.'</a>';    
              }
        }else{
            return '<a href="'.base_url().$url.'">'.$name.'</a>';
        } 
    }
    
    function sort_class($field,$name,$type){
        if($field == $name){
            return 'class="'.$type.'"';
        }else{
            return '';
        }
    }

    function sort_deff($sort){
        if($sort == 'desc'){
            return 'asc';
        }else if($sort == 'asc'){
            return 'desc';
        }else{
            return 'asc';
        }
    }
?>