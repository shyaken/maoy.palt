<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package NQD Store
 
 Template Name: Top Star
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
							$page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
							$total = countPostIDFilterStar();
							// Calculate Paging
							$numposts = $total;
							$perpage = 20;
							
							$max_page = ceil($numposts / $perpage);
							if(empty($page) || $page == 0) {
								$page = 1;
							}
							$offset = ($page-1) * $perpage;
							$pages_to_show = 10;
							$pages_to_show_minus_1 = $pages_to_show-1;
							$half_page_start = floor($pages_to_show_minus_1/2);
							$half_page_end = ceil($pages_to_show_minus_1/2);
							$start_page = $page - $half_page_start;
							if($start_page <= 0) {
								$start_page = 1;
							}
							$end_page = $page + $half_page_end;
							if(($end_page - $start_page) != $pages_to_show_minus_1) {
								$end_page = $start_page + $pages_to_show_minus_1;
							}
							if($end_page > $max_page) {
								$start_page = $max_page - $pages_to_show_minus_1;
								$end_page = $max_page;
							}
							if($start_page <= 0) {
								$start_page = 1;
							}
							if(($offset + $perpage) > $numposts) {
								$max_on_page = $numposts;
							} else {
								$max_on_page = ($offset + $perpage);
							}
							if (($offset + 1) > ($numposts)) {
								$display_on_page = $numposts;
							} else {
								$display_on_page = ($offset + 1);
							}
							// Download Paging
							if($max_page > 1) {
								if(function_exists('wp_pagenavi')) {
									$output .= '<div class="wp-pagenavi">'."\n";
								} else {
									$output .= '<div class="nqd-store-paging">'."\n";
								}
								$output .= '<span class="pages">&#8201;'.sprintf(__('Page %s of %s', 'nqd-store'), number_format_i18n($page), number_format_i18n($max_page)).'&#8201;</span>';
								if ($start_page >= 2 && $pages_to_show < $max_page) {
									$output .= '<a href="'.download_page_link(1).'" title="'.__('&laquo; First', 'nqd-store').'">&#8201;'.__('&laquo; First', 'nqd-store').'&#8201;</a>';
									$output .= '<span class="extend">...</span>';
								}
								if($page > 1) {
									$output .= '<a href="'.download_page_link(($page-1)).'" title="'.__('&laquo;', 'nqd-store').'">&#8201;'.__('&laquo;', 'nqd-store').'&#8201;</a>';
								}
								for($i = $start_page; $i  <= $end_page; $i++) {
									if($i == $page) {
										$output .= '<span class="current">&#8201;'.number_format_i18n($i).'&#8201;</span>';
									} else {
										$output .= '<a href="'.download_page_link($i).'" title="'.number_format_i18n($i).'">&#8201;'.number_format_i18n($i).'&#8201;</a>';
									}
								}
								if(empty($page) || ($page+1) <= $max_page) {
									$output .= '<a href="'.download_page_link(($page+1)).'" title="'.__('&raquo;', 'nqd-store').'">&#8201;'.__('&raquo;', 'nqd-store').'&#8201;</a>';
								}
								if ($end_page < $max_page) {
									$output .= '<span class="extend">...</span>';
									$output .= '<a href="'.download_page_link($max_page).'" title="'.__('Last &raquo;', 'nqd-store').'">&#8201;'.__('Last &raquo;', 'nqd-store').'&#8201;</a>';
								}
								$output .= '</div>';
							}
							$post_ID = getPostIDFilterStar($offset,$perpage);					
						?>
						<?php 
							foreach($post_ID as $post_item):
							global $post; 
							$post = get_post($post_item->post_id);				
							setup_postdata($post); 
						?>
						<?php
							get_template_part( 'content', get_post_format() );
						?>
						<?php endforeach;
							echo $output;
							wp_reset_postdata();
						?>
						<div class="clearfix"></div>
					</div>
		<?php else : ?>
			<?php get_template_part( 'no-results', 'archive' ); ?>
		<?php endif; ?>
		</main><!-- #main -->
		</div>
<?php get_footer(); ?>
