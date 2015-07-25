<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Icon Function Admindata
function icon_edit($link){
    $CI = get_instance();
    //if($CI->permit->get_permit_icon($link)){
        return '<a href="'.site_url($link).'" class="grouped_elements"  show_title="Cập nhật" id="vtip"><img src="'.base_url().'templates/icon/edit.png"></a>';
    /*}else{
        return '';
    }*/
}

function icon_edit1($link){
    $CI = get_instance();
    return '<a href="'.site_url($link).'" class="grouped_elements"  show_title="Cập nhật" id="vtip"><img src="'.base_url().'templates/icon/edit.png"></a>';
}
function icon_view($link){
    return '<a href="'.site_url($link).'" class="grouped_elements"  show_title="Xem" id="vtip"><img src="'.base_url().'templates/icon/view.png"></a>';
}
function icon_pay($link){
    return '<a href="'.site_url($link).'" class="grouped_elements" title="Thanh toán"><img src="'.base_url().'templates/icon/log.png"></a>';
}  
function icon_print($link){
    return '<a href="'.site_url($link).'" target="_blank" title="Print" onClick="return popup(this, \'notes\');"><img src="'.base_url().'templates/icon/print.png"></a>';
}  
function icon_theodoi($id,$status){
    if($status == 0){
        $rep ='un_';
        $theodoi = "Theo dõi văn bản";
    }else{
        $rep ='';
        $theodoi = "Bỏ theo dõi";
    }
    return  '<a href="javascript:;" onclick="theodoi('.$id.','.$status.');" title="'.$theodoi.'"><img src="'.base_url().'templates/icon/'.$rep.'theodoi.png"></a>';
}
function icon_star($table,$field,$id,$status){
    if($status==0){
        $rep ='un_';
        $title = 'Không quan trọng';
    }else{
        $rep ='';
        $title = 'Quan trọng';
    }
    return  '<a href="javascript:;" onclick="star(\''.$table.'\',\''.$field.'\','.$id.','.$status.');" title="'.$title.'"><img src="'.base_url().'templates/icon/'.$rep.'star.png"></a>';
}
function icon_active($table,$field,$id,$status){
    if($status==1){
        $rep ='un_';
    }else{
        $rep ='';
    }
    return  '<a href="javascript:;" onclick="publish('.$table.','.$field.','.$id.','.$status.');" title="Bật | Tắt"><img src="'.base_url().'templates/icon/'.$rep.'lock.png"></a>';
}

function icon_del($link){
    /*$CI = get_instance();
    if($CI->permit->get_permit_icon($link)){*/
        return '<a class="delete_record" href="'.site_url($link).'"  show_title="Xóa" id="vtip"><img src="'.base_url().'templates/icon/del.png"></a>'; 
    /*}else{
        return '';
    }*/
}
function icon_del1($link){
    return '<a class="delete_record" href="'.site_url($link).'"  show_title="Xóa" id="vtip"><img src="'.base_url().'templates/icon/del.png"></a>'; 
}
function action_del($form ='admindata',$div ='content'){
    $CI =& get_instance(); 
    return '<a class="del" show_title="Xóa" id="vtip" onclick="return action_del(\''.$form.'\',\''.$div.'\');"><span>Xóa</span></a>';
}
function action_order(){
    return '<a  style="overflow: hidden;padding-top: 5px;position: relative;top: 4px;" onclick="save_order();" href="javascript:;"><span><img align="mid" src="'.base_url().'templates/icon/ordering.png"></span></a>';     
}
  
// Action del


function action_save(){
    return '<a class="save" onclick="return action_save();"><span>Lưu</span></a>'; 
}

function action_apply(){
  
} 
