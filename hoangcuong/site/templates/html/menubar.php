<div id="main-navbar" class="navbar navbar-inverse" role="navigation">
    <!-- Main menu toggle -->
    <button type="button" id="main-menu-toggle"><i class="navbar-icon fa fa-bars icon"></i><span class="hide-menu-text">HIDE MENU</span></button>
    <div class="navbar-inner">
        <!-- Main navbar header -->
        <div class="navbar-header">
            <!-- Logo -->
            <a href="<?php echo base_url()?>" class="navbar-brand">
                <img alt="Pixel Admin" src="<?php echo base_url() ?>templates/images/logo_small.png">
            </a>
            <!-- Main navbar toggle -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse"><i class="navbar-icon fa fa-bars"></i></button>
        </div> 
        <!-- / .navbar-header -->
        <div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
            <div>
<!--                <ul class="nav navbar-nav">
                    <li>
                        <a href="<?php echo base_url();?>">Bảng điều khiển</a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Cấu hình</a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo site_url("setting/site");?>">Thông tin website</a></li>
                            <li><a href="<?php echo site_url("setting/seo");?>">Seo code</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo site_url("setting/mail_smtp")?>">STMP Mail</a></li>
                        </ul>
                    </li>
                </ul> -->

                <div class="right clearfix">
                    <ul class="nav navbar-nav pull-right right-navbar-nav">
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle user-menu" data-toggle="dropdown">
                                <span><?php echo $this->session->data["fullname"]?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo site_url("acinfo")?>"><i class="fa fa-key"></i>&nbsp;&nbsp;Đổi mật khẩu</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo site_url("home/logout")?>"><i class="dropdown-icon fa fa-power-off"></i>&nbsp;&nbsp;Thoát</a></li>
                            </ul>
                        </li>
                    </ul> <!-- / .navbar-nav -->
                </div> <!-- / .right -->
            </div>
        </div> <!-- / #main-navbar-collapse -->
    </div> <!-- / .navbar-inner -->
</div> 