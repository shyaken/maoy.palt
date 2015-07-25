<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>	
<!-- Meta info -->
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<!-- Title -->
<?php global $data;?>
<title>
	<?php 	global $page, $paged;	
	wp_title( '|', true, 'right' ); 
		bloginfo( 'name' ); 	
		$site_description = get_bloginfo( 'description', 'display' );	
		if ( $site_description && ( is_home() || is_front_page() ) )	
		echo " | $site_description"; 	
		if ( $paged >= 2 || $page >= 2 )echo ' | ' . sprintf( __( 'Page %s', 'themepacific' ), max( $paged, $page ) );?>
</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Arimo' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Open+sans' rel='stylesheet' type='text/css'>
<?php if($data['custom_feedburner']) : ?>
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php echo $data['custom_feedburner']; ?>" />
<?php endif; ?>
<?php if($data['custom_favicon']): ?>
<link rel="shortcut icon" href="<?php echo $data['custom_favicon']; ?>" /> <?php endif;  ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!-- CSS + jQuery + JavaScript --> 
<?php 	
if ( is_singular() && get_option( 'thread_comments' ) )		wp_enqueue_script( 'comment-reply' );  
	wp_head();
?>
<!--[if lt IE 9]> 
<link rel='stylesheet' href='<?php echo get_template_directory_uri(); ?>/css/ie8.css' type='text/css' media='all' />
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script> 
<script type="text/javascript" src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<!--[if  IE 9]>
<link rel='stylesheet' href='<?php echo get_template_directory_uri(); ?>/css/ie9.css' type='text/css' media='all' /> 
<![endif]-->
 
</head>

<body <?php body_class();?>> 

<!-- #wrapper -->	
<div id="wrapper" class="container clearfix"> 
   <?php 
     if ( has_nav_menu('topNav') ){ 
   ?>
	<!-- #CatNav -->  
	<div id="catnav">	
		<?php wp_nav_menu(array('theme_location' => 'topNav','container'=> '','menu_id'=> 'catmenu','menu_class'=> ' container clearfix','fallback_cb' => 'false','depth' => 3)); ?>
	</div> 
	<!-- /#CatNav -->  
	<?php } ?> 
<!-- /#Header --> 
<div id="wrapper-container"> 

<div id="header">	
	<div id="head-content" class="clearfix ">
 	 
			<!-- Logo --> 
			<div id="logo">   
				<?php if($data['custom_logo'] !='') { 
				if($data['custom_logo']) {  $logo = $data['custom_logo']; 		
				} else { $logo = get_template_directory_uri() . '/images/logo.png'; 	
				} ?>  <a href="<?php echo home_url(); ?>/" title="<?php bloginfo( 'name' ); ?>" rel="home"><img src="<?php echo $logo; ?>" alt="<?php bloginfo( 'name' ) ?>" /></a>    
				<?php } else { ?>   
				<?php if (is_home()) { ?>     
				<h1><a href="<?php echo home_url(); ?>/" title="<?php bloginfo( 'name' ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1> <span><?php bloginfo( 'description' ); ?></span>
				<?php } else { ?>  
				<h2><a href="<?php echo home_url(); ?>/" title="<?php bloginfo( 'name' ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>  
				<?php } } ?>   
			</div>	 	
			<!-- /#Logo -->
 		
		<!-- Header Ad -->
 		<div id="header-banner468" class="clearfix">
					<img src=<?php echo get_template_directory_uri(); ?>/images/728x900-tpcrn.png> 
			</div>
	 	
		<!-- /#Header Ad -->
	</div>	
 </div>
<!-- /#Header --> 

   <?php 
     if ( has_nav_menu('mainNav') ){ 
   ?>
	<!-- #CatNav -->  
	<div id="catnav" class="secondary">	
		<?php wp_nav_menu(array('theme_location' => 'mainNav','container'=> '','menu_id'=> 'catmenu','menu_class'=> 'catnav  container clearfix','fallback_cb' => 'false','depth' => 3)); ?>
	</div> 
	<!-- /#CatNav -->  
	<?php } ?> 
 
	<!--[if lt IE 8]>
		<div class="msgnote"> 
			Your browser is <em>too old!</em> <a rel="nofollow" href="http://browsehappy.com/">Upgrade to a different browser</a> to experience this site. 
		</div>
	<![endif]-->	