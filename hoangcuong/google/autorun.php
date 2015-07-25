<?php

define('ROOT', '../');
define('ROOT_TAM', '');
define('HOST','localhost');
define('USER','root');
define('PASS','');
define('DBNAME','admin_masterapk');


//define('USER','root');
//define('PASS','admin');
//define('DBNAME','2015_etc');

//
//require_once( ROOT.'../wp-admin/admin.php' );
include "inc/db.php";
include "inc/lib.php";
include "inc/simple_html_dom.php";
include "inc/function_string.php";
include("inc/resize-class.php");
include("inc/libimg.php");
include("inc/img.php");
$img = new img();
$lib = new lib();
$db = new db();
$catid = (int) $_GET['term_id'];

$ar_cat = $lib->ar_cat_string($catid);

$total_no_active1 = $lib->get_num_link_no_active($ar_cat);
$total_no_active = $total_no_active1;
$total_active = $lib->get_num_link_active($ar_cat);
$total_link = $total_active + $total_no_active;
$phantram = round(($total_active * 100 ) / $total_link, 1);
$_SESSION['total'] = $total_link;
$_SESSION['da_thuc_hien'] = $total_active;
$_SESSION['chua_thuc_hien'] = $total_no_active;
$_SESSION['con_lai'] = $total_link - $total_active;
$_SESSION['phan_tram'] = $phantram;
$data['chua_thuc_hien'] = $total_no_active;
$data['phantram'] = $phantram;
$random = rand(20, 1000);
if ($total_no_active1 == 0) {
    $lib->ac_active();
} else {
    $row = $lib->get_row($ar_cat);
    //die($row->link_google);
    //var_dump($row);
    //$url = $row->link_google;

    $post_google["start"] = $random;
    $post_google["num"] = 5;
    $post_google["numChildren"] = 0;
    $post_google["ipf"] = 1;
    $post_google["xhr"] = 1;
    //$post["token"] = "3GOZfa2gaq0e6Fga54trwHTzeXQ:1428335379649";
    $url = $row->link_google . '?authuser=0';
    //$data['url'] = $url;
    $data['ids'] = $row->ids;
    $data_content = postPage($url, $post_google);
    //$data_content = _curl($url);
    $html = str_get_html($data_content);
    $items = $html->find("div[class=details] h2 a[class=title]");
    for ($i = 0; $i < 5; $i++) {
        $content = '';
        $item = $items[$i];
        //$title = $item->plaintext;
        //$slug = vnit_change_title($title);
        $link = $item->href;
        $link = strpos($link, 'https://') !== false ? $link : 'https://play.google.com' . $link;
        $id_com = getCom($link);
        $itemdatas = _curl($link);
        $itemhtml = str_get_html($itemdatas);
        $title = $itemhtml->find("div[class=document-title]")[0]->plaintext;
        $slug = vnit_change_title($title);
        $images = $itemhtml->find("div[class=cover-container] img[0]")[0]->src;
        $find_list_img = $itemhtml->find("img[class=screenshot]");
        // Check title
        $check_alias = $lib->check_com($id_com);
        if ($check_alias == 0) {

            for ($j = 0; $j < count($find_list_img); $j++) {
                $item_img = $find_list_img[$j]->src;
            }
            $version = $itemhtml->find('div[class=meta-info] div[itemprop="softwareVersion"]')[0]->plaintext;
            $manufacture = $itemhtml->find('a[class="document-subtitle primary"] span')[0]->plaintext;
            $post_meta['_infomation_name'] = $title;
            $post_meta['_infomation_version'] = $version;
            $post_meta['_infomation_manufacturers'] = $manufacture;
            $post_meta['snap_isAutoPosted'] = 1;
            $post_meta['_internal_link_exist'] = "yes";

            // Lay thong tin bai viet
            $content_ = $itemhtml->find("div[class=show-more-content]");
            $content .= $content_[0]->innertext;

            if ($images != "") {
                $post = array(
                    'post_title' => $title,
                    'post_content' => $content,
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_date' => date("Y-m-d H:i:s"),
                    'post_date_gmt' => date("Y-m-d H:i:s"),
                    'post_name' => vnit_change_title($title),
                    'post_modified' => date("Y-m-d H:i:s"),
                    'post_modified_gmt' => date("Y-m-d H:i:s"),
                    'id_com' => $id_com,
                    'guid' => 'http://masterapk.com/dungml/' . vnit_change_title($title) . '/'
                );
                $db->insert("wp_posts", $post);
                $post_id = $db->insert_id();

                // Tang so luong category
                $db->query("UPDATE wp_term_taxonomy SET count = count + 1 WHERE term_id = " . $row->term_id);


                // Insert wp_term
                $term['name'] = $title;
                $term['slug'] = vnit_change_title($title);
                $db->insert("wp_terms", $term);
                $term_id = $db->insert_id();

                // insert wp_term_taxonomy

                $term_taxo['term_id'] = $term_id;
                $term_taxo['taxonomy'] = 'post_tag';
                $term_taxo['count'] = '0';
                $db->insert("wp_term_taxonomy", $term_taxo);
                $term_taxonomy_id = $db->insert_id();
                //insert wp_term_relationships
                $term_re['object_id'] = $post_id;
                $term_re['term_taxonomy_id'] = $term_taxonomy_id;
                $db->insert("wp_term_relationships", $term_re);

                //insert wp_term_relationships  // Add Category
                $term_re_cat['object_id'] = $post_id;
                $term_re_cat['term_taxonomy_id'] = $row->term_id;
                $db->insert("wp_term_relationships", $term_re_cat);

                // Insert wp_postmeta
                // _infomation_name; _infomation_version ;  _infomation_manufacturers;
                foreach ($post_meta as $key => $val) {
                    $postmeta["post_id"] = $post_id;
                    $postmeta["meta_key"] = $key;
                    $postmeta["meta_value"] = $val;
                    $db->insert("wp_postmeta", $postmeta);
                    unset($postmeta);
                }

                // Insert wp_downloads
                $download['post_id'] = $post_id;
                $download['file'] = $link;
                $download['file_name'] = $title;
                $download['file_des'] = $title;
                $download['file_updated_date'] = date("Y-m-d H:i:s");
                $download['file_last_downloaded_date'] = date("Y-m-d H:i:s");
                $db->insert("wp_downloads", $download);

                // insert Tag;

                $tag1 = $title . ' Apk free download';
                $tag2 = $title . ' for Android';
                $tag3 = 'download ' . $title . ' Apk file';
                $tagbv = array($tag1, $tag2, $tag3);
                foreach ($tagbv as $val):
                    $term1['name'] = $val;
                    $term1['slug'] = vnit_change_title($val);
                    $db->insert("wp_terms", $term1);
                    $term1_id = $db->insert_id();

                    $term_taxo1['term_id'] = $term1_id;
                    $term_taxo1['taxonomy'] = 'post_tag';
                    $term_taxo1['count'] = '0';
                    $db->insert("wp_term_taxonomy", $term_taxo1);
                    $term_taxonomy_id1 = $db->insert_id();

                    $term_re1['object_id'] = $post_id;
                    $term_re1['term_taxonomy_id'] = $term_taxonomy_id1;
                    $db->insert("wp_term_relationships", $term_re1);

                endforeach;

                // Insert Images
                $root_year = ROOT . '../wp-content/uploads/' . date("Y") . '/';
                if (!is_dir($root_year)) {
                    mkdir($root_year, 777);
                }
                $root_month = ROOT . '../wp-content/uploads/' . date("Y") . '/' . date("m") . '/';
                $dir_root = date("Y") . '/' . date("m") . '/';
                if (!is_dir($root_month)) {
                    mkdir($root_month, 777);
                }
                $images = $itemhtml->find("div[class=cover-container] img[0]")[0]->src;
                $image_data = file_get_contents($images); // Get image data
                $filename = preg_replace('/[^a-z0-9]/ui', '-', vnit_change_title($title)); // Create image file name
                if (strpos($filename, ".png") === false && strpos($filename, ".jpg") === false) {
                    $filename .= ".png";
                }
                file_put_contents($root_month . $filename, $image_data);
                $image = $filename;
                $image_ = explode('.', $image);
                $ext = $image_[count($image_) - 1];

                $img->crop($root_month .$filename, $root_month . vnit_change_title($title) . '150x150.' . $ext,150,150,150,150);
                $img->crop($root_month . $filename, $root_month . vnit_change_title($title) . '300x300.' . $ext,300,300,300,300);
                $img->crop($root_month . $filename, $root_month . vnit_change_title($title) . '300x160.' . $ext,60,60,60,60);
                $img->crop($root_month . $filename, $root_month . vnit_change_title($title) . '220x180.' . $ext,220,180,220,180);
                $img->crop($root_month . $filename, $root_month . vnit_change_title($title) . '70x70.' . $ext,70,70,70,70);
                $img->crop($root_month . $filename, $root_month . vnit_change_title($title) . '300x192.' . $ext,300,192,300,192);
                $img->crop($root_month . $filename, $root_month . vnit_change_title($title) . '188x144.' . $ext,188,144,188,144);
                
                /*
                $resizeObj1 = new resize($root_month . $image);
                $resizeObj1->resizeImage(150, 150, 'crop');
                $resizeObj1->saveImage($root_month . vnit_change_title($title) . '150x150.' . $ext, 100);
                unset($resizeObj1);

                $resizeObj8 = new resize($root_month . $image);
                $resizeObj8->resizeImage(300, 300, 'crop');
                $resizeObj8->saveImage($root_month . vnit_change_title($title) . '300x300.' . $ext, 100);
                unset($resizeObj8);

                $resizeObj2 = new resize($root_month . $image);
                $resizeObj2->resizeImage(300, 160, 'crop');
                $resizeObj2->saveImage($root_month . vnit_change_title($title) . '300x160.' . $ext, 100);
                unset($resizeObj2);

                $resizeObj4 = new resize($root_month . $image);
                $resizeObj4->resizeImage(220, 180, 'crop');
                $resizeObj4->saveImage($root_month . vnit_change_title($title) . '220x180.' . $ext, 100);
                unset($resizeObj4);


                $resizeObj5 = new resize($root_month . $image);
                $resizeObj5->resizeImage(70, 70, 'crop');
                $resizeObj5->saveImage($root_month . vnit_change_title($title) . '70x70.' . $ext, 100);
                unset($resizeObj5);


                $resizeObj6 = new resize($root_month . $image);
                $resizeObj6->resizeImage(300, 192, 'crop');
                $resizeObj6->saveImage($root_month . vnit_change_title($title) . '300x192.' . $ext, 100);
                unset($resizeObj6);


                $resizeObj7 = new resize($root_month . $image);
                $resizeObj7->resizeImage(188, 144, 'crop');
                $resizeObj7->saveImage($root_month . vnit_change_title($title) . '188x144.' . $ext, 100);
                unset($resizeObj7);
                */

                // Post Attachment
                $post_attachment = array(
                    'post_title' => $title,
                    'post_status' => 'inherit',
                    'post_type' => 'attachment',
                    'post_mime_type' => mime_type($ext),
                    'post_author' => 1,
                    'post_date' => date("Y-m-d H:i:s"),
                    'post_date_gmt' => date("Y-m-d H:i:s"),
                    'post_name' => vnit_change_title($title),
                    'post_modified' => date("Y-m-d H:i:s"),
                    'post_modified_gmt' => date("Y-m-d H:i:s"),
                    'guid' => 'http://masterapk.com/dungml/' . vnit_change_title($title) . '/' . vnit_change_title($title) . '-2/'
                );
                $db->insert("wp_posts", $post_attachment);
                $_post_thumbnail_id = $db->insert_id();


                $data_attach['width'] = '300';
                $data_attach['height'] = '300';
                $data_attach['height'] = '300';
                $data_attach['height'] = $dir_root . $slug . '.' . $ext;
                $data_attach['sizes'] = array(
                    'thumbnail' => array(
                        'file' => $slug . '150x150.' . $ext,
                        'width' => '150',
                        'height' => '150',
                        'mime-type' => mime_type($ext),
                        'wp_smushit' => 'ERROR: posting to Smush.it'
                    ),
                    'medium' => array(
                        'file' => $slug . '300x300.' . $ext,
                        'width' => '300',
                        'height' => '300',
                        'mime-type' => mime_type($ext),
                        'wp_smushit' => 'ERROR: posting to Smush.it'
                    ),
                    'mag-image' => array(
                        'file' => $slug . '300x160.' . $ext,
                        'width' => '300',
                        'height' => '160',
                        'mime-type' => mime_type($ext),
                        'wp_smushit' => 'ERROR: posting to Smush.it'
                    ),
                    'blog-image' => array(
                        'file' => $slug . '220x180.' . $ext,
                        'width' => '220',
                        'height' => '180',
                        'mime-type' => mime_type($ext),
                        'wp_smushit' => 'ERROR: posting to Smush.it'
                    ),
                    'sb-post-thumbnail' => array(
                        'file' => $slug . '70x70.' . $ext,
                        'width' => '70',
                        'height' => '70',
                        'mime-type' => mime_type($ext),
                        'wp_smushit' => 'ERROR: posting to Smush.it'
                    ),
                    'sb-post-big-thumbnail' => array(
                        'file' => $slug . '300x192.' . $ext,
                        'width' => '300',
                        'height' => '192',
                        'mime-type' => mime_type($ext),
                        'wp_smushit' => 'ERROR: posting to Smush.it'
                    ),
                    'sb-post-small-thumbnail' => array(
                        'file' => $slug . '188x144.' . $ext,
                        'width' => '188',
                        'height' => '144',
                        'mime-type' => mime_type($ext),
                        'wp_smushit' => 'ERROR: posting to Smush.it'
                    )
                );
                $data_attach['image_meta'] = array(
                    'aperture' => 0,
                    'credit' => '',
                    'camera' => '',
                    'caption' => '',
                    'created_timestamp' => 0,
                    'copyright' => '',
                    'focal_length' => 0,
                    'iso' => 0,
                    'shutter_speed' => 0,
                    'title' => "",
                    'orientation' => 0
                );
                $data_attach['wp_smushit'] = 'ERROR: posting to Smush.it';
                $data_ar_img = serialize($data_attach);

                $_wp_attachment_metadata["post_id"] = $_post_thumbnail_id;
                $_wp_attachment_metadata["meta_key"] = "_wp_attachment_metadata";
                $_wp_attachment_metadata["meta_value"] = $data_ar_img;
                $db->insert("wp_postmeta", $_wp_attachment_metadata);


                $_wp_attached_file["post_id"] = $_post_thumbnail_id;
                $_wp_attached_file["meta_key"] = "_wp_attached_file";
                $_wp_attached_file["meta_value"] = $dir_root . $slug . '.' . $ext;
                $db->insert("wp_postmeta", $_wp_attached_file);
                $_thumbnail_ids = $db->insert_id();

                $vattach["post_id"] = $post_id;
                $vattach["meta_key"] = "_thumbnail_id";
                $vattach["meta_value"] = $_post_thumbnail_id;
                $db->insert("wp_postmeta", $vattach);
                unset($_thumbnail_ids);



                $find_list_img = $itemhtml->find("img[class=screenshot]");
                if (count($find_list_img) > 3) {
                    $total_img = 3;
                } else {
                    $total_img = count($find_list_img);
                }
                for ($j = 0; $j <= $total_img; $j++) {
                    $item_img = $find_list_img[$j]->src;
                    //echo '<div>'.$j.': '.$item_img.'</div>';
                    $image_data = file_get_contents($item_img); // Get image data
                    $filename = preg_replace('/[^a-z0-9]/ui', '-', vnit_change_title($title)); // Create image file name
                    if (strpos($filename, ".png") === false && strpos($filename, ".jpg") === false) {
                        $filename .= "-" . $j . ".png";
                    }
                    file_put_contents($root_month . $filename, $image_data);
                    $_infomation_link_image[] = 'http://masterapk.com/wp-content/uploads/' . $dir_root . $filename;
                }
                if (isset($_infomation_link_image)) {
                    $imgdata['post_id'] = $post_id;
                    $imgdata['meta_key'] = "_infomation_link_image";
                    $imgdata['meta_value'] = serialize($_infomation_link_image);
                    $db->insert("wp_postmeta", $imgdata);
                    unset($_infomation_link_image);
                }
                //$termArray = wp_insert_term($title, 'post_tag', 0);
                unset($link);
                unset($title);
            }
        }
        sleep(1);
    }

    $lib->update_active($row->ids);
}
$data['random'] = $random;
$data['ar_cat'] = $ar_cat;
unset($random);
//unset($content);
//die();    
echo json_encode($data);

function retrieveValue($str) {
    if (stripos($str, 'littleBox')) {//check if element has it
        $var = preg_split("/littleBox=\"/", $str);
        //echo $var[1];
        $var1 = preg_split("/\"/", $var[1]);
        return $var1[0];
    } else
        return false;
}

function mime_type($ext) {
    if ($ext == 'png') {
        return 'image/png';
    } else if ($ext == 'jpg') {
        return 'image/jpeg';
    }
}

?>