<?php
// If this file is called directly, busted!
if( !defined( 'ABSPATH' ) ) {
  exit;
}
/*----------------------------------------------------------------------------------------------------------
  HEADER TEMPLATE
-----------------------------------------------------------------------------------------------------------*/
?>
<!doctype html>
<!--[if !IE]>
<html class="no-js non-ie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>
<html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>
<html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>
<html class="no-js ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php wp_title(' - ', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11"/>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>
<?php

// facebook fix for wrong thumbnail image when using facebook share button
if ( is_single() ) {
	global $post;
	if ( has_post_thumbnail( $post->ID ) ) {
		$gameleon_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		if ( !empty( $gameleon_image[0] ) ) {
			echo '<meta property="og:image" content="' .  $gameleon_image[0] . '" />';
		}
	}
}

wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="container">
<?php
locate_template('parts/top-menu.php', true ); // yep, load the top menu
?>

<div id="header">
<?php
if ( gameleon_get_option( 'td_overall_display' ) == 1 or is_front_page() ) : ?>
<?php
global $smof_data;
$td_header_manager = $smof_data['td_header_blocks']['enabled'];

if ( $td_header_manager ) {

	foreach ( $td_header_manager as $key=>$value ) {

		switch( $key ) {

				case 'block_logo_ad': // logo + ad
				echo '<div class="header-inner">';
				locate_template('parts/header-boxed-logo-ad.php', true );
				echo '</div>';
				break;

				case 'block_fullwidth_logo': // full logo
				locate_template( 'parts/header-boxed-full-logo.php', true );
				break;

				case 'block_ad_banner': // ad only
				echo '<div class="header-inner-ad-only">';
				locate_template( 'parts/header-boxed-ad-only.php', true );
				echo '</div>';
				break;

				case 'block_main_menu': // main menu
				locate_template('parts/header-menu.php', true );
				break;

				case 'block_full_header_slider': // full slider
				locate_template( 'parts/header-boxed-slider.php', true );
				break;

				case 'block_modular_slider': // modular slider
				locate_template( 'parts/modular-slider.php', true );
				break;

				case 'block_news_ticker': // main menu
				locate_template('parts/news-ticker.php', true );
				break;

			}
		}

	}
?>


<?php
elseif ( gameleon_get_option( 'td_logo_overall_display' ) == 1 or is_front_page() ) : ?>
<div class="header-inner">
<?php
locate_template('parts/header-boxed-full-logo.php', true );
?>
</div>
<?php
locate_template('parts/header-menu.php', true );
?>


<?php else: ?>
<div class="header-inner">
<?php
locate_template('parts/header-boxed-logo-ad.php', true );
?>
</div>
<?php
locate_template('parts/header-menu.php', true );
?>
<?php endif; ?>
</div><?php // end of #header ?>


<div id="wrapper-content"><?php // end of #wrapper-content ?>
<?php gameleon_header_bottom(); // after header content hook ?>
<div class="clearfix"></div>
<?php

$tenapp = get_post_meta( get_the_ID(), '_infomation_name',true );

$appver = get_post_meta( get_the_ID(), '_infomation_version',true );

$appmanu = get_post_meta( get_the_ID(), '_infomation_manufacturers',true );

?>
<div id="content" class="grid col-700">

<div class="td-content-inner-single">
<div class="widget-title">

<?php if ( defined( 'MYARCADE_VERSION' ) and is_game() ) : ?>

<h3><?php _e( 'Game Details', 'gameleon' ); ?></h3>

<?php else: ?>



<?php endif; // end of check "MYARCADE_VERSION" ?>


</div>


<div class="td-wrap-content">

<div class="post-entry">
<br/>
			<div class="post-icon">
				<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID)); ?>
				<img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>"  />
			</div>
			<div class="post-title">
				<h1 class="entry-title">Free Download <?php echo $tenapp2;?> V <?php echo  $appver; ?> APK for Android  </h1>
				<p style="padding:6px 0;"><?php $childcategory ="";
	foreach((get_the_category($post->ID)) as $category) {
	if ($category->category_parent == !0) {
	$childcategory .= ' <a href="' . get_category_link($category->cat_ID) . '" title="' . $category->name . '">' . $category->name . '</a>, ';
	}
	}echo substr($childcategory,0,-2); ?> by <?php echo $appmanu2;?><br/>Updated: <?php the_modified_date('F j, Y'); ?></p>
			</div>
			<br/>

	
	<p>
	<p>You are downloading the <?php echo $tenapp2;?> V <?php echo  $appver; ?> file for Android:
	</p>
 
					
					
						<a style="font-size:15px;color:#12A1F0;" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php _e('Read More', 'bresponZive'); ?></a>

<p style="padding:10px 0;">Please be aware that Etc.Android only share the original and free apk installer for <?php echo $tenapp2;?> V <?php echo  $appver; ?>  without any cheat, unlimited gold patch or any other modifications.</p>
	<p>All the apps &amp; games here are for home or personal use only. If any apk download infringes your copyright, please <a rel="nofollow" class="readmore" href="/contact-us/">contact us</a>, We'll delete it any way.</p>
					

	<div class="clear"></div>
	<h3 class="app-title">Download File for <?php the_title(); ?></h3>
	<?php
		foreach($files as $file):
				$file_id = $file->file_id;
				$post_id = $file->post_id;
				$file_name = $file->file_name;
				$file_url = $file->file;
				$file_des = $file->file_des;
				$file_size = formatBytes($file->file_size);
				$file_hits = $file->file_hits;
				$file_date = $file->file_date;
				$file_updated_date = $file->file_updated_date;
				$file_last_downloaded_date=$file->file_last_downloaded_date;
				$file_views = $file->file_views;
			
			$link_name = sanitize_title($file_name);
			$mylink =home_url('/') .'download/'.$file_id.'/'.$link_name;
			if(!isset($_SESSION['file_views'][$file_id])){
				$_SESSION['file_views'][$file_id]=$file_views+1;
				updateFile(array('file_views'=>$file_views+1),array('file_id'=>$file_id));			
			}
	?>
	
	<div style="float:left;width:336px;padding:15px 15px 10px 0;">
	<p style="margin:8px 0 0 10px;">
	• <a style="font-size:15px;color: #06AFE4;" href="<?=$mylink ?>" title="<?=$file_des;?>" >>> Download From Google Play <<</a></p>
	<?php endforeach;?>
	
	</div>

</div><?php // end of .post-entry / ?>
</div><?php // end of td-wrap-content / ?>
</div>



<?php get_sidebar(); ?>
<?php get_footer(); ?>