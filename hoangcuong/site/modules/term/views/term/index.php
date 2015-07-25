<?php
$page_ = (int)$this->uri->segment(3);
?>
<div class="panel colourable">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo $title;?></h3>
    </div>
    <ul class="list-group">
        <li class="list-group-item">
            <form method="get" action="<?php echo site_url("term/ds")?>">
                <div class="row">

                    <div class="col-sm-3"> 
                        <select name="catid" class="form-control input-sm">
                            <option value="">Tất cả danh mục</option>
                            <?foreach($allcat as $val):
                            $sub = $this->term->getAllCategory($val->term_id);
                            ?>
                            <option value="<?=$val->term_id?>" <?php echo ($catid == $val->term_id)?' selected="selected"':''?>><?=$val->name?></option>
                                <?foreach($sub as $val1):?>
                                <option value="<?=$val1->term_id?>" <?php echo ($catid == $val1->term_id)?' selected="selected"':''?>>|___<?=$val1->name?></option>
                                <?endforeach;?>
                            <?endforeach;?>
                        </select>
                    </div>
                    <div class="col-sm-2"> 
                        <button type="submit" class="btn btn-danger input-sm">Tìm kiếm</button>
                    </div>
                </div>
            </form>
        </li>
    </ul>
    <?php echo form_open("term/dels",array("id"=>"admindata"));?>
    <input type="hidden" name="page" value="<?php echo $page_?>">
    <input type="hidden" name="str" value="<?php echo $this->str?>">
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:3ex">
                        <input type="checkbox" name="sa" id="sa" onclick="check_chose('sa', 'ar_id[]', 'admindata')">
                    </th>
                    <th style="width:100px">Danh mục</th>
                    <th>Link</th>
                    <th class="text-right" style="width:20ex">Chức năng</th>
                </tr>
            </thead>
            <?php foreach($list as $rs):    
            ?>
            <tr>
                <td><input  type="checkbox" name="ar_id[]" value="<?php echo $rs->ids?>"></td>
                <td><?php echo $rs->name?></td>
                <td><?php echo $rs->link_google;?></td>
                <td class="text-right">
                    <?php echo icon_edit('term/edit/'.$rs->ids.'/'.$page_.'/'.$this->str)?>
                    <?php echo icon_del('term/del/'.$rs->ids.'/'.$page_.'/'.$this->str)?>
                </td>
            </tr>
            <?php endforeach;?>
        </table>
    </div>
    <?php echo form_close();?>
    <div class="table-footer clearfix">
        <div class="DT-label">Hiện có <?php echo $num?> record</div>
        <div class="DT-pagination"><?php echo $pagination;?></div>
    </div>
</div>