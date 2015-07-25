<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package NQD Store
 
 Template Name: Latest
 */

get_header(); ?>
	<div id="site-main-content" class="col-lg-12">
		<?php get_sidebar(); ?>
		<main id="site-content" class="col-lg-9" role="main">	
		<div id="site-breadcrumbs">
			<?php NQD_breadcrumbs();?>
		</div>
		<?php if ( have_posts() ) : ?>
				<div class="list-app">
						<?php 
						$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
						$args1 = array( 'posts_per_page' => $posts_number_latest,
						'post_status' => 'publish',
						'paged' => $paged,);
						$my_query = new WP_Query($args1);	
						while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
						<?php
							get_template_part( 'content', get_post_format() );
						?>
						<?php endwhile;?>
	
						<div class="clearfix"></div>
					</div>
		<?php else : ?>
			<?php get_template_part( 'no-results', 'archive' ); ?>
		<?php endif; ?>
		</main><!-- #main -->
		</div>
<?php get_footer(); ?>
