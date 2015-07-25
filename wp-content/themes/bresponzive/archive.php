<?php get_header(); ?>		
<?php global $data;?>
<div id="blocks-wrapper" class="clearfix">
	<div id="blocks-left" class="eleven columns clearfix">	
 			<!--Archive content-->
		<!-- .blogposts-wrapper-->
  
 					 		<h2 class="blogpost-wrapper-title" style="margin-top:30px;">
 							<?php if(is_day()): ?>
 								<?php printf(__('Daily Archives: %s', 'themepacific'), get_the_date()); ?>
 							<?php elseif(is_month()) : ?>
 								<?php printf(__('Monthly Archives: %s', 'themepacific'), get_the_date('F Y')); ?>
 							<?php elseif(is_year()) : ?>
 								<?php printf(__('Yearly Archives: %s', 'themepacific'), get_the_date('Y')); ?>
 							<?php elseif(is_category() || is_tag()): ?>
 								<?php printf(__('Articles Posted in the " %s " Category', 'themepacific'), single_cat_title('', false)); ?>
 							<?php elseif(is_author()):  	?>	
 								<?php printf(__('Articles Posted by the Author: %s', 'themepacific'), $curauth->nickname);  ?>
 							<?php else: ?>
 								<?php _e('Blog Archives', 'themepacific'); ?>
 							<?php endif; ?>
  						</h2> 
						<?php include_once('includes/blog_loop.php');?>
  			
 	</div>
 			<!-- END MAIN -->
 <?php get_sidebar(); ?>
 <?php get_footer(); ?>