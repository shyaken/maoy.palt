<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class loader {

    // All these are set automatically. Don't mess with them.
    var $_ci_ob_level;
    var $_ci_view_path        = '';
    var $_ci_modules_path        = '';
    var $_ci_library_paths    = array();
    var $_ci_model_paths    = array();
    var $_ci_helper_paths    = array();
    //var $_ci_mod_paths    = array();
    var $_base_classes        = array(); // Set by the controller class
    var $_ci_cached_vars    = array();
    var $_ci_classes        = array();
    var $_ci_loaded_files    = array();
    var $_ci_models            = array();
    var $_ci_helpers        = array();
    //var $_ci_mods        = array();
    var $_ci_varmap            = array('unit_test' => 'unit', 'user_agent' => 'agent');

    function __construct()
    {
        
        $APP =& get_instance();
        $this->_ci_modules_path = $APP->router->fetch_module();
        $this->_ci_view_path = APPPATH.DS.'modules'.DS;
        $this->_ci_ob_level  = ob_get_level();
        $this->_ci_library_paths = array(APPPATH, BASEPATH);
        $this->_ci_helper_paths = array(APPPATH, BASEPATH);
        $this->_ci_model_paths = array(APPPATH);
        $this->_ci_model_paths = array(APPPATH);

        //log_message('debug', "Loader Class Initialized");
    }

    // --------------------------------------------------------------------

    function library($library = '', $params = NULL, $object_name = NULL)
    {
        if (is_array($library))
        {
            foreach ($library as $class)
            {
                $this->library($class, $params);
            }

            return;
        }

        if ($library == '' OR isset($this->_base_classes[$library]))
        {
            return FALSE;
        }

        if ( ! is_null($params) && ! is_array($params))
        {
            $params = NULL;
        }

        $this->_ci_load_class($library, $params, $object_name);
    }

    // --------------------------------------------------------------------

    /**
     * Model Loader
     *
     * This function lets users load and instantiate models.
     *
     * @access    public
     * @param    string    the name of the class
     * @param    string    name for the model
     * @param    bool    database connection
     * @return    void
     */
    function model($model, $name = '', $db_conn = FALSE)
    {
        if (is_array($model))
        {
            foreach ($model as $babe)
            {
                $this->model($babe);
            }
            return;
        }

        if ($model == '')
        {
            return;
        }

        $path = '';

        // Is the model in a sub-folder? If so, parse out the filename and path.
        if (($last_slash = strrpos($model, '/')) !== FALSE)
        {
            // The path is in front of the last slash
            $path = substr($model, 0, $last_slash + 1);

            // And the model name behind it
            $model = substr($model, $last_slash + 1);
        }

        if ($name == '')
        {
            $name = $model;
        }

        if (in_array($name, $this->_ci_models, TRUE))
        {
            return;
        }

        $CI =& get_instance();
        if (isset($CI->$name))
        {
            show_error('The model name you are loading is the name of a resource that is already being used: '.$name);
        }

        $model = strtolower($model);

        foreach ($this->_ci_model_paths as $mod_path)
        {

            if ( ! file_exists($mod_path . DS . 'modules' . DS . $this->_ci_modules_path . DS .'models/'.$path.$model.EXT))
            {
                continue;
            }
            if ( ! class_exists('model'))
            {
                load_class('model', 'engine');
            }
            $modules = str_replace('_model','',$model);
            
            require_once($mod_path . DS . 'modules' . DS . $this->_ci_modules_path . DS .'models/'.$path.$model.EXT);

            $model = ucfirst($model);

            $CI->$name = new $model();

            $this->_ci_models[] = $name;
            return;
        }
        //echo $mod_path . DS . 'modules' . DS . $this->_ci_modules_path . DS .'models/'.$path.$model.EXT;
        // couldn't find the model
        die('Khong the tim thay duong dan file Models:'.$model);
    }


    // --------------------------------------------------------------------

    function view($view, $vars = array(), $return = FALSE)
    {
        $path  = APPPATH.'modules/'.$this->_ci_modules_path.'/views/';
        $this->_ci_view_path = $path;
        return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }
    
    // Lader Modules
    
    public function mod($position)
    {            
        $vars = array('position'=>$position);
        $_ci_file = 'index';
        $_ci_path = APPPATH. DS . 'mod' . DS .$_ci_file.EXT;
        extract($vars);
        ob_start();

        include($_ci_path);
    } 
    function view_mod($mod_name, $data = array()){
        $_ci_file = $mod_name . DS . 'index' .EXT;
        $_ci_path = APPPATH. 'mod'.DS.$mod_name.DS.'index'.EXT;
         if ( ! file_exists($_ci_path))
        {
            die('ERROR: Counld not load mod view: '.$_ci_file);
        }
        extract($data);
        ob_start();
        include($_ci_path);
        
    }
    /********************
    * Templates
    */

    
    public function _templates($view, $vars=array(), $string = false)
    {            
        return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars),'_ci_path'=> TEMPLATES.$view.EXT, '_ci_return' => ""));
    }

    public function templates($page, $data=array(), $layout = null){
        $data['page'] = $page;
        if($layout != null ){
            $this->_templates('skin_'.$layout, $data);
        }else{
            $this->_templates('skin', $data);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Load File
     *
     * This is a generic file loader
     *
     * @access    public
     * @param    string
     * @param    bool
     * @return    string
     */
    function file($path, $return = FALSE)
    {
        return $this->_ci_load(array('_ci_path' => $path, '_ci_return' => $return));
    }

    // --------------------------------------------------------------------

    /**
     * Set Variables
     *
     * Once variables are set they become available within
     * the controller class and its "view" files.
     *
     * @access    public
     * @param    array
     * @return    void
     */
    function vars($vars = array(), $val = '')
    {
        if ($val != '' AND is_string($vars))
        {
            $vars = array($vars => $val);
        }

        $vars = $this->_ci_object_to_array($vars);

        if (is_array($vars) AND count($vars) > 0)
        {
            foreach ($vars as $key => $val)
            {
                $this->_ci_cached_vars[$key] = $val;
            }
        }
    }
    
    
    // --------------------------------------------------------------------
    function helper($helpers = array())
    {
        foreach ($this->_ci_prep_filename($helpers, '') as $helper)
        {
            if (isset($this->_ci_helpers[$helper]))
            {
                continue;
            }

            $ext_helper = APPPATH.'helpers/'.$helper.EXT;

            if (file_exists($ext_helper))
            {
                $base_helper = BASEPATH.'helpers/'.$helper.EXT;

                if (file_exists($base_helper))
                {
                    include_once($base_helper);
                    //die('ERROR: Counld not load Helper : helpers/'.$helper.EXT.' Local: '.$base_helper);
                }

                include_once($ext_helper);
                
                $this->_ci_helpers[$helper] = TRUE;
                continue;
            }


            foreach ($this->_ci_helper_paths as $path)
            {
                if (file_exists($path.'helpers/'.$helper.EXT))
                {
                    include_once($path.'helpers/'.$helper.EXT);

                    $this->_ci_helpers[$helper] = TRUE;
                    break;
                }
            }

            // unable to load the helper
            if ( ! isset($this->_ci_helpers[$helper]))
            {
                die('ERROR: Counld not load Helper: helpers/'.$helper.EXT);
            }
        }
    }

    // --------------------------------------------------------------------

    function helpers($helpers = array())
    {
        $this->helper($helpers);
    }

    // --------------------------------------------------------------------

    function language($file = array(), $lang = '')
    {
        $CI =& get_instance();

        if ( ! is_array($file))
        {
            $file = array($file);
        }

        foreach ($file as $langfile)
        {
            $CI->lang->load($langfile, $lang);
        }
    }

    // --------------------------------------------------------------------

    function config($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        $APP =& get_instance();
        $APP->config->load($file, $use_sections, $fail_gracefully);
    }

    // --------------------------------------------------------------------

    function add_package_path($path)
    {
        $path = rtrim($path, '/').'/';

        array_unshift($this->_ci_library_paths, $path);
        array_unshift($this->_ci_model_paths, $path);
        array_unshift($this->_ci_helper_paths, $path);

        // Add config file path
        $config =& $this->_ci_get_component('config');
        array_unshift($config->_config_paths, $path);
    }

    // --------------------------------------------------------------------

    function get_package_paths($include_base = FALSE)
    {
        return $include_base === TRUE ? $this->_ci_library_paths : $this->_ci_model_paths;
    }

    // --------------------------------------------------------------------

    function remove_package_path($path = '', $remove_config_path = TRUE)
    {
        $config =& $this->_ci_get_component('config');

        if ($path == '')
        {
            $void = array_shift($this->_ci_library_paths);
            $void = array_shift($this->_ci_model_paths);
            $void = array_shift($this->_ci_helper_paths);
            $void = array_shift($config->_config_paths);
        }
        else
        {
            $path = rtrim($path, '/').'/';

            foreach (array('_ci_library_paths', '_ci_model_paths', '_ci_helper_paths') as $var)
            {
                if (($key = array_search($path, $this->{$var})) !== FALSE)
                {
                    unset($this->{$var}[$key]);
                }
            }

            if (($key = array_search($path, $config->_config_paths)) !== FALSE)
            {
                unset($config->_config_paths[$key]);
            }
        }

        // make sure the application default paths are still in the array
        $this->_ci_library_paths = array_unique(array_merge($this->_ci_library_paths, array(APPPATH, BASEPATH)));
        $this->_ci_helper_paths = array_unique(array_merge($this->_ci_helper_paths, array(APPPATH, BASEPATH)));
        $this->_ci_model_paths = array_unique(array_merge($this->_ci_model_paths, array(APPPATH)));
        $config->_config_paths = array_unique(array_merge($config->_config_paths, array(APPPATH)));
    }

    // --------------------------------------------------------------------

    function _ci_load($_ci_data)
    {
        
        // Set the default data variables
        foreach (array('_ci_view', '_ci_vars', '_ci_path', '_ci_return') as $_ci_val)
        {
            $$_ci_val = ( ! isset($_ci_data[$_ci_val])) ? FALSE : $_ci_data[$_ci_val];
        }

        // Set the path to the requested file
        if ($_ci_path == '')
        {
            $_ci_ext = pathinfo($_ci_view, PATHINFO_EXTENSION);
            $_ci_file = ($_ci_ext == '') ? $_ci_view.EXT : $_ci_view;
            $_ci_path = $this->_ci_view_path . $_ci_file;
        }
        else
        {
            $_ci_x = explode('/', $_ci_path);
            $_ci_file = end($_ci_x);
        }
        
        if ( ! file_exists($_ci_path))
        {
            die('Error: Could not load file: '.$_ci_file);
        }


        $_ci_CI =& get_instance();
        foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var)
        {
            if ( ! isset($this->$_ci_key))
            {
                $this->$_ci_key =& $_ci_CI->$_ci_key;
            }
        }


        if (is_array($_ci_vars))
        {
            $this->_ci_cached_vars = array_merge($this->_ci_cached_vars, $_ci_vars);
        }
        extract($this->_ci_cached_vars);

        ob_start();

        if ((bool) @ini_get('short_open_tag') === FALSE AND config_item('rewrite_short_tags') == TRUE)
        {
            echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
        }
        else
        {
            include($_ci_path); // include() vs include_once() allows for multiple views with the same name
        }
    }

    // --------------------------------------------------------------------

    function _ci_load_class($class, $params = NULL, $object_name = NULL)
    {

        $class = str_replace(EXT, '', trim($class, '/'));

        $subdir = '';
        if (($last_slash = strrpos($class, '/')) !== FALSE)
        {
            // Extract the path
            $subdir = substr($class, 0, $last_slash + 1);

            // Get the filename from the path
            $class = substr($class, $last_slash + 1);
        }

        // We'll test for both lowercase and capitalized versions of the file name
        foreach (array(ucfirst($class), strtolower($class)) as $class)
        {
            $subclass = APPPATH.'library/'.$class.EXT;

            // Is this a class extension request?
            if (file_exists($subclass))
            {
                $baseclass = BASEPATH.'library/'.ucfirst($class).EXT;
                if(file_exists($baseclass)){
    //                if ( ! file_exists($baseclass))
    //                {
    //                    show_error("Error: Could not load class: ".$class);
    //                }

                    if (in_array($subclass, $this->_ci_loaded_files))
                    {
                        if ( ! is_null($object_name))
                        {
                            $CI =& get_instance();
                            if ( ! isset($CI->$object_name))
                            {
                                return $this->_ci_init_class($class, '', $params, $object_name);
                            }
                        }

                        $is_duplicate = TRUE;
                        die($class." class already loaded. Second attempt ignored.");
                        return;
                    }
                    
                    include_once($baseclass);
                }
                include_once($subclass);
                $this->_ci_loaded_files[] = $subclass;

                return $this->_ci_init_class($class, '', $params, $object_name);
            }

            // Lets search for the requested library file and load it.
            $is_duplicate = FALSE;
            foreach ($this->_ci_library_paths as $path)
            {
                $filepath = $path.'library/'.$subdir.$class.EXT;

                if ( ! file_exists($filepath))
                {
                    continue;
                }


                if (in_array($filepath, $this->_ci_loaded_files))
                {

                    if ( ! is_null($object_name))
                    {
                        $CI =& get_instance();
                        if ( ! isset($CI->$object_name))
                        {
                            return $this->_ci_init_class($class, '', $params, $object_name);
                        }
                    }

                    $is_duplicate = TRUE;
                    return;
                }

                include_once($filepath);
                $this->_ci_loaded_files[] = $filepath;
                return $this->_ci_init_class($class, '', $params, $object_name);
            }

        } // END FOREACH


        if ($subdir == '')
        {
            $path = strtolower($class).'/'.$class;
            return $this->_ci_load_class($path, $params);
        }


        if ($is_duplicate == FALSE)
        {

            die("Error: Could not load class: ".$class);
        }
    }

    // --------------------------------------------------------------------

    function _ci_init_class($class, $prefix = '', $config = FALSE, $object_name = NULL)
    {
        // Is there an associated config file for this class?  Note: these should always be lowercase
        if ($config === NULL)
        {
            // Fetch the config paths containing any package paths
            $config_component = $this->_ci_get_component('config');

            if (is_array($config_component->_config_paths))
            {
                // Break on the first found file, thus package files
                // are not overridden by default paths
                foreach ($config_component->_config_paths as $path)
                {
                    // We test for both uppercase and lowercase, for servers that
                    // are case-sensitive with regard to file names. Check for environment
                    // first, global next
                    if (defined('ENVIRONMENT') AND file_exists($path .'config/'.ENVIRONMENT.'/'.strtolower($class).EXT))
                    {
                        include_once($path .'config/'.ENVIRONMENT.'/'.strtolower($class).EXT);
                        break;
                    }
                    elseif (defined('ENVIRONMENT') AND file_exists($path .'config/'.ENVIRONMENT.'/'.ucfirst(strtolower($class)).EXT))
                    {
                        include_once($path .'config/'.ENVIRONMENT.'/'.ucfirst(strtolower($class)).EXT);
                        break;
                    }
                    elseif (file_exists($path .'config/'.strtolower($class).EXT))
                    {
                        include_once($path .'config/'.strtolower($class).EXT);
                        break;
                    }
                    elseif (file_exists($path .'config/'.ucfirst(strtolower($class)).EXT))
                    {
                        include_once($path .'config/'.ucfirst(strtolower($class)).EXT);
                        break;
                    }
                }
            }
        }

        if ($prefix == '')
        {
            if (class_exists('CI_'.$class))
            {
                $name = 'CI_'.$class;
            }
            elseif (class_exists($class))
            {
                $name = $class;
            }
            else
            {
                $name = $class;
            }
        }
        else
        {
            $name = $prefix.$class;
        }

        // Is the class name valid?
        if ( ! class_exists($name))
        {
            log_message('error', "Non-existent class: ".$name);
            show_error("Non-existent class: ".$class);
        }

        // Set the variable name we will assign the class to
        // Was a custom class name supplied?  If so we'll use it
        $class = strtolower($class);

        if (is_null($object_name))
        {
            $classvar = ( ! isset($this->_ci_varmap[$class])) ? $class : $this->_ci_varmap[$class];
        }
        else
        {
            $classvar = $object_name;
        }

        // Save the class name and object name
        $this->_ci_classes[$class] = $classvar;

        // Instantiate the class
        $CI =& get_instance();
        if ($config !== NULL)
        {
            $CI->$classvar = new $name($config);
        }
        else
        {
            $CI->$classvar = new $name;
        }
    }

    // --------------------------------------------------------------------

    function _ci_autoloader()
    {
        if (defined('ENVIRONMENT') AND file_exists(APPPATH.'config/'.ENVIRONMENT.'/autoload'.EXT))
        {
            include_once(APPPATH.'config/'.ENVIRONMENT.'/autoload'.EXT);
        }
        else
        {
            include_once(APPPATH.'config/autoload'.EXT);
        }
        

        if ( ! isset($autoload))
        {
            return FALSE;
        }

        // Autoload packages
        if (isset($autoload['packages']))
        {
            foreach ($autoload['packages'] as $package_path)
            {
                $this->add_package_path($package_path);
            }
        }

        // Load any custom config file
        if (count($autoload['config']) > 0)
        {
            $CI =& get_instance();
            foreach ($autoload['config'] as $key => $val)
            {
                $CI->config->load($val);
            }
        }

        // Autoload helpers and languages
        foreach (array('helper', 'language') as $type)
        {
            if (isset($autoload[$type]) AND count($autoload[$type]) > 0)
            {
                $this->$type($autoload[$type]);
            }
        }

        // A little tweak to remain backward compatible
        // The $autoload['core'] item was deprecated
        if ( ! isset($autoload['libraries']) AND isset($autoload['core']))
        {
            $autoload['libraries'] = $autoload['core'];
        }

        // Load libraries
        if (isset($autoload['libraries']) AND count($autoload['libraries']) > 0)
        {
            // Load the database driver.
            if (in_array('database', $autoload['libraries']))
            {
                $this->database();
                $autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
            }

            // Load all other libraries
            foreach ($autoload['libraries'] as $item)
            {
                $this->library($item);
            }
        }

        // Autoload models
        if (isset($autoload['model']))
        {
            $this->model($autoload['model']);
        }
    }

    // --------------------------------------------------------------------

    function _ci_object_to_array($object)
    {
        return (is_object($object)) ? get_object_vars($object) : $object;
    }

    // --------------------------------------------------------------------

    function &_ci_get_component($component)
    {
        $CI =& get_instance();
        return $CI->$component;
    }

    // --------------------------------------------------------------------

    function _ci_prep_filename($filename, $extension)
    {
        if ( ! is_array($filename))
        {
            return array(strtolower(str_replace(EXT, '', str_replace($extension, '', $filename)).$extension));
        }
        else
        {
            foreach ($filename as $key => $val)
            {
                $filename[$key] = strtolower(str_replace(EXT, '', str_replace($extension, '', $val)).$extension);
            }

            return $filename;
        }
    }
}
