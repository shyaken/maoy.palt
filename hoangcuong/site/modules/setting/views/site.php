<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-cog fa-fw"></i> <?=$title?></h3>
    </div>
    <div class="panel-body">
        <?php echo form_open(uri_string(),array("id"=>"admindata","class"=>"form-horizontal"))?>
            <div class="form-group">
                <label class="col-sm-2 control-label">Site Name</label>
                <div class="col-sm-10">
                    <input type="text" name="vdata[site_name]" value="<?php echo $row["site_name"]?>" class="form-control" placeholder="Site name">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input required email type="text" name="vdata[email]" value="<?php echo $row["email"]?>" class="form-control" placeholder="Email">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Miêu tả</label>
                <div class="col-sm-10">
                    <input type="text" name="vdata[des]" value="<?php echo $row["des"]?>" class="form-control" placeholder="Miêu tả">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Từ khóa</label>
                <div class="col-sm-10">
                    <input type="text" name="vdata[keyword]" value="<?php echo $row["keyword"]?>" class="form-control" placeholder="Từ khóa">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Facebook page</label>
                <div class="col-sm-10">
                    <input type="text" name="vdata[facebook]" value="<?php echo $row["facebook"]?>" class="form-control" placeholder="Facebook page">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Google Plus</label>
                <div class="col-sm-10">
                    <input type="text" name="vdata[google]" value="<?php echo $row["google"]?>" class="form-control" placeholder="Google Plus">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Twitter</label>
                <div class="col-sm-10">
                    <input type="text" name="vdata[twitter]" value="<?php echo $row["twitter"]?>" class="form-control" placeholder="twitter">
                </div>
            </div>
        <?php echo form_close();?>
    </div>
</div>