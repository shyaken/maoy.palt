<?php
class request {
	public $get = array();
	public $post = array();
	public $cookie = array();
	public $files = array();
	public $server = array();
	
  	public function __construct() {
        $_GET = $this->clean($_GET);
		$_GET = $this->clean_get($_GET);
		$_POST = $this->clean($_POST);
		$_REQUEST = $this->clean($_REQUEST);
		$_COOKIE = $this->clean($_COOKIE);
		$_FILES = $this->clean($_FILES);
		$_SERVER = $this->clean($_SERVER);
		
		$this->get = $_GET;
		$this->post = $_POST;
		$this->request = $_REQUEST;
		$this->cookie = $_COOKIE;
		$this->files = $_FILES;
		$this->server = $_SERVER;
	}
	
  	public function clean($data) {
    	if (is_array($data)) {
	  		foreach ($data as $key => $value) {
				unset($data[$key]);
				
	    		$data[$this->clean($key)] = $this->clean($value);
	  		}
		} else { 
	  		$data = htmlspecialchars($data, ENT_COMPAT);
		}

		return $data;
	}
    
    public function clean_get($data){
        $ar_str_old = array(
            "'",
            '"',
            '.',
            ',',
            'script',
            '>',
            '<',
        );

        
        if (is_array($data)) {
              foreach ($data as $key => $value) {
                unset($data[$key]);
                
                $data[str_replace($ar_str_old,array(''),$key)] = str_replace($ar_str_old,array(''),$value);
              }
        } else { 
              $data = str_replace($ar_str_old,array(''),$data);
        }

        return $data;
    }
}
?>