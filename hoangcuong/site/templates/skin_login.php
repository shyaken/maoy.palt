<?php
if($_SESSION['user_id'] && $_SESSION['group_id'] > 11){
    redirect('cpanel');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html>
<head>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Đăng nhập hệ thống</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link href="<?php echo base_url() ?>templates/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>templates/css/pixel-admin.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>templates/css/widgets.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>templates/css/pages.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>templates/css/rtl.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>templates/css/themes.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>templates/css/alert.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>templates/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script src="<?php echo base_url() ?>templates/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript">
        var base_url = '<?php echo base_url()?>';
        var init = [];
    </script>
    <style>
        #signin-demo {
            position: fixed;
            right: 0;
            bottom: 0;
            z-index: 10000;
            background: rgba(0,0,0,.6);
            padding: 6px;
            border-radius: 3px;
        }
        #signin-demo img { cursor: pointer; height: 40px; }
        #signin-demo img:hover { opacity: .5; }
        #signin-demo div {
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding-bottom: 6px;
        }
    </style>
</head>
<body class="theme-default page-signin">
<!-- Demo script --> <script src="<?php echo base_url() ?>templates/js/demo.js"></script> <!-- / Demo script -->

    <!-- Page background -->
    <div id="page-signin-bg">
        <!-- Background overlay -->
        <div class="overlay"></div>
        <!-- Replace this with your bg image -->
        <img src="<?php echo base_url()?>templates/images/signin-bg-7.jpg" alt="">
    </div>
    <!-- / Page background -->

    <!-- Container -->
    <div class="signin-container">

        <!-- Left side -->
        <div class="signin-info">
            <a href="http://imos.vn" class="logo">
                <img src="<?php echo base_url()?>templates/images/logo.png" alt="" style="margin-top: -5px;">
  
            </a> <!-- / .logo -->
            <div class="slogan">
                Đơn giản - Linh hoạt - Mạnh mẽ
            </div> <!-- / .slogan -->
            <ul>
                <li><i class="fa fa-sitemap signin-icon"></i> Cấu trúc mô-đun linh hoạt</li>
                <li><i class="fa fa-file-text-o signin-icon"></i> LESS &amp; SCSS source files</li>
                <li><i class="fa fa-outdent signin-icon"></i> RTL direction support</li>
                <li><i class="fa fa-heart signin-icon"></i> Crafted with love</li>
            </ul> <!-- / Info list -->
        </div>
        <!-- / Left side -->

        <!-- Right side -->
        <div class="signin-form">

            <!-- Form -->
            <?php echo form_open(uri_string())?>
                <div class="signin-text">
                    <span>Đăng nhập hệ thống</span>
                </div> <!-- / .signin-text -->

                <div class="form-group w-icon">
                    <input type="text" name="username" value="<?php echo set_value("username")?>" id="username_id" class="form-control input-lg" placeholder="Tên đăng nhập hoặc email">
                    <span class="fa fa-user signin-form-icon"></span>
                </div> <!-- / Username -->

                <div class="form-group w-icon">
                    <input type="password" name="password" id="password_id" class="form-control input-lg" placeholder="Mật khẩu">
                    <span class="fa fa-lock signin-form-icon"></span>
                </div> <!-- / Password -->

                <div class="form-actions">
                    <input type="submit" value="Đăng nhập" class="signin-btn bg-primary">
                    <!--<a href="#" class="forgot-password" id="forgot-password-link">Forgot your password?</a>-->
                </div> <!-- / .form-actions -->
            </form>
            <!-- / Form -->

            <!-- "Sign In with" block -->
            <div class="signin-with">
                <!-- Facebook -->
                <?php if(isset($message) && $message !=''){ echo '<div class="alert alert-danger alert-dark" id="msg"><button data-dismiss="alert" class="close"><span>×</span></button>'.$message.'</div>';}else{?>
                <a href="#" class="signin-with-btn" style="background:#4f6faa;background:rgba(79, 111, 170, .8);">Sign In with <span>Facebook</span></a>
                <?}?>
            </div>
            <!-- / "Sign In with" block -->

            <!-- Password reset form -->
            <div class="password-reset-form" id="password-reset-form">
                <div class="header">
                    <div class="signin-text">
                        <span>Password reset</span>
                        <div class="close">&times;</div>
                    </div> <!-- / .signin-text -->
                </div> <!-- / .header -->
                
                <!-- Form -->
                <form action="index.html" id="password-reset-form_id">
                    <div class="form-group w-icon">
                        <input type="text" name="password_reset_email" id="p_email_id" class="form-control input-lg" placeholder="Enter your email">
                        <span class="fa fa-envelope signin-form-icon"></span>
                    </div> <!-- / Email -->

                    <div class="form-actions">
                        <input type="submit" value="SEND PASSWORD RESET LINK" class="signin-btn bg-primary">
                    </div> <!-- / .form-actions -->
                </form>
                <!-- / Form -->
            </div>
            <!-- / Password reset form -->
        </div>
        <!-- Right side -->
    </div>
    <!-- / Container -->

    <?=$this->session->unset_flashdata(array('message','error','alert','notes'))?>
    <script src="<?php echo base_url() ?>templates/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>templates/js/pixel-admin.min.js"></script>
    <script type="text/javascript">
        // Resize BG
        init.push(function () {
            var $ph  = $('#page-signin-bg'),
                $img = $ph.find('> img');

            $(window).on('resize', function () {
                $img.attr('style', '');
                if ($img.height() < $ph.height()) {
                    $img.css({
                        height: '100%',
                        width: 'auto'
                    });
                }
            });
        });

        // Show/Hide password reset form on click
        init.push(function () {
            $('#forgot-password-link').click(function () {
                $('#password-reset-form').fadeIn(400);
                return false;
            });
            $('#password-reset-form .close').click(function () {
                $('#password-reset-form').fadeOut(400);
                return false;
            });
        });

        // Setup Sign In form validation
        init.push(function () {
            $("#signin-form_id").validate({ focusInvalid: true, errorPlacement: function () {} });
            
            // Validate username
            $("#username_id").rules("add", {
                required: true,
                minlength: 3
            });

            // Validate password
            $("#password_id").rules("add", {
                required: true,
                minlength: 6
            });
        });

        // Setup Password Reset form validation
        init.push(function () {
            $("#password-reset-form_id").validate({ focusInvalid: true, errorPlacement: function () {} });
            
            // Validate email
            $("#p_email_id").rules("add", {
                required: true,
                email: true
            });
        });

        window.PixelAdmin.start(init);
    </script>
   
</body>
</html>