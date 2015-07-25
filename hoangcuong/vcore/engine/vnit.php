<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class vnit{
    private static $instance;
    public function __construct()
    {
        self::$instance =& $this;
        foreach (is_loaded() as $var => $class)
        {
            $this->$var =& load_class($class);
        }

        $this->load =& load_class('loader', 'engine');
        $this->security =& load_class('security', 'engine');
        $this->request =& load_class('request', 'library');
        $this->db =& load_class('db', 'engine');
        $this->db->connect();
        $this->db->set_utf8();
        $this->load->helper('url');
        $this->load->_base_classes = is_loaded();
        $this->security->instance();
        $this->load->_ci_autoloader();
        $this->menu =array();
        $this->link =array();

    }
    public static function &get_instance()
    {
        return self::$instance;
    }
}
