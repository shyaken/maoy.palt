<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class esset{
    var $_css = array();
    var $_js = array();
    function __construct(){
        $this->APP =& get_instance();
        $this->APP->load->config('config_esset');
        $this->css_path = base_url().$this->APP->config->item('css_path');
        $this->js_path = base_url().$this->APP->config->item('js_path');
    }
    
    function css($_css_ar = array()){
        for($i = 0; $i < sizeof($_css_ar); $i++){
            $this->_css[] = $_css_ar[$i];
        }
    }
    function js($_js_ar = array()){
        for($i = 0; $i < sizeof($_js_ar); $i++){
            $this->_js[] = $_js_ar[$i];
        }
    }
    function display(){
        $js_css = '';
        for($i = 0; $i < sizeof($this->_css); $i++){
            $cs_file = $this->_css[$i];
            if(!$this->isURL($cs_file)){
                $cs_file = $this->css_path.$cs_file;
            }
            $js_css .= '<link type="text/css" rel="stylesheet" href="'.$cs_file.'" media="screen" />'."\r\n";
        }
        for($i = 0; $i < sizeof($this->_js); $i++){
            $js_file = $this->_js[$i];
            if(!$this->isURL($js_file)){
                $js_file = $this->js_path.$js_file;
            }
            $js_css .= '<script type="text/javascript" src="'.$js_file.'" charset="'.$this->APP->config->item('charset').'"></script>'."\r\n";
        }
        
        return $js_css;
    }
    
    public static function isURL($url)
    {
        $pattern = '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@';
        return preg_match($pattern, $url);
    }
}
