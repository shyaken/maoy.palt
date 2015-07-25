<?php
class setting extends vnit{
    function __construct(){
        parent::__construct();
        $this->load->model('setting_model','setting');
    }
    
    function site(){
        $this->load->config('config_site');
        $this->link[0] = "Cấu hình Website";
        $data['title'] = "Cấu hình Website";
        $data['save'] = true;
        $row = $this->setting->getSetting("site_config");
        $data["row"] = unserialize($row->setting_value);
        $this->form_validation->set_rules('vdata[site_name]','Tên website','required');
        $this->form_validation->set_rules('vdata[email]','Email hệ thống','required');
        $this->form_validation->set_rules('vdata[des]','Miêu tả','required');
        $this->form_validation->set_rules('vdata[keyword]','Từ khóa','required');
        if($this->form_validation->run() === FALSE){
            $this->pre_message = validation_errors();
        }else{
            $str = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n/**\n* File Config_site \n* Date: ".date('d/m/y H:i:s').".\n**/";
            $vdata = $this->request->post['vdata'];
            $setting["setting_value"] = serialize($vdata);
            $this->db->update("settings",$setting,array("setting_key"=>"site_config"));
            $site_name = $vdata['site_name'];
            $site_email = $vdata['email'];
            $site_des = $vdata['des'];
            $site_keyword = $vdata['keyword'];
            $site_facebook = $vdata['facebook'];
            $site_google = $vdata['google'];
            $site_twitter = $vdata['twitter'];
            
            
            $str .= "\n\$config['site_name'] = '$site_name';";  
            $str .= "\n\$config['site_email'] = '$site_email';";  
            $str .= "\n\$config['site_des'] = '$site_des';";  
            $str .= "\n\$config['site_keyword'] = '$site_keyword';"; 
            $str .= "\n\$config['site_facebook'] = '$site_facebook';"; 
            $str .= "\n\$config['site_google'] = '$site_google';"; 
            $str .= "\n\$config['site_twitter'] = '$site_twitter';"; 

            $str .= "\n\n/* End of file config_site*/";
            $this->load->helper('file');
            if(write_file(APPPATH.'config/config_site.php', $str)){
                $this->session->set_flashdata('message','Lưu thành công');
                redirect('setting/site') ;     
            }else{
                $this->pre_message =" Lưu không thành công";
            }
        }
        $data['message'] = $this->pre_message;
        $this->load->templates('site',$data);
    }
    
    function seo(){
        $this->load->config('config_seo');
        $data['title'] = "Seo Code";
        $this->link[0] = "Seo code";
        $data['save'] = true;
        $row = $this->setting->getSetting("seo_config");
        $data["row"] = unserialize($row->setting_value);
        $this->form_validation->set_rules('yahoo','Yahoo code','');
        $this->form_validation->set_rules('alexa','Alexa','');
        $this->form_validation->set_rules('google_webmaster','Google Webmaster','');
        $this->form_validation->set_rules('google_analytics','Google Analytics','');
        if($this->form_validation->run() == FALSE){
            $this->pre_message = validation_errors();
        }else{
            $str = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n/**\n* File config_seo \n* Date: ".date('d/m/y H:i:s').".\n**/";
            $vdata = $this->request->post["vdata"];
            $setting["setting_value"] = serialize($vdata);
            $this->db->update("settings",$setting,array("setting_key"=>"seo_config"));            
            $cf_yahoo = $vdata['yahoo'];
            $cf_alexa = $vdata['alexa'];
            $cf_google_webmaster = $vdata['google_webmaster'];
            $cf_google_analytics = $vdata['google_analytics'];
            $str .= "\n\$config['cf_yahoo'] = '$cf_yahoo';";  
            $str .= "\n\$config['cf_alexa'] = '$cf_alexa';";  
            $str .= "\n\$config['cf_google_webmaster'] = '$cf_google_webmaster';";  
            $str .= "\n\$config['cf_google_analytics'] = '$cf_google_analytics';";  
            
            $str .= "\n\n/* End of file config_seo*/";   
            $this->load->helper('file');
            write_file(APPPATH.'config/config_seo.php', $str);    
            $this->session->set_flashdata('message','Lưu thành công');
            redirect('setting/seo') ;     
        }
        $data['message'] = $this->pre_message;
        $this->_templates['page'] = 'seo';
        $this->load->templates($this->_templates['page'],$data);
    }
    
    function mail_smtp(){
        $this->load->config('config_smtp');
        $data['title'] = "Mail SMTP";
        $this->link[0] = "Mail SMTP";
        $data['save'] = true;
        $row = $this->setting->getSetting("smtp_config");
        $data["row"] = unserialize($row->setting_value);
        $this->form_validation->set_rules('smtp_hostname','Yahoo code','');
        if($this->form_validation->run() == FALSE){
            $this->pre_message = validation_errors();
        }else{
            $str = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n/**\n* File config_smtp \n* Date: ".date('d/m/y H:i:s').".\n**/";
            $vdata = $this->request->post["vdata"];
            $setting["setting_value"] = serialize($vdata);
            $this->db->update("settings",$setting,array("setting_key"=>"smtp_config"));            
            $smtp_hostname = $vdata['smtp_hostname'];
            $smtp_port = $vdata['smtp_port'];
            $smtp_security = $vdata['smtp_security'];
            $smtp_username = $vdata['smtp_username'];
            $smtp_password = $vdata['smtp_password'];
            $str .= "\n\$config['smtp_hostname'] = '$smtp_hostname';";  
            $str .= "\n\$config['smtp_port'] = '$smtp_port';";  
            $str .= "\n\$config['smtp_security'] = '$smtp_security';";  
            $str .= "\n\$config['smtp_username'] = '$smtp_username';";  
            $str .= "\n\$config['smtp_password'] = '$smtp_password';";  
            
            $str .= "\n\n/* End of file config_smtp*/";   
            $this->load->helper('file');
            write_file(APPPATH.'config/config_smtp.php', $str);    
            $this->session->set_flashdata('message','Lưu thành công');
            redirect('setting/mail_smtp') ;     
        }
        $data['message'] = $this->pre_message;
        $this->_templates['page'] = 'smtp';
        $this->load->templates($this->_templates['page'],$data);
    }
    
    function banner(){
        $data['apply'] = true;
        $this->load->config('config_banner');
        $data['title'] = "Tùy chỉnh banner";
        $this->form_validation->set_rules('token','','');
        if($this->form_validation->run()){
            if($_FILES["userfile"]["size"] > 0){
                $config['upload_path'] = ROOT.'data';
                $config['allowed_types'] = '*';
                $config['max_size']    = '10000';
                $config['file_name'] =  $data['slug'];
                $this->load->library('upload', $config);
                $this->upload->initialize($config);                     
                       
                if ( !$this->upload->do_upload()){
                    $this->pre_message =  $this->upload->display_errors();
                    $this->session->set_flashdata('error',$this->pre_message);
                    redirect(uri_string());
                }else{                         
                    $result =  $this->upload->data();
                    $banner = $result['file_name'];  
                    $str = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n/**\n* File config_seo \n* Date: ".date('d/m/y H:i:s').".\n**/";
                    $str .= "\n\$config['banner'] = '$banner';";  
                    $str .= "\n\n/* End of file config_seo*/";   
                    $this->load->helper('file');
                    if(write_file(ROOT.'site/config/config_banner.php', $str)){
                        $msg = "Lưu thành công";
                    }else{
                        $msg = 'Lưu không thành công';
                    }
                    $this->session->set_flashdata('message',$msg);
                    redirect(uri_string());
                }                    
            }else{
                $this->pre_message = "Vui lòng chọn File để tải lên";
            }                                                        
        }
        $data['message'] = $this->pre_message;
        $this->load->templates('banner',$data);
    }
}
