<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-cog fa-fw"></i> <?=$title?></h3>
    </div>
    <div class="panel-body">
        <?php echo form_open(uri_string(),array("id"=>"admindata","class"=>"form-horizontal"))?>
            <div class="form-group">
                <label class="col-sm-2 control-label">Title</label>
                <div class="col-sm-10">
                    <input type="text" name="vdata[smtp_title]" value="<?php echo $row["smtp_title"]?>" class="form-control" placeholder="Site name">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">SMTP hostname</label>
                <div class="col-sm-10">
                    <input type="text" name="vdata[smtp_hostname]" value="<?php echo $row["smtp_hostname"]?>" class="form-control" placeholder="Site name">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">SMTP Port</label>
                <div class="col-sm-10">
                    <input required email type="text" name="vdata[smtp_port]" value="<?php echo $row["smtp_port"]?>" class="form-control" placeholder="Email">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">SMTP Security</label>
                <div class="col-sm-10">
                    <select name="vdata[smtp_security]" class="form-control">
                        <option value="tls" <?php echo ($row["smtp_security"] == "tls")?'selected="selected"':'';?>>TLS</option>
                        <option value="ssl" <?php echo ($row["smtp_security"] == "ssl")?'selected="selected"':'';?>>SSL</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">SMTP Username</label>
                <div class="col-sm-10">
                    <input type="text" name="vdata[smtp_username]" value="<?php echo $row["smtp_username"]?>" class="form-control" placeholder="Từ khóa">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">SMTP Password</label>
                <div class="col-sm-10">
                    <input type="text" name="vdata[smtp_password]" value="<?php echo $row["smtp_password"]?>" class="form-control" placeholder="Facebook page">
                </div>
            </div>
        <?php echo form_close();?>
    </div>
</div>