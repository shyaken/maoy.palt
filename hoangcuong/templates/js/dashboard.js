// float title
$(window).on('load', function() {
    $(window).scroll(function(){
        var windowTop = $(window).scrollTop(); // returns number
        head = $("#navbar").height();
        //console.log(head+" - "+windowTop);
        if(windowTop > head){
            $("#page-title").addClass("toolbar_fix");
        }else{
            //$("#page-title").removeClass("affix");
            $("#page-title").removeClass("toolbar_fix");
        }
    })
})
$(document).ready(function(){
    $("#admindata").on("click","#checkall",function(){
        var is_checked = ($(this).is(':checked')) ? 1 :0 ;
        $("#admindata .itemcheck").each(function(){
           if(is_checked == 1){
               $(this).attr("checked","checked");
           }else{
               $(this).removeAttr("checked");
           }
        })
    })
    
    var link = '';
    $('.delete_record').click(function() {
        link = $(this).attr('href');
        if(link !=''){
            jConfirm('Bạn có chắc chắn muốn xóa mục đã chọn.<br />Chọn <b>Đồng ý</b> hoặc <b>Hủy bỏ</b>','Thông báo',function(r) {
                if(r){
                  window.location.href = link;
                }
            });           
        }
        return false;
    });
})
/*******************
* Action Toolbar
*/
// Save
function action_save()
{
    $('#admindata').append('<input type="hidden" name="option" value="save">');
    $('#admindata').submit();
   return true;
}

// Apply
function action_apply()
{
    $('#admindata').append('<input type="hidden" name="option" value="apply">');
    $('#admindata').submit();
   return true;
}

// Status Published, Unpublished
function publish(table,field,id,status){
    $("#publish"+id).html('<a href="javascript:;" class="btn btn-xs btn-default add-tooltip"><i class="fa fa-refresh fa-spin red"></i></a>');
    $.post(base_url+"api/publish",{'table':table,'field':field,'id':id,'status':status},function(data)
    {
        $("#publish"+id).html(data.published);                                               
        $("#publish"+id+" a").attr("data-original-title",data.status);                                               
    },'json');     
}

// Delete All Record
function action_del()
{
    var res;
    var checked = $('input[type=checkbox]').is(':checked');
    if(!checked){
        jAlert('Vui lòng chọn một mục để xóa','Thông báo');
        return false;
    }else{    
        jConfirm('Bạn có chắc chắn muốn xóa  mục đã chọn.<br />Chọn <b>Đồng ý</b> hoặc <b>Không đồng ý</b>','Thông báo',function(r) {
          if(r){
              $('#admindata').submit();
          }
        });
        return false;
    }
}
function setCheckboxes(the_form, id, do_check)
{
    var elts      = (typeof(document.forms[the_form].elements[id]) != 'undefined')
                  ? document.forms[the_form].elements[id]
                  : 0;
    var elts_cnt  = (typeof(elts.length) != 'undefined')
                  ? elts.length
                  : 0;

    if (elts_cnt) {
        for (var i = 0; i < elts_cnt; i++) {
            elts[i].checked = do_check;
            //$(elts[i].checked).parent('<span>').addClass().
        }
    } else {
        elts.checked        = do_check;
    }
return true; 
}
function check_chose(id, arid, the_form)
{
    var n = $('#'+id+':checked').val();
    if(n){
        setCheckboxes(the_form, arid, true);
        // Set Style Uifrom Checked 
        $("#"+the_form+" .checker span").addClass('checked');
        
    }else{
        setCheckboxes(the_form, arid, false);
        // Set Style Uifrom un Checked
        $("#"+the_form+" .checker span").removeClass('checked');
        
    }
}