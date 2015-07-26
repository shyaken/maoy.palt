<?php ob_start(); ?>
<?php
require_once( dirname(__FILE__) . '/admin.php' );

function _post_app_by_url($url, $category)
{
    if (strpos($url, '&hl=') === false && strpos($url, '?hl=') === false) {
        $url .= '';
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($curl);
    curl_close($curl);
    $dom = new DOMDocument('1.0', 'utf-8');
    @$dom->loadHTML('<?xml version="1.0" encoding="utf-8"?>' . $data);
    $xpath = new DOMXPath($dom);
    $game = array();
    $content = "";
    $thumbnail = "";
    /**
     * Retrieve game name:
     */
    $results = $xpath->query("//*[@class='document-title']");
    if ($results->length > 0) {
        $review = $results->item(0)->nodeValue;
        $game['name'] = ucfirst(trim($review));
    }
    /**
     * Retrieve game version:
     */
    $results = $xpath->query("//*[@itemprop='softwareVersion']");
    if ($results->length > 0) {
        $review = $results->item(0)->nodeValue;
        $game['version'] = trim($review);
    }
    /**
     * Retrieve game manufacturer:
     */
    $results = $xpath->query("//*[@class='document-subtitle primary']");
    if ($results->length > 0) {
        $review = $results->item(0)->nodeValue;
        $game['manufacturers'] = trim($review);
    }
    /**
     * Retrieve game images:
     */
    $results = $xpath->query("//*[@itemprop='screenshot']");
    if ($results->length > 0) {
        $review = array();
        for ($i = 0; $i < min(array($results->length, 3)); $i++) {
            $review[] = trim($results->item($i)->getAttribute('src'));
        }
        $game['link_image'] = $review;

    }
    /**
     * Retrieve game description:
     */
    $results = $xpath->query("//*[@class='id-app-orig-desc']");
    $review = '';
    if ($results->length > 0) {
        $review = $results->item(0);
    }
    $results = $xpath->query("//*[@class='id-app-translated-desc']");
    if ($results->length > 0) {
        $review = $results->item(0);
    }
    if (!empty($review)) {
        $html = '';
        foreach ($review->childNodes as $childNode) {
            $html .= $dom->saveXML($childNode);
        }
        $content = $html;
    }
    /**
     * Retrieve game thumbnail:
     */
    $results = $xpath->query("//*[@class='cover-container']/img");
    if ($results->length > 0) {
        $review = trim($results->item(0)->getAttribute('src'));
        $thumbnail = $review;
    }
    $post = array(
        'post_title' => $game['name'],
        'post_content' => $content,
        'post_status' => 'publish',
        'post_author' => get_current_user_id(),
        'post_category' => $category //Static Category: Game Online
    );
    $post_id = wp_insert_post($post, $wp_error);
	
			$tag1 = $game['name'].' Apk free download';
			$tag2 = $game['name'].' for Android';
			$tag3 = 'download '.$game['name'].' Apk file';
			$tagbv = array($tag1,$tag2,$tag3);
			$termArray = wp_insert_term($game['name'], 'post_tag', 0);
            wp_set_post_tags($post_id, $tagbv, true);
			
    $prefix = '_infomation_';
               foreach ($game as $key => $value) {
                if ((empty($value) && $key != "link_image") || ($key == "link_image" && !count($value)))
                    continue;
                 $upload_dir = wp_upload_dir();
                $file = "";
                if (wp_mkdir_p($upload_dir['path'])) {
                    $file = $upload_dir['path'] . '/' ;
                } else {
                    $file = $upload_dir['basedir'] . '/';
                }
                $filePath = explode("/", $file);
                $count = count($filePath);
                $fileText = get_site_url()."/wp-content/uploads"."/".$filePath[$count-3]."/".$filePath[$count-2]."/".$filePath[$count-1];
                if ($key == "link_image") {
                    $i = 0;
                    foreach ($value as $src) {
						$tenanh = preg_replace('/[^a-z0-9]/ui', '-', $game['name']);
                        $filename = $tenanh."-".$i.".jpg";
                        $image_data = file_get_contents($src);
                        file_put_contents($file."".$filename, $image_data);
                        $value[$i] = $fileText."".$filename;
                        $i++;
                    }
                     
                } else {
                }
                add_post_meta($post_id, $prefix . $key, $value);
            }
			global $wpdb;            
			$table_name = $wpdb->prefix . "downloads";            
			$file_download = array(                
			"post_id" => $post_id,                
			"file" => $url,                
			"jad_file" => "",                
			"file_name" => $game['name'],                
			"file_des" => $game['name'],                
			"file_size" => "-1",                
			"file_date" => date("Y-m-d H:i:s"),                
			"file_updated_date" => date("Y-m-d H:i:s"),                
			"file_last_downloaded_date" => date("Y-m-d H:i:s"),                
			"file_hits" => 0,                
			"file_views" => 0            
			);            
			$wpdb->insert($table_name,$file_download);
    // Add Featured Image to Post
    if (!empty($thumbnail)) {
        $thumbnail = str_replace("https:/", "http:/", $thumbnail);
        $upload_dir = wp_upload_dir(); // Set upload folder
        $image_data = file_get_contents($thumbnail); // Get image data
        $filename = preg_replace('/[^a-z0-9]/ui', '-', $game['name']); // Create image file name
        if (strpos($filename, ".png") === false && strpos($filename, ".jpg") === false) {
            $filename .= ".png";
        }
        // Check folder permission and define file location
        if (wp_mkdir_p($upload_dir['path'])) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }
        // Create the image  file on the server
        file_put_contents($file, $image_data);
        // Check image file type
        $wp_filetype = wp_check_filetype($filename, null);

        // Set attachment data
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => $game['name'],
            'post_content' => '',
            'post_status' => 'inherit'
        );

        // Create the attachment
        $attach_id = wp_insert_attachment($attachment, $file, $post_id);
        // Include image.php
        require_once(dirname(__FILE__) .'/includes/image.php');

        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);

        // Assign metadata to attachment
        wp_update_attachment_metadata($attach_id, $attach_data);

        // And finally assign featured image to post
        set_post_thumbnail($post_id, $attach_id);
    }

    return true;
}

    function _category_url_to_app_urls($url, $limitter=null){
    $urlpf = (substr($url, -1) == '/') ? '' : '/';
    $freeurl = $url. ($limitter?"?num={$limitter}":"");
    $allurlPf = 'https://play.google.com';
    $urls = $freeurl;
    $urlreturns = array();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $urls);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($curl);
        curl_close($curl);
        $dom = new DOMDocument('1.0', 'utf-8');
        @$dom->loadHTML('<?xml version="1.0" encoding="utf-8"?>' . $data);
        $xpath = new DOMXPath($dom);
        /**
         * Retrieve app url:
         */
        $results = $xpath->query("//*[@class='cover']/a[@class='card-click-target']");
        if ($results->length > 0) {
            for ($i = 0; $i < $results->length; $i++) {
                $urlreturns[] = $allurlPf . $results->item($i)->getAttribute('href');
            }
        }

    return $urlreturns;
}

require_once( ABSPATH . 'wp-admin/admin-header.php' );
?>
<div class="wrap">
    <h2>Google play Category Import</h2>
    <form action="postgame.php" method="post">
        <?php $tax = get_taxonomy('category'); ?>
        <div id="categorydiv" class="postbox">
            <div class="inside">
                <b><?php _e('Destination Categories:') ?></b>
                <div id="taxonomy-category" class="categorydiv">
                    <div id="category-all" class="tabs-panel">
                        <ul id="categorychecklist" data-wp-lists="list:category" class="categorychecklist form-no-clear">
                            <?php wp_terms_checklist($post_ID, array('taxonomy' => 'category', 'popular_cats' => $popular_ids)) ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <input style="width: 50%;" type="text" name="url" placeholder="Paste google play category link here!" />
        <input type="text" name="limit" placeholder="Limit number" />
        <button type="submit">Submit</button>
        <p><b>HINT:</b></p>
        <p>-<i>Google play link example: <u>https://play.google.com/store/apps/category/GAME_STRATEGY</u></i></p>
        <p>-<i>Entering limit number to limitting number of apps (FREE or PAID) that will be imported. Default limit: <b>60</b> (60 FREE + 60 PAID), max acceptable limit: <b>119</b></i></p>
        <hr>
        <br>
        <h2>Single App Import</h2>
        <input style="width: 63%;" type="text" name="product_url" placeholder="Paste google play app link here!" />
        <button type="submit">Submit</button>
        <p></p>
    </form>
</div>

<?php
if($_POST['post_category']){
    $idJoined = implode(", ",$_POST['post_category']);
    if (isset($_POST['url']) && !empty($_POST['url'])){
        $url = $_POST['url'];
        $urls = _category_url_to_app_urls($url,$_POST['limit']);
        $success=0;
        $falure=0;
        foreach($urls as $appUrl){
            if(_post_app_by_url($appUrl, $_POST['post_category'])) {
                $success++;
            }else{
                $falure++;
            }
        }
        echo "<p style='color:green'>{$success} apps have been added to category {$idJoined}</p>";
        if($falure>0) echo ", {$falure} failed";
    } else if ($_POST['product_url']) {
        if(_post_app_by_url($_POST['product_url'], $_POST['post_category']))
            echo "<p style='color:green'>The app at this url: <i><u>{$_POST['product_url']}</u></i> has been successfully added to category {$idJoined}</p>";
    } else {
        echo "<p style='color:red'>URL shouldn't be empty!</p>";
    }
}else{
    if ($_POST) echo "<p style='color:red'>Please choose a destination category and continue!</p>";
}
include( ABSPATH . 'wp-admin/admin-footer.php' );
?>
