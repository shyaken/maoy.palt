<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-cog fa-fw"></i> <?=$title?></h3>
    </div>
    <div class="panel-body">
        <?php echo form_open(uri_string(),array("id"=>"admindata","class"=>"form-horizontal"))?>
            <div class="form-group">
                <label class="col-sm-2 control-label">Họ tên</label>
                <div class="col-sm-10">
                    <input type="text" name="fullname" value="<?php echo $rs->fullname;?>" class="form-control" placeholder="Họ tên">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input type="text" name="email" value="<?php echo $rs->email;?>" class="form-control" placeholder="Email">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Tên đăng nhập</label>
                <div class="col-sm-10">
                    <input type="text" name="username" value="<?php echo $rs->username;?>" class="form-control" placeholder="Tên đăng nhập">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Mật khẩu</label>
                <div class="col-sm-10">
                    <input type="text" name="password" value="" class="form-control" placeholder="Mật khẩu">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Mật khẩu nhắc lại</label>
                <div class="col-sm-10">
                    <input type="text" name="re_password" value="" class="form-control" placeholder="Mật khẩu nhắc lại">
                </div>
            </div>
        <?php echo form_close();?>
    </div>
</div>
