<?php get_header(); ?>
<!-- #blocks-wrapper-->

<div id="blocks-wrapper" class="clearfix">
    <?php if (have_posts()) : while (have_posts()) : the_post();  ?> 
	<?php

$tenapp = get_post_meta( get_the_ID(), '_infomation_name',true );

$appver = get_post_meta( get_the_ID(), '_infomation_version',true );

$appmanu = get_post_meta( get_the_ID(), '_infomation_manufacturers',true );

?>

	<!-- /blocks Left -or -right -->
	<div id="blocks-left" <?php post_class('eleven columns');?>>	 		
		
		<!-- .post-content-->
		<div class="post-content">
  				<?php 
			  if($data['posts_bread'] == 'On' ) {
			  if (function_exists('themepacific_breadcrumb')) themepacific_breadcrumb(); 
			  }
			 ?>						
		<!--/.post-outer -->
			<div class="post-outer clearfix">
			
 				<!--.post-title-->
 				  <div class="post-title"><h1 class="entry-title"><?php the_title(); ?></h1></div>
				  <!--/.post-title-->
 		<!--/#post-meta --> 
			<div class="post-meta-blog">
			<span class="meta_author"><?php _e('Posted by', 'themepacific'); ?> <?php the_author_posts_link(); ?></span>
			<span class="meta_date"><?php _e('On', 'themepacific'); ?> <?php the_time('F d, Y'); ?></span>
			<span class="meta_comments"><?php _e('', 'themepacific'); ?>  <a href="<?php comments_link(); ?>"><?php comments_number('0 Comment', '1 Comment', '% Comments'); ?></a></span>
 			<?php edit_post_link( __( 'Edit', 'themepacific' ), '<span class="edit-link">', '</span>' ); ?>
 			</div>
			<!--/#post-meta --> 
			 <!-- .post_content -->
			  <div class = 'post_content entry-content'>
  					<?php the_content(); ?>
  					<div class="clear"></div>
 			 </div>	
			 <!-- /.post_content -->
					<?php wp_link_pages(); ?>
   					<div class='clear'></div>
					<p class="post-tags">
						<strong>TOPICS </strong><?php the_tags('',''); ?>					
						</p>
			</div>
		<!--/.post-outer -->
 
		</div>
		<!-- post-content-->
<h2 class="app-title"><i>Download <?php the_title(); ?> files</i></h2>
<div style="float:left;height:150px;width:150px;padding:15px 20px 10px 0;">
	<a title="Download <?php the_title();?>"><img src="<?php echo $image[0]; ?>" width="150" height="150" alt="<?php the_title();?>" /></a>
</div>
<div style="padding:15px 0 10px 0;">
	<p>Download <?php the_title(); ?> V <?php echo  $appver; ?> :</p>
	<p style="margin:15px 0;" class="no_translate">
	<?php 
					$files = getFileByPostID(get_the_ID());
					if( !empty( $files) ){ 

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
						echo'
<a href="http://masterapk.com/getapk.php?url='.$file_url.'" title="<?=$file_des;?>" rel="nofollow" class="download"> <strong> <font color="0066FF">&bull; Direct Download APK from masterapk</font> </strong> </a>
<br/>
<a href="'.$mylink.'" rel="nofollow" class="download"> <strong><font color="0066FF">&bull;  Get it from Google play </font></strong> </a>
';
						endforeach;
						}
						else{
						echo'
						<a href="'.$mylink.'" rel="nofollow">DOWNLOAD HERE</a>';
						}
	?>
	
 </p>
	<ul>
		<li>&bull; Price: Download apk free with masterapk </b></li>
		<li>&bull; Version: <?php echo  $appver; ?></li>
		<li>&bull; Manufacture: <?php echo $appmanu; ?></li>
		<li>&bull; File Name: <?php the_title(); ?> APK</li>
	</ul>
</div>
<h2 class="app-title"><?php the_title(); ?> APK Screenshots</h2> <br/>

		

		

<div class="hinhanh">



<?php  $key_image_values = get_post_meta( get_the_ID(), '_infomation_link_image',false );

if(is_array($key_image_values[0])): ?>

<?php foreach($key_image_values[0] as $value): 

?>	

<img src="<?php echo $value;?>" alt="<?php the_title();?>" />	

<?php endforeach; ?>

<?php endif;?>

</div>

 
			 <?php if($data['posts_navigation'] == 'On'){ ?>
		 		<!-- .single-navigation-->
				<div class="single-navigation clearfix">
					<div class="previous"><?php previous_post_link('%link', ' <span>  Previous:</span> %title'); ?></div>
					<div class="next"><?php next_post_link('%link', ' <span>Next:  </span> %title ' ); ?></div>
					 
				</div>
				<!-- /single-navigation-->
			<?php } ?>
 
   					<?php comments_template(); ?>
 				<?php endwhile; endif; ?>
			
			</div>
			<!-- /blocks Left-->
 			
<?php  get_sidebar(); ?>
			
<?php get_footer(); ?>