<?php
/**
 * Plugin Name: Game Crawler Plugin
 * Plugin URI: http://masterapk.com/wp-admin/postgame.php // Địa chỉ trang chủ của plugin
 * Description: This is plugin for automatically crawl a game to our website
 * Version: 1.0 // Đây là phiên bản đầu tiên của plugin
 * Author: shyaken  // Tên tác giả, người thực hiện plugin này
 * Author URI: http://www.facebook.com/alleria.ken // Địa chỉ trang chủ của tác giả
 * License: GPLv2 or later // Thông tin license của plugin, nếu không quan tâm thì bạn cứ để GPLv2 vào đây
 */
/** Step 2 (from text above). */
add_action( 'admin_menu', 'my_plugin_menu' );

/** Step 1. */
function my_plugin_menu() {
	add_menu_page( 'Game Crawler Setting', 'Game Crawler', 'manage_options', 'postgame.php' );
}

/** Step 3. */
function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<p>Here is where the form would go if I actually had options.</p>';
	echo '</div>';
}
?>