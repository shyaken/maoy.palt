<?php

/**

 * The template for displaying Archive pages.

 *

 * Learn more: http://codex.wordpress.org/Template_Hierarchy

 *

 * @package NQD Store

 

 Template Name: Danh Muc

 */



get_header(); ?>

<div class="nav">

<h3><a href="http://etcandroid.com" title="Download free APK for android"> Download free APK for android </a></h3>

</div>

	<div id="site-main-content" class="col-lg-12">

		<main id="site-content" class="col-lg-9" role="main">	

			

	<?php

		$args = array(

			'theme_location' => '',

			'depth'		 => 0,

			'container'	 => false,

			'menu_class'	 => 'nav',

			'before'          => '',

			'after'           => '',

			'link_before' =>'',

			'link_after' =>'',

			'items_wrap' => '<div class="list-group">%3$s</div>',

			'walker'	 => new BootstrapSidebarMenuWalker()

		);

		wp_nav_menu($args);

	?>

		</main><!-- #main -->

		</div>

<?php get_footer(); ?>

