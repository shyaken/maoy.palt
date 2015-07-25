<?php
class home extends vnit{
    function __construct(){
        parent::__construct();
        $this->load->model('home_model','home');
    }
    
    function index(){
        $data['title'] = 'Trang chu';
        $this->form_validation->set_rules('username','Tên đăng nhập','required');
        $this->form_validation->set_rules('password','Mật khẩu','required');
        if($this->form_validation->run() == false){
            $this->pre_message = validation_errors();
            
        }else{
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            if($this->auth->check_user($username)){
                if($this->auth->check_user_password($username, $password)){
                        $this->auth->set_auth($username, $password);
                        $this->session->set_flashdata('message','Đăng nhập thành công');
                        redirect('cpanel');
                }else{
                    $this->pre_message = "Mật khẩu không chính xác";    
                }
            }else{
                $this->pre_message = "Tên đăng nhập không tồn tại";
            }
        }
        $data['message'] = $this->pre_message;
        $this->load->templates('index',$data,'login');
        
    }
    
    function sendpass(){
        $email = $_POST['email'];
        if($email == ''){
            $data['error'] = 1;
            $data['msg'] = "Vui lòng nhập địa chỉ Email";
        }else{
            if($this->home->check_email_exit($email)){
                $data['error'] = 0;
                $data['msg'] = "Mật khẩu mới đã được gửi tới Email: ".$email;
            }else{
                $data['error'] = 1;
                $data['msg'] = "Email không tồn tại trên hệ thống"; 
            }
        }
        echo json_encode($data);
    }
    
    function logout(){
        session_destroy();
        redirect();
    }
}
