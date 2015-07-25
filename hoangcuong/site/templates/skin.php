<?php
if($_SESSION['user_id'] == ''){
    redirect();
}
?>
<!DOCTYPE html>
<!--

TABLE OF CONTENTS.

Use search to find needed section.

=====================================================

|  1. $BODY                 |  Body                 |
|  2. $MAIN_NAVIGATION      |  Main navigation      |
|  3. $NAVBAR_ICON_BUTTONS  |  Navbar Icon Buttons  |
|  4. $MAIN_MENU            |  Main menu            |
|  5. $SEARCH_RESULTS_PAGE  |  Search results page  |

=====================================================

-->


<!--[if IE 8]>         <html class="ie8"> <![endif]-->
<!--[if IE 9]>         <html class="ie9 gt-ie8"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="gt-ie8 gt-ie9 not-ie"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Quản trị hệ thống</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

        <!-- Open Sans font from Google CDN -->
        

        <!-- Pixel Admin's stylesheets -->
        <link href="<?php echo base_url() ?>templates/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url() ?>templates/css/pixel-admin.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url() ?>templates/css/widgets.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url() ?>templates/css/pages.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url() ?>templates/css/rtl.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url() ?>templates/css/themes.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url() ?>templates/css/alert.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url() ?>templates/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <script type="text/javascript">
            var base_url = '<?php echo base_url()?>';
        </script>
        <!--[if lt IE 9]>
                <script src="assets/javascripts/ie.min.js"></script>
        <![endif]-->

    </head>


    <!-- 1. $BODY ======================================================================================
            
            Body
    
            Classes:
            * 'theme-{THEME NAME}'
            * 'right-to-left'      - Sets text direction to right-to-left
            * 'main-menu-right'    - Places the main menu on the right side
            * 'no-main-menu'       - Hides the main menu
            * 'main-navbar-fixed'  - Fixes the main navigation
            * 'main-menu-fixed'    - Fixes the main menu
            * 'main-menu-animated' - Animate main menu
    -->
    <body class="theme-default main-menu-animated page-search">

        <script>var init = [];</script>
        <script src="<?php echo base_url() ?>templates/js/demo.js"></script>
        <script src="<?php echo base_url() ?>templates/js/jquery-2.1.1.min.js"></script>

        <div id="main-wrapper">


            <!-- 2. $MAIN_NAVIGATION ===========================================================================
            Main navigation
            -->
            <?php $this->_templates("html/menubar"); ?>
            <!-- / #main-navbar -->
            <!-- /2. $END_MAIN_NAVIGATION -->


            <!-- 4. $MAIN_MENU =================================================================================-->
            <?php $this->_templates("html/col_left"); ?>
            <!-- /4. $MAIN_MENU -->


            <div id="content-wrapper">
                <div id="page-title">
                    <h1 class="page-header text-overflow"><?php echo $title;?></h1>
                    <div class="pull-right">
                        <?php $this->_templates("html/toolbar");?>
                    </div>
                </div>
                <?php $this->_templates("html/breadcrumb");?>
                <?php if(isset($message) && $message !=''){ echo '<div class="alert alert-info alert-dark" id="msg"><button data-dismiss="alert" class="close"><span>×</span></button>'.$message.'</div>';}?>
                <?php if($this->session->get_flashdata('message')){
                    echo '<div class="alert alert-success alert-dark" id="msg"><button data-dismiss="alert" class="close"><span>×</span></button>'.$this->session->get_flashdata('message').'</div>';
                }if($this->session->get_flashdata('error')){
                    echo '<div class="alert alert-danger alert-dark" id="msg"><button data-dismiss="alert" class="close"><span>×</span></button>'.$this->session->get_flashdata('error').'</div>';
                }if($this->session->get_flashdata('notes')){
                    echo '<div class="alert alert-dark" id="msg"><button data-dismiss="alert" class="close"><span>×</span></button>'.$this->session->get_flashdata('notes').'</div>';
                }
                ?>
                <?php $this->load->view($page);?>
            </div> <!-- / #content-wrapper -->
            <div id="main-menu-bg"></div>
        </div> <!-- / #main-wrapper -->

        <!-- Pixel Admin's javascripts -->
        
        <script src="<?php echo base_url() ?>templates/js/alert.js"></script>
        <script src="<?php echo base_url() ?>templates/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url() ?>templates/js/pixel-admin.min.js"></script>
        <script src="<?php echo base_url() ?>templates/js/dashboard.js"></script>

        <script type="text/javascript">
            init.push(function () {
                $('.add-tooltip').tooltip();
            });
            window.PixelAdmin.start(init);
        </script>
    </body>
</html>
<?=$this->session->unset_flashdata(array('message','error','alert','notes'))?>