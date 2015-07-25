<?php
class term extends vnit{
    function __construct(){
        parent::__construct();
        $this->load->model('term_model','term');
        $get = $this->request->get;
        $str = "";
        foreach($get as $key=>$val):
            if($val != ""){
                $str .= $key."=".$val."&";
            }
        endforeach;
        $str = rtrim($str,'&');
        $this->str = ($str != "")?"?".$str:"";
    }
    
    function ds(){
        $keyword = $this->request->get["keyword"];
        $catid = $this->request->get["catid"];
        $data["allcat"] = $this->term->getAllCategory();
        $data["keyword"] = $keyword;
        $data["catid"] = $catid;
        $data['title'] = "Danh sách link";
        $data['add'] = 'term/add';
        $data['delete'] = true;
        $config['base_url'] = base_url().'term/ds/';
        $config['suffix'] = $this->str;
        $config['total_rows']   =  $this->term->getNumLink($catid);
        $data['num'] = $config['total_rows'];
        $config['per_page']  =   10; 
        $config['uri_segment'] = 3; 
        $this->load->library('pagination');
        $this->pagination->initialize($config);   
        $data['list'] =   $this->term->getAllLink($config['per_page'],segment(3,'int'),$catid);
        $data['pagination']    = $this->pagination->create_links();
        $this->_templates['page'] = 'term/index';
        $this->load->templates($this->_templates['page'],$data);
    }
    
    function add(){
        $this->link[0] = "Quản lý link ";
        $this->link[1] = "Thêm mới";
        $data['title'] = "Thêm mới link";
        $data['save'] = true;
        $data['apply'] = true;
        $data['cancel'] = 'term/ds/';
        $data["allcat"] = $this->term->getAllCategory();
        // Form validation
        $this->form_validation->set_rules('vdata[link_google]','Link','required');
        if($this->form_validation->run() === FALSE){
            $this->pre_message = validation_errors();
        }else{
            $vdata = $this->request->post["vdata"];
            if($this->db->insert('wp_terms_link',$vdata)){
                $id = $this->db->insert_id();
                $this->session->set_flashdata('message','Lưu thành công');
                $option =  $_POST['option'];
                if($option == 'save'){
                   $url = 'term/ds/';
                }else{
                    $url = 'term/edit/'.$id;
                }
                redirect($url);
            }
        }
        $data['message'] = $this->pre_message;
        $this->_templates['page'] = 'term/edit';
        $this->load->templates($this->_templates['page'],$data);
    }
    
    function edit(){
        $id = segment(3,'int');
        $page_ = segment(4,'int');
        $this->link[0] = "Quản lý link ";
        $this->link[1] = "Cập nhật";
        $data['title'] = "Cập nhật";
        $data['save'] = true;
        $data['apply'] = true;
        $data['cancel'] = 'term/ds/'.$this->str;
        $data["allcat"] = $this->term->getAllCategory();
        $data['rs'] = $this->db->row("SELECT * FROM wp_terms_link WHERE ids = $id");
        // Form validation
        $this->form_validation->set_rules('vdata[link_google]','Link','required');
        if($this->form_validation->run() === FALSE){
            $this->pre_message = validation_errors();
        }else{
            $vdata = $this->request->post["vdata"];
            if($this->db->update('wp_terms_link',$vdata,array("ids"=>$id))){
                $id = $this->db->insert_id();
                $this->session->set_flashdata('message','Lưu thành công');
                $option =  $_POST['option'];
                if($option == 'save'){
                   $url = 'term/ds/'.$page_.'/'.$this->str;
                }else{
                    $url = uri_string();
                }
                redirect($url);
            }
        }
        $data['message'] = $this->pre_message;
        $this->_templates['page'] = 'term/edit';
        $this->load->templates($this->_templates['page'],$data);
    } 
    
    function del(){
        $id = segment(3,'int');
        $page_ = segment(4,'int');
        if($this->db->delete("wp_terms_link",array('ids'=>$id))){
            $error = 0;
            $msg = "Xóa link ID: ".$id." thành công";
        }else{
            $error = 1;
            $msg = "Xóa link ID: ".$id." không thành công";
        }
        $act = ($error == 1)?"notes":"message";
        $this->session->set_flashdata($act, $msg);
        redirect("term/ds/".$page_.'/'.$this->str);
    }
    
// Xoa nhieu ban ghi
    function dels(){                  
        if(!empty($this->request->post['ar_id']))
        {
            $msg = "";
            $ar_id = $_POST['ar_id'];
            $page_ = $this->request->post['page'];
            $str = $this->request->post['str'] ;
            for($i = 0; $i < sizeof($ar_id); $i ++) {
                $id = $ar_id[$i];
                if ($id){
                    if($this->db->delete('wp_terms_link',array('ids'=>$id))){
                        $msg .='<div>Link: ID <b>'.$catid.'</b> xóa thành công</div>';
                    }else{
                        $msg .='<div>Link: ID <b>'.$catid.'</b> không xóa thành công</div>';
                    }
                }
            }
        }
        $this->session->set_flashdata('message',$msg);
        redirect('term/ds/'.$page_.'/'.$str);
    }
}
