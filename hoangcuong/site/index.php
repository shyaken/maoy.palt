<?php 
@session_start();
define('ENVIRONMENT', 'development');
if (defined('ENVIRONMENT'))
{
    switch (ENVIRONMENT)
    {
        case 'development':
            error_reporting( error_reporting() & ~E_NOTICE );
            //error_reporting(E_ALL);
        break;
    
        case 'testing':
        case 'production':
            error_reporting(0);
        break;

        default:
            exit('The application environment is not set correctly.');
    }
}
define('CORE','../');
define('SITE','../');
define('ROOT','../');
define('EXT','.php');
define('DS',DIRECTORY_SEPARATOR);
$system_path = ROOT.'vcore';
$system_path = realpath($system_path).'/';
$app_path = 'admin';
$app_path = realpath($app_path).'/';
define('BASEPATH', str_replace("\\", "/", $system_path));
define('APPPATH', ROOT.'admin/');
define('ADMIN_NAME', 'admin');
define('APP_ROOT', str_replace("\\", "/", $app_path));
require ROOT.'vcore/startup.php';

