<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-cog fa-fw"></i> <?=$title?></h3>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <div class="col-sm-3">
                <select id="term_id" class="form-control">
                    <option value="0">Tất cả danh mục</option>
                    <?php foreach($allCat as $val):
                    $subcat = $this->admincp->getAllCategory($val->term_id);
                    ?>
                    <option value="<?php echo $val->term_id?>" <?php echo (isset($rs))?($val->term_id == $rs->term_id)?'selected="selected"':'':'';?>><?php echo $val->name?></option>
                    <?php foreach($subcat as $val1):?>
                        <option value="<?php echo $val1->term_id?>" <?php echo (isset($rs))?($val1->term_id == $rs->term_id)?'selected="selected"':'':'';?>>|___ <?php echo $val1->name?></option>
                    <?php endforeach;?>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="col-sm-5">
                <button type="button" id="clickauto" class="btn btn-danger input-sm">Lấy dữ liệu</button>
            </div>
        </div>
        <div class="progress">
            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%</div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function($) {
    $("#clickauto").click(function(){
        auto();
        $(".progress-bar").css({width:'0'}); 
        $(".progress-bar").attr("aria-valuenow","0");
    })    
})
var active = 1;

function auto(){
    //if(active = 1){
        //active = 0;
        var term_id = $("#term_id").val();
        $.get(base_url+'google/cron.php', {'term_id':term_id} , function(data) {
            //$.get(base_url+'google/session.php', function(data){ 
                if(data.chua_thuc_hien == 0){
                    $('.progress-bar').html('100%');
                    $(".progress-bar").css({width:'100%'}); 
                    $(".progress-bar").attr("aria-valuenow","100"); 
                    return false;
                     
                }else{
                    $('.progress-bar').html(data.phantram+'%');
                    $(".progress-bar").css({'width':data.phantram+'%'});
                    $(".progress-bar").attr("aria-valuenow",data.phantram);
                   auto();
                }
                if(data.ok == 1){
                    active = 1;
                }   
                
            //},'json')
        },'json');
    //}
}
function auto_cron(){
    setInterval("auto()", 10000);
}
</script>