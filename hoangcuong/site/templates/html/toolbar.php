<?php if(isset($save)){?>
<a class="btn btn-primary" onclick="return action_save();" title="" data-toggle="tooltip" href="javascript:;" data-original-title="Lưu"><i class="fa fa-save"></i></a>
<?php }?>
<?php if(isset($apply)){?>
<a class="btn btn-primary" onclick="return action_apply();" title="" data-toggle="tooltip" href="javascript:;" data-original-title="Áp dụng"><i class="fa fa-save"></i></a>
<?php }?>
<?php if(isset($cancel)){?>
<a class="btn btn-dark" title="" data-toggle="tooltip" href="<?=site_url($cancel)?>" data-original-title="Quay lại"><i class="fa fa-reply"></i></a>
<?php }?>

<?php if(isset($delete)){?>
<a class="btn btn-danger"  onclick="return action_del();" title="" data-toggle="tooltip" href="javascript:;" data-original-title="Xóa"><i class="fa fa-trash-o"></i></a>
<?php }?>    

<?php if(isset($add)){
$add = explode('|',$add);
$add_link = $add[0];
?> 
<a class="btn btn-primary" title="" data-toggle="tooltip" href="<?=site_url($add_link)?>" data-original-title="Thêm mới"><i class="fa fa-plus"></i></a>
<?php }?>

<?php if(isset($back)){
$back = explode('|',$back);
$back_link = $back[0];
?> 
<a class="btn btn-dark" title="" data-toggle="tooltip" href="<?=site_url($back_link)?>" data-original-title="Quay lại"><i class="fa fa-reply"></i></a>
<?php }?>