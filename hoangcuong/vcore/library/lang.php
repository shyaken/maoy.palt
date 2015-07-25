<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class lang {

    var $language    = array();
    var $is_loaded    = array();

    function __construct()
    {

    }

    // --------------------------------------------------------------------

    function load($langfile = '', $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '')
    {
        
        $APP = get_instance();
        
        
        $langfile = str_replace(EXT, '', $langfile);

        if ($add_suffix == TRUE)
        {
            $langfile = str_replace('_lang.', '', $langfile).'_lang';
        }

        $langfile .= EXT;

        if (in_array($langfile, $this->is_loaded, TRUE))
        {
            return;
        }

        $config =& get_config();

        if ($idiom == '')
        {
            $deft_lang = ( ! isset($config['language'])) ? 'vi' : $config['language'];
            $idiom = ($deft_lang == '') ? 'vi' : $deft_lang;
        }
        $_module = $APP->router->fetch_module_path();
        $path = $_module.'language/'.$idiom.'/'.$langfile;

        // Determine where the language file is and load it
        if ($path != '' && file_exists($path))
        {
            include($path);
            $found = TRUE;
        }
        else
        {
            $found = FALSE;

            foreach (get_instance()->load->get_package_paths(TRUE) as $package_path)
            {
                if (file_exists($package_path.'language/'.$idiom.'/'.$langfile))
                {
                    //echo $package_path.'language/'.$idiom.'/'.$langfile.'<br>';
                    include($package_path.'language/'.$idiom.'/'.$langfile);
                    
                    $found = TRUE;
                    break;
                }
            }

            if ($found !== TRUE)
            {
                die('Unable to load the requested language file: language/'.$idiom.'/'.$langfile);
            }
        }


        if ( ! isset($lang))
        {
            log_message('error', 'Language file contains no data: language/'.$idiom.'/'.$langfile);
            return;
        }

        if ($return == TRUE)
        {
            return $lang;
        }

        $this->is_loaded[] = $langfile;
        $this->language = array_merge($this->language, $lang);
        //var_dump($this->language);
        unset($lang);

        log_message('debug', 'Language file loaded: language/'.$idiom.'/'.$langfile);
        return TRUE;
    }

    function line($line = '')
    {
        $line = ($line == '' OR ! isset($this->language[$line])) ? FALSE : $this->language[$line];

        // Because killer robots like unicorns!
        if ($line === FALSE)
        {
            log_message('error', 'Could not find the language line "'.$line.'"');
        }

        return $line;
    }
}