<?php

define('ROOT', '../');
//define('HOST','localhost');
//define('USER','root');
//define('PASS','admin');
//define('DBNAME','2015_etc');

define('HOST','localhost');
define('USER','root');
define('PASS','');
define('DBNAME','admin_masterapk');

include "inc/db.php";
include "inc/lib.php";
include "inc/simple_html_dom.php";
include "inc/function_string.php";
include("inc/resize-class.php");
$lib = new lib();
$db = new db();
$sql = "SELECT ID, file, post_name FROM wp_posts as p, wp_downloads as d  WHERE p.ID = d.post_id AND is_update = 0 ORDER BY ID DESC";
$item_row = $db->row($sql);
$link = $item_row->file;
$itemdatas = _curl($link);
$itemhtml = str_get_html($itemdatas);
$title = $item_row->post_name;
$root_year = ROOT . '../wp-content/uploads/' . date("Y") . '/';
if (!is_dir($root_year)) {
    mkdir($root_year, 777);
}
$root_month = ROOT . '../wp-content/uploads/' . date("Y") . '/' . date("m") . '/';
$dir_root = date("Y") . '/' . date("m") . '/';
if (!is_dir($root_month)) {
    mkdir($root_month, 777);
}

$find_list_img = $itemhtml->find("img[class=screenshot]");
if (count($find_list_img) > 3) {
    $total_img = 3;
} else {
    $total_img = count($find_list_img);
}
for ($j = 0; $j <= $total_img; $j++) {
    $item_img = $find_list_img[$j]->src;
    $image_data = file_get_contents($item_img); // Get image data
    $filename = preg_replace('/[^a-z0-9]/ui', '-', $title); // Create image file name
    if (strpos($filename, ".png") === false && strpos($filename, ".jpg") === false) {
        $filename .= "-" . $j . ".png";
    }
    file_put_contents($root_month . $filename, $image_data);
    $_infomation_link_image[] = 'http://etcandroid.com/wp-content/uploads/' . $dir_root . $filename;
}

if (isset($_infomation_link_image)) {
    //$imgdata['post_id'] = $post_id;
    //$imgdata['meta_key'] = "_infomation_link_image";
    $imgdata['meta_value'] = serialize($_infomation_link_image);
    $db->update("wp_postmeta", $imgdata,array("post_id"=>$item_row->ID, 'meta_key'=>"_infomation_link_image"));
    unset($_infomation_link_image);
}
$vupdate["is_update"] = 1;
$db->update("wp_posts",$vupdate,array("ID"=>$item_row->ID));
echo $item_row->ID;