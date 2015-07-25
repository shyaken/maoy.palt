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
//require_once( ROOT.'../wp-admin/admin.php' );
include "inc/db.php";
include "inc/lib.php";
include "inc/simple_html_dom.php";
include "inc/function_string.php";
$db = new db();
$sql = "SELECT ID, file FROM wp_posts as p, wp_downloads as d WHERE p.ID = d.post_id ORDER BY ID DESC";
$list = $db->result($sql);
foreach($list as $rs):
    $id_com = getCom($rs->file);
    $vdata['id_com'] = $id_com;
    $db->update("wp_posts",$vdata,array("ID"=>$rs->ID));
    unset($vdata);
endforeach;