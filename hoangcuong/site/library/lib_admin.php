<?php
class lib_admin{
    function __construct(){
        $this->V = get_instance(); 
        $this->lg_ = $this->V->session->data["lg_admin"]; 
    }
    
    function getMod($mod = ''){
        $sql = "SELECT * FROM `modules` WHERE `id_module` = '$mod'";
        return $this->V->db->row($sql);
    }
    
    function write_router(){
        $this->V->load->helper('file');
        $str = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n/**\n* File Router \n* Date: ".date('d/m/y H:i:s').".\n**/";
        // Category News
        $AllCatNews = $this->_Get_Cat_News();
        foreach($AllCatNews as $val):
        $cat_slug = $val->cat_slug;
        $str .= "\n\$route['$cat_slug'] = 'news/index';";
        $str .= "\n\$route['$cat_slug/(:num)'] = 'news/index/$1';";
        $str .= "\n\$route['$cat_slug/(:any)'] = 'news/detail/$1';";
        if($val->id_mod == 'HELP'){
            $str .= "\n\$route['$cat_slug'] = 'help/index';";
            $str .= "\n\$route['$cat_slug/(:num)'] = 'help/index/$1';";
            $str .= "\n\$route['$cat_slug/(:any)'] = 'help/detail/$1';";
        }
        endforeach;
                
        // Main Cat Tour
        $AllMain = $this->_Get_Cat_Tour_Main();
        foreach($AllMain as $val):
        $cat_slug = $val->slug;
        $str .= "\n\$route['$cat_slug/(:any)'] = 'tour/detail/$1';";
        endforeach;
        
        // Tour
        $AllCat = $this->_Get_Cat_Tour();
        foreach($AllCat as $val):
        $cat_slug = $val->slug;
        $str .= "\n\$route['$cat_slug'] = 'tour/index';";
        $str .= "\n\$route['$cat_slug/(:num)'] = 'tour/index/$1';";
        endforeach;

        
        // Loai Hinh
        $AllCat = $this->_Get_Loai_Hinh();
        foreach($AllCat as $val):
        $cat_slug = $val->slug;
        $str .= "\n\$route['$cat_slug'] = 'tour/chude';";
        $str .= "\n\$route['$cat_slug/(:num)'] = 'tour/chude/$1';";
        endforeach;
        

        
        write_file(ROOT.'site/config/route_site.php', $str);
    }
}
