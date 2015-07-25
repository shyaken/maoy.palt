<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-cog fa-fw"></i> <?=$title?></h3>
    </div>
    <div class="panel-body">
        <?php echo form_open(uri_string(),array("id"=>"admindata","class"=>"form-horizontal"))?>
            <div class="form-group">
                <label class="col-sm-2 control-label">Danh mục cha</label>
                <div class="col-sm-10">
                    <select name="vdata[term_id]" class="form-control">
                        <?php foreach($allcat as $val):
                        $subcat = $this->term->getAllCategory($val->term_id);
                        ?>
                        <option value="<?php echo $val->term_id?>" <?php echo (isset($rs))?($val->term_id == $rs->term_id)?'selected="selected"':'':'';?>><?php echo $val->name?></option>
                        <?php foreach($subcat as $val1):?>
                            <option value="<?php echo $val1->term_id?>" <?php echo (isset($rs))?($val1->term_id == $rs->term_id)?'selected="selected"':'':'';?>>|___ <?php echo $val1->name?></option>
                        <?php endforeach;?>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Link</label>
                <div class="col-sm-10">
                    <input type="text" name="vdata[link_google]" value="<?php echo (isset($rs))?$rs->link_google:""?>" class="form-control" placeholder="Link">
                </div>
            </div>
        <?php echo form_close();?>
    </div>
</div>