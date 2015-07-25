<?php
class esset{
    var $_css = array();
    var $_js = array();
    function __construct(){
        $this->APP =& get_instance();
    }
    
    function css($_css = array()){
        
    }
    
    public static function isURL($url)
    {
        $pattern = '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@';
        return preg_match($pattern, $url);
    }
}
