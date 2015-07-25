
<?=form_open(uri_string(),array('id'=>'admin_login'))?>
<div id="loginLogin"></div>
<div id="loginform">
    <div id="login-form">
        <div id="loginhead"></div>
        <div class="label">
            <input class="username" type="text" onblur="if(this.value=='')this.value='Tên đăng nhập'" onfocus="if(this.value=='Tên đăng nhập')this.value=''" value="Tên đăng nhập" name="username">
        </div>
        <div class="label">
            <input class="password" type="password" onblur="if(this.value=='')this.value='Mật khẩu'" onfocus="if(this.value=='Mật khẩu')this.value=''" value="Mật khẩu" name="password">
        </div>
        <div class="label">
            <input type="submit" value="" class="bt_login">
        </div>
        <div class="list">
            <a href="javascript:;" onclick="forgot_pass()">Quên mật khẩu?</a>
        </div>
        <div class="form_message">
        <? if($msg != ''){ echo '<div id="msg" class="show_notice">'.$msg.'</div>';}?> 
        <?=$this->session->get_flashdata('message')?>
        </div>
    </div>
    <div id="forgot-pass" style="display: none;">
        <div id="loginhead_e"></div>
        <div class="label">
            <input class="email" id="email" type="text" onblur="if(this.value=='')this.value='Địa chỉ Email'" onfocus="if(this.value=='Địa chỉ Email')this.value=''" value="Địa chỉ Email" name="email">
        </div>
        <div class="label">
            <input type="button" onclick="send_pass()" value="" class="bt_sendpass">
        </div>
        <div class="list">
            <a href="javascript:;" onclick="form_login()">Đăng nhập hệ thống</a>
        </div>
        <?
        function isURL($url)
        {
            $pattern = '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@';
            return preg_match($pattern, $url);
        }
        echo isURL('360vnit.com');
        ?>
    </div>
</div>
<?=form_close()?>
