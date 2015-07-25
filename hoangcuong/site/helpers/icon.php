<?php
function icon_edit($link){
    $CI = get_instance();
    return '<a data-container="body" data-original-title="Cập nhật" href="'.site_url($link).'" data-toggle="tooltip" class="btn btn-xs btn-default add-tooltip"><i class="fa fa-pencil"></i></a>';
}
function icon_del($link){
    $CI = get_instance();
    return '<a data-container="body" data-original-title="Xóa" href="'.site_url($link).'" data-toggle="tooltip" class="btn btn-xs btn-danger add-tooltip delete_record"><i class="fa fa-times"></i></a>';
}
function icon_active($table,$field,$id,$status){
    if($status==1){
        $rep ='fa fa-check published';
        $title = "Tắt";
    }else{
        $rep ='fa fa-check un_published';
        $title = "Bật";
    }
    $html ='<span id="publish'.$id.'">';
    $html .='<a data-container="body" data-original-title="'.$title.'" href="javascript:;" onclick="publish('.$table.','.$field.','.$id.','.$status.');"  data-toggle="tooltip" class="btn btn-xs btn-default add-tooltip"><i class="'.$rep.'"></i></a>';
    $html .='</span>';
    return $html;
}
function action_order(){
    return '<a  style="overflow: hidden;padding-top: 5px;position: relative;top: 4px;font-size:18px;" onclick="save_order();" href="javascript:;"><i class="fa fa-save"></i></a>';     
}