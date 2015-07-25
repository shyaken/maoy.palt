<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package NQD-Store-Smart
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function wp_get_postcount($id) {
  //return count of post in category child of ID 15
  $count = 0;
  $taxonomy = 'category';
  $args = array('child_of' => $id);
  $tax_terms = get_terms($taxonomy,$args);
  foreach ($tax_terms as $tax_term) {
    $count +=$tax_term->count;
  }
  return $count;
}

class BootstrapSidebarMenuWalker extends Walker_Nav_Menu {
	function start_lvl(&$output, $depth) {
		$indent = str_repeat ( "\t", $depth );
		$submenu = ($depth > 0) ? ' sub-menu' : '';
	}
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$indent = ($depth) ? str_repeat ( "\t", $depth ) : '';
		
		$li_attributes = '';
		$class_names = $value = '';
		
		$classes = empty ( $item->classes ) ? array () : ( array ) $item->classes;
		
		// managing divider: add divider class to an element to get a divider
		// before it.
		$id_cate = $id;
		$class_names = join ( ' ', apply_filters ( 'nav_menu_css_class', array_filter ( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr ( $class_names ) . '"';
		
		$id = apply_filters ( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
		$id = strlen ( $id ) ? ' id="' . esc_attr ( $id ) . '"' : '';
		$attributes = ! empty ( $item->attr_title ) ? ' title="' . esc_attr ( $item->attr_title ) . '"' : '';
		$attributes .= ! empty ( $item->target ) ? ' target="' . esc_attr ( $item->target ) . '"' : '';
		$attributes .= ! empty ( $item->xfn ) ? ' rel="' . esc_attr ( $item->xfn ) . '"' : '';
		$attributes .= ! empty ( $item->url ) ? ' href="' . esc_attr ( $item->url ) . '"' : '';
		$attributes .= ' class="list-group-item"';
		
		//$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$category = get_category($item->object_id);
		$count = $category->category_count;
		
		$item_output .= ($args->has_children) ?'<span class="badge bg-warning">'.wp_get_postcount($item->object_id).'</span>':'<span class="badge bg-info">'.$count.'</span>';
		$item_output .= ($args->has_children) ? '<span class="text-bold">'.$args->link_before . apply_filters ( 'the_title', $item->title, $item->ID ) . $args->link_after .'</span>': $args->link_before . apply_filters ( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		//$item_output .= $args->after;
		
		$output .= apply_filters ( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	function end_el( &$output, $element, $depth, $args ) {
		$output.='';
	}
	function end_lvl( &$output, $depth, $args ) {
		$output.='';
	}
	function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
		// v($element);
		if (! $element)
			return;
		
		$id_field = $this->db_fields ['id'];
		
		// display this element
		if (is_array ( $args [0] ))
			$args [0] ['has_children'] = ! empty ( $children_elements [$element->$id_field] );
		else if (is_object ( $args [0] ))
			$args [0]->has_children = ! empty ( $children_elements [$element->$id_field] );
		$cb_args = array_merge ( array (
				&$output,
				$element,
				$depth 
		), $args );
		call_user_func_array ( array (
				&$this,
				'start_el' 
		), $cb_args );
		$id = $element->$id_field;
		
		// descend only when the depth is right and there are childrens for this
		// element
		if (($max_depth == 0 || $max_depth > $depth + 1) && isset ( $children_elements [$id] )) {
			
			foreach ( $children_elements [$id] as $child ) {
				
				if (! isset ( $newlevel )) {
					$newlevel = true;
					// start the child delimiter
					$cb_args = array_merge ( array (
							&$output,
							$depth 
					), $args );
					call_user_func_array ( array (
							&$this,
							'start_lvl' 
					), $cb_args );
				}
				$this->display_element ( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}
			unset ( $children_elements [$id] );
		}
		
		if (isset ( $newlevel ) && $newlevel) {
			// end the child delimiter
			$cb_args = array_merge ( array (
					&$output,
					$depth 
			), $args );
			call_user_func_array ( array (
					&$this,
					'end_lvl' 
			), $cb_args );
		}
		
		// end this element
		$cb_args = array_merge ( array (
				&$output,
				$element,
				$depth 
		), $args );
		call_user_func_array ( array (
				&$this,
				'end_el' 
		), $cb_args );
	}
}
function nqd_store_smart_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'nqd_store_smart_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 */
function nqd_store_smart_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'nqd_store_smart_body_classes' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 */
function nqd_store_smart_enhanced_image_navigation( $url, $id ) {
	if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
		return $url;

	$image = get_post( $id );
	if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
		$url .= '#main';

	return $url;
}
add_filter( 'attachment_link', 'nqd_store_smart_enhanced_image_navigation', 10, 2 );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 */
function nqd_store_smart_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'nqd-store-smart' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'nqd_store_smart_wp_title', 10, 2 );

function catch_that_image() {
  global $post, $posts;
  $first_img = '';
  $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') );
  if(empty($url)){
	  ob_start();
	  ob_end_clean();
	  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	  $first_img = $matches[1][0];
	  if(empty($first_img)) {
			$first_img = find_img_src($post);
		if(empty($first_img)) {
			$first_img = get_template_directory_uri()."/images/no-image.jpg";
		}
	  }
  }else{
	$first_img = $url;
  }
  return remove_http($first_img);
}
function catch_that_image_id($post) {
  $first_img = '';
  $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') );
  if(empty($url)){
	  ob_start();
	  ob_end_clean();
	  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	  $first_img = $matches[1][0];
	  if(empty($first_img)) {
			$first_img = find_img_src($post);
		if(empty($first_img)) {
			$first_img = get_template_directory_uri()."/images/no-image.jpg";
		}
	  }
  }else{
	$first_img = $url;
  }
  return remove_http($first_img);
}
function gpi_find_image_id($post_id) {
    if (!$img_id = get_post_thumbnail_id ($post_id)) {
        $attachments = get_children(array(
            'post_parent' => $post_id,
            'post_type' => 'attachment',
            'numberposts' => 1,
            'post_mime_type' => 'image'
        ));
        if (is_array($attachments)) foreach ($attachments as $a)
            $img_id = $a->ID;
    }
    if ($img_id)
        return $img_id;
    return false;
}
function find_img_src($post) {
    if (!$img = gpi_find_image_id($post->ID))
        if ($img = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches))
            $img = $matches[1][0];
    if (is_int($img)) {
        $img = wp_get_attachment_image_src($img);
        $img = $img[0];
    }
    return $img;
}
 // check mobile browser
 function remove_http($input = '')
{
	$input = trim($input, '/');

	// If scheme not included, prepend it
	if (!preg_match('#^http(s)?://#', $input)) {
		$input = 'http://' . $input;
	}

	$urlParts = parse_url($input);

	// remove www
	$domain = preg_replace('/^www\./', '', $urlParts['host']);
	$domain.=$urlParts['path'];
	$domain.=$urlParts['query'];
	return $domain;
}
function pagination($pages = '', $range = 2) {
	$showitems = ($range * 2) + 1;
	
	global $paged;
	if (empty ( $paged ))
		$paged = 1;
	
	if ($pages == '') {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if (! $pages) {
			$pages = 1;
		}
	}
	if (1 != $pages) {
		echo "<ul class='pagination'>";
		if ($paged > 2 && $paged > $range + 1 && $showitems < $pages)
			echo "<li><a href='" . get_pagenum_link ( 1 ) . "'>&laquo;</a></li>";
		if ($paged > 1 && $showitems < $pages)
			echo "<li><a href='" . get_pagenum_link ( $paged - 1 ) . "'>&lsaquo;</a></li>";
		
		for($i = 1; $i <= $pages; $i ++) {
			if (1 != $pages && (! ($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
				echo ($paged == $i) ? "<li class='active'><span class='current'>" . $i . "</span></li>" : "<li><a href='" . get_pagenum_link ( $i ) . "' class='inactive' >" . $i . "</a></li>";
			}
		}
		
		if ($paged < $pages && $showitems < $pages)
			echo "<li><a href='" . get_pagenum_link ( $paged + 1 ) . "'>&rsaquo;</a></li>";
		if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
			echo "<li><a href='" . get_pagenum_link ( $pages ) . "'>&raquo;</a></li>";
		echo "</ul>\n";
	}
}
function NQD_breadcrumbs() {
	  /* === OPTIONS === */  
    $text['home']     = ''; // text for the 'Home' link  
    $text['category'] = '%s'; // text for a category page  
    $text['search']   = 'Kết quả tìm kiếm cho từ khóa "%s"'; // text for a search results page  
    $text['tag']      = 'Tag "%s"'; // text for a tag page  
    $text['author']   = 'Bài viết bởi %s'; // text for an author page  
    $text['404']      = 'Error 404'; // text for the 404 page  
  
    $showCurrent = 0; // 1 - show current post/page title in breadcrumbs, 0 - don't show  
    $showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show  
    $delimiter   = ''; //&raquo; '; delimiter between crumbs  
    $before      = '<span class="current">'; // tag before the current crumb  
    $after       = '</span>'; // tag after the current crumb  
    /* === END OF OPTIONS === */  
  
    global $post;  
    $homeLink = get_bloginfo('url') . '/';  
    $linkBefore = '';  
    $linkAfter = '';  
    $linkAttr = '';  
    $link = '<a href="%1$s">%2$s</a>';  
  
    if (is_home() || is_front_page()) {  
  
        if ($showOnHome == 1) echo '<div id="crumbs" class="breadcrumb"><a href="' . $homeLink . '">' . $text['home'] . '</a></div>';  
  
    } else {  
  
        echo '<div class="breadcrumb" id="crumbs" xmlns:v="http://rdf.data-vocabulary.org/#">' . sprintf($link, $homeLink, $text['home']) . $delimiter;  
  
        if ( is_category() ) {  
            $thisCat = get_category(get_query_var('cat'), true);  
            if ($thisCat->parent != 0) {  
                $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);  
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);  
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);  
                echo $cats;  
            }  
            echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;  
  
        } elseif ( is_search() ) {  
            echo $before . sprintf($text['search'], get_search_query()) . $after;  
  
        } elseif ( is_day() ) {  
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;  
            echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;  
            echo $before . get_the_time('d') . $after;  
  
        } elseif ( is_month() ) {  
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;  
            echo $before . get_the_time('F') . $after;  
  
        } elseif ( is_year() ) {  
            echo $before . get_the_time('Y') . $after;  
  
        } elseif ( is_single() && !is_attachment() ) {  
            if ( get_post_type() != 'post' ) {  
                $post_type = get_post_type_object(get_post_type());  
                $slug = $post_type->rewrite;  
                printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);  
                if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;  
            } else {  
                $cat = get_the_category(); $cat = $cat[0];  
                $cats = get_category_parents($cat, TRUE, $delimiter);  
                if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);  
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);  
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);  
                echo $cats;  
                if ($showCurrent == 1) echo $before . get_the_title() . $after;  
            }  
  
        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {  
            $post_type = get_post_type_object(get_post_type());  
            echo $before . $post_type->labels->singular_name . $after;  
  
        } elseif ( is_attachment() ) {  
            $parent = get_post($post->post_parent);  
            $cat = get_the_category($parent->ID); $cat = $cat[0];  
            $cats = get_category_parents($cat, TRUE, $delimiter);  
            $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);  
            $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);  
            echo $cats;  
            printf($link, get_permalink($parent), $parent->post_title);  
            if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;  
  
        } elseif ( is_page() && !$post->post_parent ) {  
           echo $before . get_the_title() . $after;  
  
        } elseif ( is_page() && $post->post_parent ) {  
            $parent_id  = $post->post_parent;  
            $breadcrumbs = array();  
            while ($parent_id) {  
                $page = get_page($parent_id);  
                $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));  
                $parent_id  = $page->post_parent;  
            }  
            $breadcrumbs = array_reverse($breadcrumbs);  
            for ($i = 0; $i < count($breadcrumbs); $i++) {  
                echo $breadcrumbs[$i];  
                if ($i != count($breadcrumbs)-1) echo $delimiter;  
            }  
            if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;  
  
        } elseif ( is_tag() ) {  
            echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;  
  
        } elseif ( is_author() ) {  
            global $author;  
            $userdata = get_userdata($author);  
            echo $before . sprintf($text['author'], $userdata->display_name) . $after;  
  
        } elseif ( is_404() ) {  
            echo $before . $text['404'] . $after;  
        }  
  
        if ( get_query_var('paged') ) {  
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';  
            echo __('Page') . ' ' . get_query_var('paged');  
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';  
        }
		if(is_page('manufacturers')){
			echo '<span typeof="v:Breadcrumb"><a href="'.home_url().'/manufacturers/" property="v:title" rel="v:url">Nhà sản xuất</a></span><span typeof="v:Breadcrumb"><a href="'.home_url().'/manufacturers/'.get_query_var('manufacturers_name').'" property="v:title" rel="v:url">'.urldecode(get_query_var('manufacturers_name')).'</a></span>';
		}
		if(is_page('detail')){
			echo '<span typeof="v:Breadcrumb"><a href="'.home_url().'/detail/" property="v:title" rel="v:url">Chi tiết</a></span><span typeof="v:Breadcrumb"><a href="'.get_permalink(get_query_var('post_id')).'" property="v:title" rel="v:url">'.get_the_title(get_query_var('post_id')).'</a></span>';
		}
        echo '</div>';  
  
    }  
}
function time_stamp($time_ago) {
	$cur_time = time ();
	$time_elapsed = $cur_time - $time_ago;
	$seconds = $time_elapsed;
	$minutes = round ( $time_elapsed / 60 );
	$hours = round ( $time_elapsed / 3600 );
	$days = round ( $time_elapsed / 86400 );
	$weeks = round ( $time_elapsed / 604800 );
	$months = round ( $time_elapsed / 2600640 );
	$years = round ( $time_elapsed / 31207680 );
	// Seconds
	if ($seconds <= 60) {
		return " Cách đây $seconds giây ";
	} 	// Minutes
	else if ($minutes <= 60) {
		if ($minutes == 1) {
			return " Cách đây 1 phút ";
		} else {
			return " Cách đây $minutes phút";
		}
	} 	// Hours
	else if ($hours <= 24) {
		if ($hours == 1) {
			return "Cách đây 1 tiếng ";
		} else {
			return " Cách đây  $hours tiếng ";
		}
	} 	// Days
	else if ($days <= 7) {
		if ($days == 1) {
			return " Ngày hôm qua ";
		} else {
			return " Cách đây  $days ngày ";
		}
	} 	// Weeks
	else if ($weeks <= 4.3) {
		if ($weeks == 1) {
			return " Cách đây 1 tuần ";
		} else {
			return " Cách đây  $weeks tuần";
		}
	} 	// Months
	else if ($months <= 12) {
			return date('d/m/Y',$time_ago);
	} 	// Years
	else {
		return date('d/m/Y',$time_ago);
	}
}
//end time_stamp
//rewrite url download

add_filter('query_vars', 'parameter_queryvars' );
function parameter_queryvars( $qvars )
{
	$qvars[] = 'pid';
	return $qvars;
}
//meta query var
add_filter('query_vars', 'parameter_queryvars1' );
function parameter_queryvars1( $qvars )
{
	$qvars[] = 'file_id';
	return $qvars;
}
add_filter('query_vars', 'parameter_queryvars2' );
function parameter_queryvars2( $qvars )
{
	$qvars[] = 'file_name';
	return $qvars;
}
add_action( 'init', 'urldlRewrite_init' );
function urldlRewrite_init()
{
    add_rewrite_rule(
        'download(/([^/]+))?(/([^/]+))?(/([^/]+))?/?',
        'index.php?pagename=download&file_id=$matches[2]&file_name=$matches[4]&pid=$matches[6]','top'
    );
}
//rewrite url download

add_filter('query_vars', 'parameter_queryvar_manufacturers' );
function parameter_queryvar_manufacturers( $qvars )
{
	$qvars[] = 'manufacturers_name';
	return $qvars;
}
//meta query var
add_action( 'init', 'url_manufacturers_Rewrite_init' );
function url_manufacturers_Rewrite_init()
{
    add_rewrite_rule(
        'manufacturers(/([^/]+))?/?',
        'index.php?pagename=manufacturers&manufacturers_name=$matches[2]','top'
    );
}

add_filter('query_vars', 'parameter_queryvar_download_detail' );
function parameter_queryvar_download_detail( $qvars )
{
	$qvars[] = 'post_id';
	return $qvars;
}
//meta query var
add_action( 'init', 'url_download_detail_Rewrite_init' );
function url_download_detail_Rewrite_init()
{
    add_rewrite_rule(
        'detail(/([^/]+))?/?',
        'index.php?pagename=detail&post_id=$matches[2]','top'
    );
}
// Add the Meta Box
function add_infomation_meta_box() {
    add_meta_box(
		'infomation_meta_box', // $id
		'Infomation', // $title 
		'show_infomation_meta_box', // $callback
		'post', // $page
		'normal', // $context
		'high'); // $priority
}
add_action('add_meta_boxes', 'add_infomation_meta_box');

// Field Array
$prefix = '_infomation_';
$custom_meta_fields = array(
	array(  
        'label'=> 'Disable',  
        'desc'  => 'Disable infomation box.',  
        'id'    => $prefix.'disable',  
        'type'  => 'checkbox'  
    ) ,
	array(  
        'label'=> 'Name',  
        'desc'  => 'Tên game.',  
        'id'    => $prefix.'name',  
        'type'  => 'text'  
    ) ,
	array(  
        'label'=> 'Version',  
        'desc'  => 'Phiên bản.',  
        'id'    => $prefix.'version',  
        'type'  => 'text'  
    ) ,
	array(  
        'label'=> 'Manufacturers',  
        'desc'  => 'Nhà sản xuất.',  
        'id'    => $prefix.'manufacturers',  
        'type'  => 'text'  
    ) ,
	array(  
		'label'  => 'Image',  
		'desc'  => 'Slider image.',  
		'id'    => $prefix.'link_image',  
		'type'  => 'repeatable'  
	) ,
	array(  
		'label'  => 'Add File download',  
		'desc'  => 'Link download.',  
		'id'    => $prefix.'download',  
		'type'  => 'download'  
	) 
);

function show_infomation_meta_box() {
	global $custom_meta_fields, $post;
	// Use nonce for verification
	echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
	
	// Begin the field table and loop
	echo '<table class="form-table">';
	foreach ($custom_meta_fields as $field) {
		// get value of this field if it exists for this post
		$meta = get_post_meta($post->ID, $field['id'], true);
		// begin a table row with
		echo '<tr>
				<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
				<td>';
				switch($field['type']) {
					// text
						case 'text':
							echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
									<br /><span class="description">'.$field['desc'].'</span>';
						break;
						// textarea
						case 'textarea':
							echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
									<br /><span class="description">'.$field['desc'].'</span>';
						break;
						// checkbox
						case 'checkbox':
							echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
									<label for="'.$field['id'].'">'.$field['desc'].'</label>';
						break;
						// select
						case 'select':
							echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
							foreach ($field['options'] as $option) {
								echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
							}
							echo '</select><br /><span class="description">'.$field['desc'].'</span>';
						break;
						// radio
						case 'radio':
							foreach ( $field['options'] as $option ) {
								echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
										<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
							}
							echo '<span class="description">'.$field['desc'].'</span>';
						break;
						// checkbox_group
						case 'checkbox_group':
							foreach ($field['options'] as $option) {
								echo '<input type="checkbox" value="'.$option['value'].'" name="'.$field['id'].'[]" id="'.$option['value'].'"',$meta && in_array($option['value'], $meta) ? ' checked="checked"' : '',' /> 
										<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
							}
							echo '<span class="description">'.$field['desc'].'</span>';
						break;
						// tax_select
						case 'tax_select':
							echo '<select name="'.$field['id'].'" id="'.$field['id'].'">
									<option value="">Select One</option>'; // Select One
							$terms = get_terms($field['id'], 'get=all');
							$selected = wp_get_object_terms($post->ID, $field['id']);
							foreach ($terms as $term) {
								if (!empty($selected) && !strcmp($term->slug, $selected[0]->slug)) 
									echo '<option value="'.$term->slug.'" selected="selected">'.$term->name.'</option>'; 
								else
									echo '<option value="'.$term->slug.'">'.$term->name.'</option>'; 
							}
							$taxonomy = get_taxonomy($field['id']);
							echo '</select><br /><span class="description"><a href="'.get_bloginfo('home').'/wp-admin/edit-tags.php?taxonomy='.$field['id'].'">Manage '.$taxonomy->label.'</a></span>';
						break;
						// post_list
						case 'post_list':
						$items = get_posts( array (
							'post_type'	=> $field['post_type'],
							'posts_per_page' => -1
						));
							echo '<select name="'.$field['id'].'" id="'.$field['id'].'">
									<option value="">Select One</option>'; // Select One
								foreach($items as $item) {
									echo '<option value="'.$item->ID.'"',$meta == $item->ID ? ' selected="selected"' : '','>'.$item->post_type.': '.$item->post_title.'</option>';
								} // end foreach
							echo '</select><br /><span class="description">'.$field['desc'].'</span>';
						break;
						// date
						case 'date':
							echo '<input type="text" class="datepicker" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
									<br /><span class="description">'.$field['desc'].'</span>';
						break;
						// slider
						case 'slider':
						$value = $meta != '' ? $meta : '0';
							echo '<div id="'.$field['id'].'-slider"></div>
									<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$value.'" size="5" />
									<br /><span class="description">'.$field['desc'].'</span>';
						break;
						// image
						case 'image':
							$image = get_template_directory_uri().'/images/image.png';	
							echo '<span class="custom_default_image" style="display:none">'.$image.'</span>';
							if ($meta) { $image = wp_get_attachment_image_src($meta, 'medium');	$image = $image[0]; }				
							echo	'<input name="'.$field['id'].'" type="hidden" class="custom_upload_image" value="'.$meta.'" />
										<img src="'.$image.'" class="custom_preview_image" alt="" /><br />
											<input class="custom_upload_image_button button" type="button" value="Choose Image" />
											<small>&nbsp;<a href="#" class="custom_clear_image_button">Remove Image</a></small>
											<br clear="all" /><span class="description">'.$field['desc'].'</span>';
						break;
						// repeatable
						case 'repeatable':
							echo '<a class="repeatable-add button" href="#">+</a>
									<ul id="'.$field['id'].'-repeatable" class="custom_repeatable">';
							$i = 0;
							
							if ($meta) {
								foreach($meta as $row) {
									echo '<li><span class="sort hndle">|||</span>
												<input type="text" name="'.$field['id'].'['.$i.']" id="'.$field['id'].'" value="'.$row.'" size="30" />
												<a class="img-repeatable-remove button" href="#">-</a></li>';
									$i++;
								}
							} else {
								echo '<li><span class="sort hndle">|||</span>
											<input type="text" name="'.$field['id'].'['.$i.']" id="'.$field['id'].'" value="" size="30" />
											<a class="img-repeatable-remove button" href="#">-</a></li>';
							}
							echo '</ul>
								<span class="description">'.$field['desc'].'</span>';
						break;
						// repeatable
						case 'download':
							echo '<a class="repeatable-add button" href="#">+</a>
									<ul id="'.$field['id'].'-repeatable" class="custom_repeatable">';
							$i = 0;
							$files =getFileByPostID($post->ID);
							if ($files) {
								foreach($files as $row) {
									$row= (array )$row;
									echo '<li><table>
												<tr>
													<th></th>
													<td><input type="hidden" name="'.$field['id'].'_file_id_'.'[]" id="'.$field['id'].'" value="'.$row['file_id'].'" size="50" /></td>
													<td></td>
												</tr>
												<tr>
													<th>File Name :</th>
													<td><input type="text" name="'.$field['id'].'_file_name_'.'[]" id="'.$field['id'].'" value="'.$row['file_name'].'" size="50" /></td>
													<td><a class="repeatable-remove button" href="#">-</a></td>
												</tr>
												<tr>
													<th>File Link :</th>
													<td><input type="text" name="'.$field['id'].'_file_'.'[]" id="'.$field['id'].'" value="'.$row['file'].'" size="50" /></td>
													<td></td>
												</tr>
												
												<tr>
													<th>File Description :</th>
													<td><textarea name="'.$field['id'].'_des_'.'[]" cols="50" rows="5">'.$row['file_des'].'</textarea></td>
													<td></td>
												</tr>
												</table>
												</li>';
									$i++;
								}
							} else {
								echo '<li><table>
												<tr>
													<th></th>
													<td><input type="hidden" name="'.$field['id'].'_file_id_'.'[]" id="'.$field['id'].'" value="" size="50" /></td>
													<td></td>
												</tr>
												<tr>
													<th>File Name :</th>
													<td><input type="text" name="'.$field['id'].'_file_name_'.'[]" id="'.$field['id'].'" value="" size="50" /></td>
													<td><a class="repeatable-remove button" href="#">-</a></td>
												</tr>
												<tr>
													<th>File Link :</th>
													<td><input type="text" name="'.$field['id'].'_file_'.'[]" id="'.$field['id'].'" value="" size="50" /></td>
													<td></td>
												</tr>
												<tr>
													<th>File Description :</th>
													<td><textarea name="'.$field['id'].'_des_'.'[]" cols="50" rows="5"></textarea></td>
													<td></td>
												</tr>
												</table>
												</li>';
							}
							echo '</ul>
								<span class="description">'.$field['desc'].'</span>';
						break;
				} //end switch
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}
// Save the Data
function save_custom_meta($post_id) {
    global $custom_meta_fields,$post;
	// verify nonce
	if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__))) 
		return $post_id;
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $post_id;
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id))
			return $post_id;
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
	}
	$files =getFileByPostID($post->ID);
	// loop through fields and save the data
	foreach ($custom_meta_fields as $field) {
		if($field['type'] == 'tax_select') continue;
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
		if($field['type'] == 'download'){
				$gID = $_POST['_infomation_download_file_id_'];
				$files= (array )$files;
				foreach($files as $file){
					$file= (array )$file;
					if(!in_array($file['file_id'],$gID)){
						deleteFile(array('file_id'=>$file['file_id']));
					}
				}
				
				$gFileName = $_POST['_infomation_download_file_name_'];
				$gFileLink = $_POST['_infomation_download_file_'];
				$gFileDes = $_POST['_infomation_download_des_'];
				
				for($i = 0 ;$i < count($gID);$i++){
					if(!empty($gFileName[$i]) && !empty($gFileLink[$i])){
						if(empty($gID[$i])){
							$data = array(
							'post_id' => $post_id,
							'file' => $gFileLink[$i],
							'jad_file' => " ",	
							'file_name' => $gFileName[$i],
							'file_des' => $gFileDes[$i],
							'file_size' => remote_filesize($gFileLink[$i]),
							'file_date' => date("d-m-Y H:i:s"),
							'file_updated_date' => date("d-m-Y H:i:s"),
							'file_last_downloaded_date' => date("d-m-Y H:i:s")
							);
							insertFile($data);
						}else{
						$data = array(
							'post_id' => $post_id,
							'file' => $gFileLink[$i],
							'jad_file' => " ",		
							'file_name' => $gFileName[$i],
							'file_des' => $gFileDes[$i],				
							'file_size' => remote_filesize($gFileLink[$i]),
							'file_updated_date' => date("d-m-Y H:i:s")
							);
							$where = array('file_id' => $gID[$i]);
							updateFile($data,$where);
						}
					}
				}
				

		}
	} // enf foreach
}
add_action('save_post', 'save_custom_meta');
// function to get current filename from a url made by sarfraz ahmed.
### Function: Get Remote File Size
if(!function_exists('remote_filesize')) {
	function remote_filesize($uri) {
	$chGetSize = curl_init();
 
	// Set the url we're requesting
	curl_setopt($chGetSize, CURLOPT_URL, $uri);
	 
	// Set a valid user agent
	curl_setopt($chGetSize, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.11) Gecko/20071127 Firefox/2.0.0.11");
	 
	// Don't output any response directly to the browser
	curl_setopt($chGetSize, CURLOPT_RETURNTRANSFER, true);
	 
	// Don't return the header (we'll use curl_getinfo();
	curl_setopt($chGetSize, CURLOPT_HEADER, false);
	 
	// Don't download the body content
	curl_setopt($chGetSize, CURLOPT_NOBODY, true);
	 
	// Run the curl functions to process the request
	$chGetSizeStore = curl_exec($chGetSize);
	$chGetSizeError = curl_error($chGetSize);
	$chGetSizeInfo = curl_getinfo($chGetSize);
	 
	// Close the connection
	curl_close($chGetSize);// Print the file size in bytes
	 
	return $chGetSizeInfo['download_content_length'];
	}
}

//get remote file name
if(!function_exists('remote_filename')) {
	function remote_filename($uri) {
		$header_array = @get_headers($uri, 1);
		$file_name = $header_array['Content-Disposition'];
		if(!empty($file_name)) {
		
            $tmp_name = explode('=', $file_name);
						
            if ($tmp_name[1]) 
			{
				$file_name =str_replace('"','',$tmp_name[1]);				
				return $file_name;
			}
		} else {
			return __('unknown', 'wp-downloadmanager');
		}
	}
}
add_action('wp_ajax_download_action' , 'ajax_link_download'); // When user login
add_action('wp_ajax_nopriv_download_action' , 'ajax_link_download'); // When user not loggin
function ajax_link_download() {
 
    // The $_REQUEST contains all the data sent via ajax
    if ( isset($_POST) ) {
        $post_id = $_POST['post_id'];
		echo json_encode(getFileByPostID($post_id));
    }
   die();
}
add_filter( 'wp_title', 'filter_wp_title');
function filter_wp_title( $title ) {
	global $manufacturers_name;
	
	if ( is_page('manufacturers') )
		return urldecode($manufacturers_name).' - '.$title;
	if ( is_page('detail') ){
		global $download_post_name;
		return urldecode($download_post_name).' - '.$title;
	}
	if ( is_home() )
		return $title.' - Tải ứng dụng android | Tải game android';
	return $title;
}
function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
     $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
}
//expand link from bit.ly
function expandBitly($url){
	// get host name from URL
	preg_match('@^(?:http://)?([^/]+)@i',$url, $matches);
	$host = $matches[1];
	// get last two segments of host name
	preg_match('/[^.]+\.[^.]+$/', $host, $matches);
	if($matches[0] == 'bit.ly')
	{

		$bitly  = new Bitly('o_19kpvsso1r','R_bb9f3f8d2aa62705fdc5750866f11176');
		$url= $bitly->expand($url);
	}
	return $url;
}
function paging($para,$query) {  
 //global $wp_query;
 $total = $query->max_num_pages;

 // Only paginate if we have more than one page
 if ( $total > 1 )  {
     // Get the current page
     if ( !$current_page = get_query_var($para) )
          $current_page = 1;
     // Structure of “format” depends on whether we’re using pretty permalinks
    //$permalinks = get_option('permalink_structure');
    $format = "?$para=%#%" ;//empty( $permalinks ) ? "&$para=%#%" : "$para/%#%/";
	$permalinks = get_option('permalink_structure');empty( $permalinks ) ? "&$para=%#%" : "/$para/%#%/";
	//$big =999999999;
	//$bs = str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) );
    echo paginate_links(array(
          'base' => @add_query_arg("$para",'%#%'),//get_pagenum_link( $current_page ) . '%_%',
          'format' => $format,
          'current' => max( 1, $_GET[$para] ),//$current_page,
          'total' => $total,
		  //'add_args' => array( "$para" => $para ),
          'mid_size' => 2,
		'prev_text' => '«',  
		'next_text' => '»'  
    ));
}
}
if( !is_admin()){
	wp_deregister_script('jquery');
	wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"), false, '1.7.2');
	wp_enqueue_script('jquery');
}
function developer_taxonomy()  {

	$labels = array(
		'name'                       => _x( 'Developers', 'Taxonomy General Name', 'nqd-store' ),
		'singular_name'              => _x( 'Developer', 'Taxonomy Singular Name', 'nqd-store' ),
		'menu_name'                  => __( 'Developer', 'nqd-store' ),
		'all_items'                  => __( 'All Developers', 'nqd-store' ),
		'parent_item'                => __( 'Parent Developer', 'nqd-store' ),
		'parent_item_colon'          => __( 'Parent Developer:', 'nqd-store' ),
		'new_item_name'              => __( 'New Developer Name', 'nqd-store' ),
		'add_new_item'               => __( 'Add New Developer', 'nqd-store' ),
		'edit_item'                  => __( 'Edit Developer', 'nqd-store' ),
		'update_item'                => __( 'Update Developer', 'nqd-store' ),
		'separate_items_with_commas' => __( 'Separate developers with commas', 'nqd-store' ),
		'search_items'               => __( 'Search developers', 'nqd-store' ),
		'add_or_remove_items'        => __( 'Add or remove developers', 'nqd-store' ),
		'choose_from_most_used'      => __( 'Choose from the most used developers', 'nqd-store' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'developer', 'post', $args );

}

// Hook into the 'init' action
add_action( 'init', 'developer_taxonomy', 0 );

function download_page_link($page) {
	$current_url = $_SERVER['REQUEST_URI'];
	$curren_downloadpage = intval($_GET['paged']);
	$download_page_link = preg_replace('/paged=(\d+)/i', 'paged='.$page, $current_url);
	if($curren_downloadpage == 0) {
		if(strpos($current_url, '?') !== false) {
			$download_page_link = "$download_page_link&amp;paged=$page";
		} else {
			$download_page_link = "$download_page_link?paged=$page";
		}
	}
	return $download_page_link;
}