<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package NQD-Store-Smart
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function nqd_store_smart_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
	) );
}
add_action( 'after_setup_theme', 'nqd_store_smart_jetpack_setup' );
