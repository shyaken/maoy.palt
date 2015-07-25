<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'config' . DS . 'constants'.EXT;
require(BASEPATH . DS . 'helpers' . DS. 'timmer'.EXT);
timer_start();
require BASEPATH . DS . 'engine' . DS. 'common'.EXT;
require APPPATH.'config' . DS . 'database'.EXT;
$URI =& load_class('uri', 'library');
$RTR =& load_class('router', 'library');
$RTR->_set_routing();

$CFG =& load_class('config', 'library');
$LANG =& load_class('lang', 'library');

// Do we have any manually set config items in the index.php file?
if (isset($assign_to_config))
{
    $CFG->_assign_to_config($assign_to_config);
}


require BASEPATH . DS . 'engine' . DS. 'vnit'.EXT;

function &get_instance()
{
    return vnit::get_instance();
}
// Dinh nghia thu muc template
define('TEMPLATES', APPPATH . DS . 'templates' . DS); //.php file


$class  = $RTR->fetch_class();
$method = $RTR->fetch_method();
$directory = $RTR->fetch_directory();


require_once APPPATH . DS . 'modules' . DS. $directory . DS . $class . EXT;
$APP = new $class();
if(method_exists($APP, $method)){
    $APP->$method();
}else{
    show_404("{$class}/{$method}");
}
