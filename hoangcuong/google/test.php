<?php
define('ROOT','');
define('HOST','localhost');
define('USER','root');
define('PASS','');
define('DBNAME','admin_masterapk');
include "inc/db.php";
include "inc/lib.php";
include "inc/simple_html_dom.php";
include "inc/image.php";
include "inc/functions.php";
include "inc/plugin.php";
include "inc/post.php";
include "inc/formatting.php";
//include "inc/option.php";

//include "inc/function_getlink.php";

include "inc/function_string.php";
include "inc/class.get.image.php";
include("inc/resize-class.php");
$getimg = new GetImage();
//include "inc/vimg.php";
//include "inc/cron.php";
//include "inc/class.get.image.php";
$post["start"] = rand(60,1000);;
$post["num"] = 5;
$post["numChildren"] = 0;
$post["ipf"] = 1;
$post["xhr"] = 1;
//$post["token"] = "3GOZfa2gaq0e6Fga54trwHTzeXQ:1428335379649";
$post_id = 22722;
$file = ROOT.'data/1-plants-vs-zombies-free300x160.png';
$filename = ROOT.'data/1-plants-vs-zombies-free300x160.png';
$game['name'] = "abc";
$game['version'] = "1.2";
$game['manufacturers'] = "IMOS";
$game['link_image'] = $filename;
$data['width'] = '300';
$data['height'] = '300';
$data['height'] = '300';
$data['height'] = '2015/04/Inbox-by-Gmail.png';
$data['sizes'] = array(
        'thumbnail'=> array(
            'file'=>'Inbox-by-Gmail-150x150.png',
            'width'=>'150',
            'height'=>'150',
            'mime-type'=>'image/png',
            'wp_smushit'=>'ERROR: posting to Smush.it'
        ),
        'medium'=> array(
            'file'=>'Inbox-by-Gmail-300x300.png',
            'width'=>'300',
            'height'=>'300',
            'mime-type'=>'image/png',
            'wp_smushit'=>'ERROR: posting to Smush.it'
        ), 
        'mag-image'=> array(
            'file'=>'Inbox-by-Gmail-300x160.png',
            'width'=>'300',
            'height'=>'160',
            'mime-type'=>'image/png',
            'wp_smushit'=>'ERROR: posting to Smush.it'
        ), 
        'blog-image'=> array(
            'file'=>'Inbox-by-Gmail-220x180.png',
            'width'=>'220',
            'height'=>'180',
            'mime-type'=>'image/png',
            'wp_smushit'=>'ERROR: posting to Smush.it'
        ), 
        'sb-post-thumbnail'=> array(
            'file'=>'Inbox-by-Gmail-70x70.png',
            'width'=>'70',
            'height'=>'70',
            'mime-type'=>'image/png',
            'wp_smushit'=>'ERROR: posting to Smush.it'
        ), 
        'sb-post-big-thumbnail'=> array(
            'file'=>'Inbox-by-Gmail-300x192.png',
            'width'=>'300',
            'height'=>'192',
            'mime-type'=>'image/png',
            'wp_smushit'=>'ERROR: posting to Smush.it'
        ),
        'sb-post-small-thumbnail'=> array(
            'file'=>'Inbox-by-Gmail-188x144.png',
            'width'=>'188',
            'height'=>'144',
            'mime-type'=>'image/png',
            'wp_smushit'=>'ERROR: posting to Smush.it'
        )
);
$data['image_meta'] = array(
    'aperture'=>0,
    'credit'=>'',
    'camera'=>'',
    'caption'=>'',
    'created_timestamp'=>0,
    'copyright'=>'',
    'focal_length'=>0,
    'iso'=>0,
    'shutter_speed'=>0,
    'title'=>"",
    'orientation'=>0
);
$data['wp_smushit'] = 'ERROR: posting to Smush.it';
echo serialize($data);
//serialize
die();
$wp_filetype = wp_check_filetype($filename, null);

        // Set attachment data
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => $game['name'],
            'post_content' => '',
            'post_status' => 'inherit'
        );
        //var_dump($attachment);

        // Create the attachment
        $attach_id = wp_insert_attachment($attachment, $file, $post_id);
        // Include image.php
        //require_once(dirname(__FILE__) .'/includes/image.php');

        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);

        // Assign metadata to attachment
        wp_update_attachment_metadata($attach_id, $attach_data);

        // And finally assign featured image to post
        set_post_thumbnail($post_id, $attach_id);
die();

$lib = new lib();
$total_no_active = $lib->get_num_link_no_active();
$total_active = $lib->get_num_link_active();
$total_link = $total_active + $total_no_active;
$phantram = round(($total_active * 100 ) / $total_link,1);
$_SESSION['total'] = $total_link;
$_SESSION['da_thuc_hien'] = $total_active;
$_SESSION['chua_thuc_hien'] = $total_no_active;
$_SESSION['con_lai'] = $total_link - $total_active;
$_SESSION['phan_tram'] = $phantram;
    echo '<meta charset="UTF-8">';
    //$url = 'https://play.google.com/store/apps/category/GAME_ADVENTURE/collection/topselling_free';
    //$data_content = _curl($url);
$url = 'https://play.google.com/store/apps/category/GAME_STRATEGY/collection/topselling_free?authuser=0';
$data_content = postPage($url,$post);
    $html =  str_get_html($data_content);
    $items = $html->find("div[class=details] h2 a[class=title]");
    for($i = 0; $i < 2; $i++){
        $content = '';
        $item = $items[$i]; 
        $title = $item->plaintext;
        $link = $item->href;
        $link = strpos($link, 'https://')!==false ? $link : 'https://play.google.com'.$link;
        echo '<div>Title: '.$title.'</div>';  
        echo '<div>Link: '.$link.'</div>';  
        $itemdatas = _curl($link);
        $itemhtml = str_get_html($itemdatas);
        // Hinh anh
        $root_year = ROOT.'../../wp-content/uploads/'.date("Y").'/';
        if(!is_dir($root_year)){
            mkdir($root_year,777);    
        }                         
        $root_month = ROOT.'../../wp-content/uploads/'.date("Y").'/'.date("m").'/';
        if(!is_dir($root_month)){
            mkdir($root_month,777);    
        } 
        $images = $itemhtml->find("div[class=cover-container] img[0]")[0]->src;
        $image_data = file_get_contents($images); // Get image data
        $filename = preg_replace('/[^a-z0-9]/ui', '-', vnit_change_title($title)); // Create image file name
        if (strpos($filename, ".png") === false && strpos($filename, ".jpg") === false) {
            $filename .= ".png";
        }
        file_put_contents($root_month.$filename, $image_data);
        $image = $filename; 
        $image_ = explode('.',$image);
        $ext = $image_[count($image_) - 1];
        
        $resizeObj = new resize(ROOT.'data/'.$image);
        $resizeObj -> resizeImage(200, 200, 'crop');
        $resizeObj -> saveImage(ROOT.'data/'.vnit_change_title($title).'200x200.'.$ext, 100);
        unset($resizeObj);
        
        $resizeObj1 = new resize(ROOT.'data/'.$image);
        $resizeObj1 -> resizeImage(150, 150, 'crop');
        $resizeObj1 -> saveImage(ROOT.'data/'.vnit_change_title($title).'150x150.'.$ext, 100);
        unset($resizeObj1);
        
        $resizeObj2 = new resize(ROOT.'data/'.$image);
        $resizeObj2 -> resizeImage(300, 160, 'crop');
        $resizeObj2 -> saveImage(ROOT.'data/'.vnit_change_title($title).'300x160.'.$ext, 100);
        unset($resizeObj2);
        
        $resizeObj4 = new resize(ROOT.'data/'.$image);
        $resizeObj4 -> resizeImage(220, 180, 'crop');
        $resizeObj4 -> saveImage(ROOT.'data/'.vnit_change_title($title).'220x180.'.$ext, 100);
        unset($resizeObj4);
        
        $resizeObj5 = new resize(ROOT.'data/'.$image);
        $resizeObj5 -> resizeImage(70, 70, 'crop');
        $resizeObj5 -> saveImage(ROOT.'data/'.vnit_change_title($title).'70x70.'.$ext, 100);
        unset($resizeObj5);
        
        $resizeObj6 = new resize(ROOT.'data/'.$image);
        $resizeObj6 -> resizeImage(300, 192, 'crop');
        $resizeObj6 -> saveImage(ROOT.'data/'.vnit_change_title($title).'300x192.'.$ext, 100);
        unset($resizeObj6);
        
        $resizeObj7 = new resize(ROOT.'data/'.$image);
        $resizeObj7 -> resizeImage(188, 144, 'crop');
        $resizeObj7 -> saveImage(ROOT.'data/'.vnit_change_title($title).'188x144.'.$ext, 100);
        unset($resizeObj7);
        
        $resizeObj8 = new resize(ROOT.'data/'.$image);
        $resizeObj8 -> resizeImage(300, 300, 'crop');
        $resizeObj8 -> saveImage(ROOT.'data/'.vnit_change_title($title).'300x300.'.$ext, 100);
        unset($resizeObj8);
        
        
        
        $find_list_img = $itemhtml->find("img[class=screenshot]");
        
        for($j = 0; $j < count($find_list_img); $j++){
            $item_img = $find_list_img[$j]->src;
            //echo '<div>'.$j.': '.$item_img.'</div>';
            $image_data = file_get_contents($item_img); // Get image data
            $filename = preg_replace('/[^a-z0-9]/ui', '-', vnit_change_title($title)); // Create image file name
            if (strpos($filename, ".png") === false && strpos($filename, ".jpg") === false) {
                $filename .= "-".$j.".png";
            }
            file_put_contents($root_month.$filename, $image_data);
        }
        
        $version = $itemhtml->find('div[class=meta-info] div[itemprop="softwareVersion"]')[0]->plaintext;
        $manufacture = $itemhtml->find('a[class="document-subtitle primary"] span')[0]->plaintext;
        $price = $itemhtml->find('meta[itemprop="price"]');
        foreach($price as $element){
            echo $element;
           if(isset($element->type)!= false){
            echo retrieveValue($element).'______________________________________________________________';
           }
        }
        
        // Lay thong tin bai viet
        $content_ = $itemhtml->find("div[class=show-more-content]");
        $content .= $content_[0]->innertext;
        $content .='<h2 class="app-title"><i>'.$title.'</i></h2>';
        $content .='<div style="float:left;height:150px;width:150px;padding:15px 20px 10px 0;">
            <a title="Download '.$title.'"><img width="150" height="150" alt="'.$title.'" src="'.$images.'"></a>
        </div>';
        $content .='<div style="padding:15px 0 10px 0;">
        <p>Download '.$title.' V '.$version.' :</p>
        <p class="no_translate" style="margin:15px 0;">
        <a class="download" rel="nofollow" href="http://etcandroid.com/download/1644/exploration-lite"> <strong> ++ Click here to download ++ </strong> </a>    
         </p>
            <ul>
                <li>• Price: A Free App for $0 </li>
                <li>• Version: '.$version.'</li>
                <li>• Manufacture: '.$manufacture.'</li>
                <li>• File Name: '.$title.' APK</li>
            </ul>
        </div>';
        echo $content;
    }


function retrieveValue($str){
    if (stripos($str, 'littleBox')){//check if element has it
    $var=preg_split("/littleBox=\"/",$str);
    //echo $var[1];
    $var1=preg_split("/\"/",$var[1]);
    return $var1[0];
    }
    else
    return false;
}

?>