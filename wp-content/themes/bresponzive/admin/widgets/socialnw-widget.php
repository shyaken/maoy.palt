<?php
/*************************************************************************************
	Plugin Name: Social Network Icons Widget
	Description: It will display Social Nw Icons.
	Author: ThemePacific
	Author URI: http://themepacific.com					
***************************************************************************/
/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'tpcrn_social_widget_box' );
/**
 * Register our widget.
 * 'Example_Widget' is the widget class used below.
 * 
 * @since 0.1
 */
function tpcrn_social_widget_box() {
	register_widget( 'tpcrn_social_widget' );
}
/**
 * Example Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class tpcrn_social_widget extends WP_Widget {

	function tpcrn_social_widget() {
		$widget_ops = array( 'classname' => 'tpcrn-social-icons-widget', 'description' => 'Display Social Icons' );
		$control_ops = array($control_ops = array('id_base' => 'tpcrn_social_icons-widget'));
		$this->WP_Widget( 'social','ThemePacific: Social Icons', $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );		 		
		$fb = $instance['fb'];		 		
		$gp = $instance['gp'];		 		
		$rss = $instance['rss'];		 		
		$tw = $instance['tw'];		
/* Before widget (defined by themes). */
		echo $before_widget;
		if($title)
			echo $before_title . $title . $after_title;
		/* Display the widget title if one was input (before and after defined by themes). */ 		
  ?>
  
			<div class="widget">
			<div class="social-icons">
		<?php
		$icons_path =  get_stylesheet_directory_uri().'/images/social-icons';
		 $rss = get_bloginfo('rss2_url'); 
			?><a   title="Rss" href="<?php echo $rss ; ?>" ><img src="<?php echo $icons_path; ?>/rss.png" alt="RSS"  /></a> 
		 
		 <a  title="Google+" href="<?php echo $gp; ?>" ><img src="<?php echo $icons_path; ?>/gp.png" alt="Google+"  /></a> 
		 <a  title="Facebook" href="<?php echo $fb; ?>" ><img src="<?php echo $icons_path; ?>/fb.png" alt="Facebook"  /></a> 
		 
		 <a  title="Twitter" href="<?php echo $tw ; ?>" ><img src="<?php echo $icons_path; ?>/tw.png" alt="Twitter"  /></a> 
			</div>
			</div>
		 		<?php	
	/* After widget (defined by themes). */
		echo $after_widget;		
	
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['rss'] = strip_tags( $new_instance['rss'] );
		$instance['fb'] = strip_tags( $new_instance['fb'] );
		$instance['gp'] = strip_tags( $new_instance['gp'] );
		$instance['tw'] = strip_tags( $new_instance['tw'] );
 		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' =>__('Social' , 'themepacific') , 'rss' =>__('' , 'themepacific') , 'fb' =>__('' , 'themepacific') , 'gp' =>__('' , 'themepacific') , 'tw' =>__('' , 'themepacific')   );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title : </label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'rss' ); ?>">RSS </label>
			<input id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" value="<?php echo $instance['rss']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'fb' ); ?>">Facebook : </label>
			<input id="<?php echo $this->get_field_id( 'fb' ); ?>" name="<?php echo $this->get_field_name( 'fb' ); ?>" value="<?php echo $instance['fb']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'gp' ); ?>">Google+ : </label>
			<input id="<?php echo $this->get_field_id( 'gp' ); ?>" name="<?php echo $this->get_field_name( 'gp' ); ?>" value="<?php echo $instance['gp']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'tw' ); ?>">Twitter : </label>
			<input id="<?php echo $this->get_field_id( 'tw' ); ?>" name="<?php echo $this->get_field_name( 'tw' ); ?>" value="<?php echo $instance['tw']; ?>" class="widefat" type="text" />
		</p>

 
 
		


	<?php
	}
}
?>