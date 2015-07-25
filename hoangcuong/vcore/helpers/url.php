<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('base_url'))
{
    function base_url()
    {
        if (isset($_SERVER['HTTP_HOST']))
        {
            $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $base_url .= '://'. $_SERVER['HTTP_HOST'];
            $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        }

        else
        {
            $base_url = 'http://localhost/';
        }
        return $base_url;
    }
}
// ------------------------------------------------------------------------

/**
 * Base URL
 *
 * Returns the "base_url" item from your config file
 *
 * @access    public
 * @return    string
 */
if ( ! function_exists('base_url_site'))
{
    function base_url_site()
    {
        if (isset($_SERVER['HTTP_HOST']))
        {
            $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $base_url .= '://'. $_SERVER['HTTP_HOST'];
            $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        }

        else
        {
            $base_url = 'http://localhost/';
        }
        return str_replace(ADMIN_NAME.'/','',$base_url);
    }
}

if ( ! function_exists('redirect'))
{
    function redirect($uri = '', $method = 'location', $http_response_code = 302)
    {
        $V =& get_instance();
        if($uri == ''){
            $uri = base_url();
        }
        else if ( ! preg_match('#^https?://#i', $uri))
        {
            $uri = html_link(str_replace($V->config->item('suff'),'',$uri));
        }
        switch($method)
        {
            case 'refresh'    : header("Refresh:0;url=".$uri);
                break;
            default            : header("Location: ".$uri, TRUE, $http_response_code);
                break;
        }
        exit;
    }
}

if ( ! function_exists('site_url'))
{
    function site_url($uri = '', $method = 'location', $http_response_code = 302)
    {
        $V =& get_instance();
        $suff = $V->config->item('suff');
        return base_url().$uri.$suff;
    }
}

if ( ! function_exists('html_link'))
{
    function html_link($uri = '')
    {
        $V =& get_instance();
        $suff = $V->config->item('suff');
        return base_url().$uri.$suff;
    }
}

if ( ! function_exists('uri_string'))
{
    function uri_string()
    {
        $pageURL = "http://";
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        $pageURL = str_replace(base_url(),'',$pageURL);
        return $pageURL;
    }
}
/*
if ( ! function_exists('get_params'))
{
    function get_params($name,$params){
        parse_str($params, $output);
        return $output[$name];
    }
}
*/
if ( ! function_exists('get_id'))
{
    function get_id(){
        $sid = strtolower($str_old);
        $id = str_replace($sid,array(''),$_GET['id']);
        return (int)$id;
    }
}

if ( ! function_exists('get_var'))
{
    function get_var($segment, $type = 'string'){
        $V =& get_instance();
        $data = $V->request->get[$segment];
        if($data){
            if($type == 'string'){
                return str_get($data);
            }else{
                return preg_replace("/[^0-9]+/", "", $data);
            }
        }else{
            return ($type == 'string')?'':0;
        }
    }
}

if ( ! function_exists('post_var'))
{
    function post_var($segment, $type = 'string'){
        $V =& get_instance();
        $data = $V->request->post[$segment];
        if($data){
            if($type == 'string'){
                return str_get($data);
            }else{
                return preg_replace("/[^0-9]+/", "", $data);
            }
        }else{
            return ($type == 'string')?'':0;
        }
    }
}

if ( ! function_exists('get_url_var'))
{
    function get_url_var($var, $type = 'string'){
        $V =& get_instance();
        $data = $_GET[$var];
        if($data){
            if($type == 'string'){
                return str_get($data);
            }else{
                return preg_replace("/[^0-9]+/", "", $data);
            }
        }else{
            return ($type == 'string')?'':0;
        }
    }
}
if ( ! function_exists('segment'))
{
    function segment($int = 1,$type = 'string'){
        $V = &get_instance();
        $data = $V->uri->segment($int);
        if($data){
            if($type == 'string'){
                return str_get($data);
            }else{
                return preg_replace("/[^0-9]+/", "", $data);
            }
        }else{
            return ($type == 'string')?'':0;
        }
        
    }
}
if ( ! function_exists('get_end'))
{
    function get_end($string){
        $str = end(explode('-',$string));
        return preg_replace("/[^0-9]+/", "", $str);
    }
}

/*
if ( ! function_exists('segment'))
{
    function segment($int = 1){
        $V = &get_instance();
        $suff = $V->config->item('suff');
        $uri = uri_string();
        $uri = str_replace($suff,'',$uri);
        $uri_str = explode('/',$uri);
        if($uri_str[$int - 1]){
            return $uri_str[$int - 1];
        }else{
            return '';
        }
        
    }
}
*/
if ( ! function_exists('v_order'))
{
    function v_order($url, $name, $field, $order = 'asc', $attr = array())
    {
        //$uri = uri_string();
        //$page = (int)get_var('page');
        //$mods = get_var('mod');
        $act = get_var('act');
        $_order = get_url_var('order');
        $_field = get_url_var('field');

        $url_new = $url;
        
        $attr_str = '';
        foreach($attr as $key => $value):
            $attr_str .="&$key=$value";
        endforeach;
        
        if($_order != ''){
            if(strpos('asc', $_order) === false){
                $order = 'desc';
            }else{
                $order = 'asc';
            }
        }else{
            $order = 'desc';
        }
        if($_field != ''){
            if($_field == $field){
                $order = ($_order == 'asc')?'desc':'asc';
                $url_new .="?field=$field&order=$order".$attr_str;

                return '<a class="'.$order.'" href="'.base_url().$url_new.'">'.$name.'</a>';
            }else{
                $url_new .="?field=$field&order=$order".$attr_str;
                return '<a href="'.base_url().$url_new.'">'.$name.'</a>'; 
            }
        }else{
            $url_new .="?field=$field&order=$order".$attr_str;
            return '<a href="'.base_url().$url_new.'">'.$name.'</a>';
        }


    }
}

if ( ! function_exists('set_page'))
{
    function set_page()
    {
        $pageURL = "http://";
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        $pageURL = str_replace(base_url(),'',$pageURL);
        return '&redirect='.base64_encode($pageURL);
    }
}

if ( ! function_exists('get_page'))
{
    function get_page()
    {
        $redirect = $_GET['redirect'];
        return base64_decode($redirect);
        
    }
}

if ( ! function_exists('set_post_page'))
{
    function set_post_page()
    {
        $pageURL = "http://";
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        $pageURL = str_replace(base_url(),'',$pageURL);
        return base64_encode($pageURL);
    }
}

if ( ! function_exists('get_post_page'))
{
    function get_post_page()
    {
        $redirect = $_POST['page'];
        return base64_decode($redirect);
    }
}