<?php
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
global $wpdb;
$wpdb->downloads = $wpdb->prefix.'downloads';
	// Create WP-Downloads Table
$create_table = "CREATE TABLE $wpdb->downloads (".
							"file_id int(10) NOT NULL auto_increment,".
							"post_id int(10) NOT NULL,".
							"file tinytext NOT NULL,".
							"jad_file tinytext NOT NULL,".
							"file_name text character set utf8 NOT NULL,".
							"file_des text character set utf8 NOT NULL,".
							"file_size varchar(20) NOT NULL default '',".
							"file_date varchar(20) NOT NULL default '',".
							"file_updated_date varchar(20) NOT NULL default '',".
							"file_last_downloaded_date varchar(20) NOT NULL default '',".
							"file_hits int(10) NOT NULL default '0',".
							"file_views int(10) NOT NULL default '0',".
							"PRIMARY KEY (file_id)) $charset_collate;";
dbDelta( $create_table );

function insertFile($data,$format = null){
	global $wpdb;
	$wpdb->insert( $wpdb->downloads, $data, $format );
}

function updateFile( $data, $where, $format = null, $where_format = null){
	global $wpdb;
	$wpdb->update( $wpdb->downloads, $data, $where, $format, $where_format );
}

function deleteFile($where, $where_format = null ){
	global $wpdb;
	$wpdb->delete( $wpdb->downloads, $where, $where_format );
}

function getFileByPostID($post_id){
	global $wpdb;
	$files = $wpdb->get_results( 
		"SELECT *
		FROM $wpdb->downloads
		WHERE post_id = '$post_id'"
	);
	return $files;
}
function getFileSizeByPostID($post_id){
	global $wpdb;
	$files = $wpdb->get_results( 
		"SELECT file_size
		FROM $wpdb->downloads
		WHERE post_id = '$post_id'"
	);
	return $files[0]->file_size;
}
function getFileByFileID($file_id){
	global $wpdb; 
	$file = $wpdb->get_results( 
		"
		SELECT *
		FROM $wpdb->downloads
		WHERE file_id = '$file_id' 
		"
	);
	return $file;	
}
function getLinkByFileID($file_id){
	global $wpdb; 
	$link = $wpdb->get_var($wpdb->prepare("select file from $wpdb->downloads WHERE file_id = '".$file_id."' "));
	return $link;
}
function getPostIDFilterFileHits($offset=0, $file_perpage=20){
	global $wpdb; 
	$link = $wpdb->get_results("select distinct post_id from $wpdb->downloads d INNER JOIN $wpdb->posts p ON d.post_id = p.ID Where p.post_status = 'publish' ORDER BY file_hits DESC LIMIT $offset,$file_perpage");
	return $link;
}
function countPostIDFilterFileHits(){
	global $wpdb; 
	$link = $wpdb->get_var("select count(post_id) countFile from $wpdb->downloads d INNER JOIN $wpdb->posts p ON d.post_id = p.ID Where p.post_status = 'publish'  ORDER BY file_hits DESC");
	return $link;
}
function getPostIDFilterStar($offset=0, $file_perpage=20){
	global $wpdb; 
	$link = $wpdb->get_results("SELECT p.ID post_id, visitor_votes + user_votes as total_votes, visitor_votes, user_votes  FROM  `".$wpdb->prefix."gdsr_data_article` da INNER JOIN $wpdb->posts p ON da.post_id = p.ID WHERE p.post_status = 'publish' and p.post_type='post' order by total_votes desc limit $offset, $file_perpage");
	return $link;
}
function countPostIDFilterStar(){
	global $wpdb; 
	$link = $wpdb->get_var($wpdb->prepare("SELECT count(p.ID) countFile  FROM  `".$wpdb->prefix."gdsr_data_article` da INNER JOIN $wpdb->posts p ON da.post_id = p.ID WHERE p.post_status = 'publish' and p.post_type='post'"));
	return $link;
}