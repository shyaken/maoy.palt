<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function &load_class($class, $directory = 'libraries', $prefix = '')
{
    static $_classes = array();

    // Does the class exist?  If so, we're done...
    if (isset($_classes[$class]))
    {
        return $_classes[$class];
    }

    $name = FALSE;

    // Look for the class first in the native system/libraries folder
    // thenin the local application/libraries folder
    foreach (array(BASEPATH, APPPATH) as $path)
    {
        if (file_exists($path.$directory.'/'.$class.EXT))
        {
            $name = $prefix.$class;

            if (class_exists($name) === FALSE)
            {
                require($path.$directory.'/'.$class.EXT);
            }

            break;
        }
    }

    // Is the request a class extension?  If so we load it too
    if (file_exists(APPPATH.$directory.'/'.$class.EXT))
    {
        $name = $class;

        if (class_exists($name) === FALSE)
        {
            require(APPPATH.$directory.'/'.$class.EXT);
        }
    }

    // Did we find the class?
    if ($name === FALSE)
    {
        // Note: We use exit() rather then show_error() in order to avoid a
        // self-referencing loop with the Excptions class
        exit('Unable to locate the specified class: '.$class.EXT);
    }

    // Keep track of what we just loaded
    is_loaded($class);

    $_classes[$class] = new $name();
    return $_classes[$class];
}

// --------------------------------------------------------------------

function is_loaded($class = '')
{
    static $_is_loaded = array();

    if ($class != '')
    {
        $_is_loaded[strtolower($class)] = $class;
    }

    return $_is_loaded;
}

function &get_config($replace = array())
{
    static $_config;

    if (isset($_config))
    {
        return $_config[0];
    }

    // Is the config file in the environment folder?
    if ( ! defined('ENVIRONMENT') OR ! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/config'.EXT))
    {
        $file_path = APPPATH.'config/config'.EXT;
    }

    // Fetch the config file
    if ( ! file_exists($file_path))
    {
        exit('The configuration file does not exist.');
    }

    require($file_path);

    // Does the $config array exist in the file?
    if ( ! isset($config) OR ! is_array($config))
    {
        exit('Your config file does not appear to be formatted correctly.');
    }

    // Are any values being dynamically replaced?
    if (count($replace) > 0)
    {
        foreach ($replace as $key => $val)
        {
            if (isset($config[$key]))
            {
                $config[$key] = $val;
            }
        }
    }

    return $_config[0] =& $config;
}

// ------------------------------------------------------------------------

function config_item($item)
{
    static $_config_item = array();

    if ( ! isset($_config_item[$item]))
    {
        $config =& get_config();

        if ( ! isset($config[$item]))
        {
            return FALSE;
        }
        $_config_item[$item] = $config[$item];
    }

    return $_config_item[$item];
}    

// ------------------------------------------------------------------------

function is_really_writable($file)
{
    // If we're on a Unix server with safe_mode off we call is_writable
    if (DIRECTORY_SEPARATOR == '/' AND @ini_get("safe_mode") == FALSE)
    {
        return is_writable($file);
    }

    // For windows servers and safe_mode "on" installations we'll actually
    // write a file then read it.  Bah...
    if (is_dir($file))
    {
        $file = rtrim($file, '/').'/'.md5(mt_rand(1,100).mt_rand(1,100));

        if (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
        {
            return FALSE;
        }

        fclose($fp);
        @chmod($file, DIR_WRITE_MODE);
        @unlink($file);
        return TRUE;
    }
    elseif ( ! is_file($file) OR ($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
    {
        return FALSE;
    }

    fclose($fp);
    return TRUE;
}  

// ------------------------------------------------------------------------

function log_message($level = 'error', $message, $php_error = FALSE)
{
    static $_log;

    if (config_item('log_threshold') == 0)
    {
        return;
    }

    $_log =& load_class('Log');
    $_log->write_log($level, $message, $php_error);
}    
    
// ------------------------------------------------------------------------

function remove_invisible_characters($str, $url_encoded = TRUE)
{
    $non_displayables = array();
    
    // every control character except newline (dec 10)
    // carriage return (dec 13), and horizontal tab (dec 09)
    
    if ($url_encoded)
    {
        $non_displayables[] = '/%0[0-8bcef]/';    // url encoded 00-08, 11, 12, 14, 15
        $non_displayables[] = '/%1[0-9a-f]/';    // url encoded 16-31
    }
    
    $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';    // 00-08, 11, 12, 14-31, 127

    do
    {
        $str = preg_replace($non_displayables, '', $str, -1, $count);
    }
    while ($count);

    return $str;
}
   
// ------------------------------------------------------------------------
    
function show_404($page = '', $log_error = TRUE)
{
    
    
    $heading = "404 Page Not Found";
    $message = "The page you requested was not found. ".$page;

    // By default we log this, but allow a dev to skip it
    if ($log_error)
    {
        log_message('error', '404 Page Not Found --> '.$page);
    }

    echo show_error($heading, $message, 'error_404', 404);
    exit;
}

// ------------------------------------------------------------------------

function show_error($heading, $message, $template = 'error_general', $status_code = 500)
{
    set_status_header($status_code);

    $message = '<p>'.implode('</p><p>', ( ! is_array($message)) ? array($message) : $message).'</p>';

   
        ob_end_flush();
    
    ob_start();
    include(APPPATH.'errors/'.$template.EXT);
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}

// ------------------------------------------------------------------------
    
function set_status_header($code = 200, $text = '')
{
    $stati = array(
                        200    => 'OK',
                        201    => 'Created',
                        202    => 'Accepted',
                        203    => 'Non-Authoritative Information',
                        204    => 'No Content',
                        205    => 'Reset Content',
                        206    => 'Partial Content',

                        300    => 'Multiple Choices',
                        301    => 'Moved Permanently',
                        302    => 'Found',
                        304    => 'Not Modified',
                        305    => 'Use Proxy',
                        307    => 'Temporary Redirect',

                        400    => 'Bad Request',
                        401    => 'Unauthorized',
                        403    => 'Forbidden',
                        404    => 'Not Found',
                        405    => 'Method Not Allowed',
                        406    => 'Not Acceptable',
                        407    => 'Proxy Authentication Required',
                        408    => 'Request Timeout',
                        409    => 'Conflict',
                        410    => 'Gone',
                        411    => 'Length Required',
                        412    => 'Precondition Failed',
                        413    => 'Request Entity Too Large',
                        414    => 'Request-URI Too Long',
                        415    => 'Unsupported Media Type',
                        416    => 'Requested Range Not Satisfiable',
                        417    => 'Expectation Failed',

                        500    => 'Internal Server Error',
                        501    => 'Not Implemented',
                        502    => 'Bad Gateway',
                        503    => 'Service Unavailable',
                        504    => 'Gateway Timeout',
                        505    => 'HTTP Version Not Supported'
                    );

    if ($code == '' OR ! is_numeric($code))
    {
        show_error('Status codes must be numeric', 500);
    }

    if (isset($stati[$code]) AND $text == '')
    {
        $text = $stati[$code];
    }

    if ($text == '')
    {
        show_error('No status text available.  Please check your status code number or supply your own message text.', 500);
    }

    $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

    if (substr(php_sapi_name(), 0, 3) == 'cgi')
    {
        header("Status: {$code} {$text}", TRUE);
    }
    elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0')
    {
        header($server_protocol." {$code} {$text}", TRUE, $code);
    }
    else
    {
        header("HTTP/1.1 {$code} {$text}", TRUE, $code);
    }
}