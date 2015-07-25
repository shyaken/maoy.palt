<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-cog fa-fw"></i> <?=$title?></h3>
    </div>
    <div class="panel-body">
        <?php echo form_open(uri_string(),array("id"=>"admindata","class"=>"form-horizontal"))?>
            <div class="form-group">
                <label class="col-sm-2 control-label">Google Analytics</label>
                <div class="col-sm-10">
                    <input type="text" name="vdata[google_analytics]" value="<?php echo $row["google_analytics"]?>" class="form-control" placeholder="Google Analytics">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Google Webmaster</label>
                <div class="col-sm-10">
                    <input required email type="text" name="vdata[google_webmaster]" value="<?php echo $row["google_webmaster"]?>" class="form-control" placeholder="Google Webmaster">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Yahoo</label>
                <div class="col-sm-10">
                    <input type="text" name="vdata[yahoo]" value="<?php echo $row["yahoo"]?>" class="form-control" placeholder="Yahoo">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Alexa</label>
                <div class="col-sm-10">
                    <input type="text" name="vdata[alexa]" value="<?php echo $row["alexa"]?>" class="form-control" placeholder="Yahoo">
                </div>
            </div>
        <?php echo form_close();?>
    </div>
</div>