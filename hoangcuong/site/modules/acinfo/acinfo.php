<?php
class acinfo extends vnit{
    protected $_templates;
    function __construct(){
        parent::__construct();
        $this->user_id = $_SESSION['user_id'];
    }
     function index(){
         $data['title'] = 'Thông tin tài khoản';
         $data['apply'] = true;
         $data['rs'] = $this->db->row("SELECT * FROM user WHERE user_id = ".$this->user_id);
         //form validation
         $this->form_validation->set_rules('fullname','Họ và tên','required');
         $this->form_validation->set_rules('email','Email','required|valid_email|callback__checkemailedit');
         $this->form_validation->set_rules('username','Email','required|callback__checkusernameedit');
         $this->form_validation->set_rules('password','Mật khẩu','');
         $this->form_validation->set_rules('re_password','Mật khẩu nhập lại','matches[password]');
         if($this->form_validation->run() === FALSE){
            $this->pre_message = validation_errors();
         }else{
             $vdata['username'] = $this->request->post['username'];
             $vdata['fullname'] = $this->request->post['fullname'];
             $vdata['email'] = $this->request->post['email'];
             if($_POST['password'] != ''){
                $vdata['password'] = md5($this->request->post['password']);
             }

             if($this->db->update('user',$vdata,array('user_id'=>$this->user_id))){
                 $this->session->set_flashdata('message','Lưu thành công');
                 redirect(uri_string());
             }
         }
         $data['message'] = $this->pre_message;
         $this->_templates['page'] = 'index';
         $this->load->templates($this->_templates['page'],$data);
     }
     
    function _checkusernameedit($username){
        $row = $this->db->row("SELECT username FORM user WHERE user_id !=".$this->user_id." AND username = '$username'");
        if($row){
            $this->form_validation->set_message('_checkusernameedit', 'Tên đăng nhập đã tồn tại trên hệ thống'); 
            return FALSE;
        }else{
            return true;
        }
    }
    function _checkemailedit($email){
        $row = $this->db->row("SELECT username FORM user WHERE user_id !=".$this->user_id." AND email = '$email'");
        if($row){
            $this->form_validation->set_message('_checkemailedit', 'Email đã tồn tại trên hệ thống'); 
            return FALSE;
        }else{
            return true;
        }
    } 
}
