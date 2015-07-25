<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  function icon_edit($link){
      return '<a href="'.site_url($link).'" show_title="Cập nhật" id="vtip"><img src="'.base_url().'templates/icon/edit.png"></a>';
  }
  function icon_active($table,$field,$id,$status){
      if($status==1){
            $rep ='un_';
            return  '<a href="javascript:;" onclick="publish('.$table.','.$field.','.$id.','.$status.');" id="vtip" show_title="Tắt"><img src="'.base_url().'templates/icon/'.$rep.'lock.png"></a>';
      }else{
            $rep ='';
return  '<a href="javascript:;" onclick="publish('.$table.','.$field.','.$id.','.$status.');" id="vtip" show_title="Bật"><img src="'.base_url().'templates/icon/'.$rep.'lock.png"></a>';
      }
      
  }
  
  function icon_del($link){
      return '<a class="delete_record" href="'.site_url($link).'" id="vtip" show_title="Xóa"><img src="'.base_url().'templates/icon/del.png"></a>'; 
  }
  function action_del(){
      $CI =& get_instance(); 
      return '<a class="del" onclick="return action_del();"><span>Xóa</span></a>';
  }
  function action_order(){
     return '<a  style="overflow: hidden;padding-top: 5px;position: relative;top: 4px;" onclick="save_order();" href="javascript:;" id="vtip" show_title="Lưu sắp xếp"><span><img align="mid" src="'.base_url().'templates/icon/ordering.png"></span></a>';     
  }
      
  // Action del
  

  function action_save($value = 'Sắp xếp'){
     return '<a class="save" onclick="return action_save();"><span>'.$value.'</span></a>'; 
  }
  
  function action_apply(){
      
  } 
  // TootlBar

  function icon_score($link){
      return '<a href="'.site_url($link).'" show_title="Diễn biến trận đấu" id="vtip"><img src="'.base_url().'templates/icon/log.png"></a>';
  } 
?>
