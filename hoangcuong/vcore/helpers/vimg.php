<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function img_resize($path, $path_new, $width, $height){
    
}

function vnit_resize_image($src, $dest, $new_width = 0, $new_height = 0, $make_box = true, $bg_color = '#ffffff', $save_original = false)
{

    
    static $notification_set = false;
    static $gd_settings = array();
    if(file_exists($dest)){
        unlink($dest);
    }
    if (file_exists($src) && !empty($dest) && (!empty($new_width) || !empty($new_height)) && extension_loaded('gd')) {

        $img_functions = array(
            'png' => function_exists('imagepng'),
            'jpg' => function_exists('imagejpeg'),
            'gif' => function_exists('imagegif'),
        );
        /*
        if (empty($gd_settings)) {
            $gd_settings = fn_get_settings('Thumbnails');
        }
        */

        $dst_width = $new_width;
        $dst_height = $new_height;

        list($width, $height, $mime_type) = fn_get_image_size($src);
        if (empty($width) || empty($height)) {
            return false;
        }

        if ($width < $new_width) {
            $new_width = $width;
        }
        if ($height < $new_height) {
            $new_height = $height;
        }

        if ($dst_height == 0) { // if we passed width only, calculate height
            $new_height = $dst_height = ($height / $width) * $new_width;

        } elseif ($dst_width == 0) { // if we passed height only, calculate width
            $new_width = $dst_width = ($width / $height) * $new_height;

        } else { // we passed width and height, limit image by height! (hm... not sure we need it anymore?)
            if ($new_width * $height / $width > $dst_height) {
                $new_width = $width * $dst_height / $height;
            }
            $new_height = ($height / $width) * $new_width;
            if ($new_height * $width / $height > $dst_width) {
                $new_height = $height * $dst_width / $width;
            }
            $new_width = ($width / $height) * $new_height;
        }

        $w = number_format($new_width, 0, ',', '');
        $h = number_format($new_height, 0, ',', '');

        $ext = fn_get_image_extension($mime_type);

        if (!empty($img_functions[$ext])) {
            if ($make_box) {
                $dst = imagecreatetruecolor($dst_width, $dst_height);
            } else {
                $dst = imagecreatetruecolor($w, $h);
            }
            if (function_exists('imageantialias')) {
                imageantialias($dst, true);
            }
        } elseif ($notification_set == false) {
            $msg = fn_get_lang_var('error_image_format_not_supported');
            $msg = str_replace('[format]', $ext, $msg);
            fn_set_notification('E', fn_get_lang_var('error'), $msg);
            $notification_set = true;
            return false;
        }

        if ($ext == 'gif' && $img_functions[$ext] == true) {
            $new = imagecreatefromgif($src);
        } elseif ($ext == 'jpg' && $img_functions[$ext] == true) {
            $new = imagecreatefromjpeg($src);
        } elseif ($ext == 'png' && $img_functions[$ext] == true) {
            $new = imagecreatefrompng($src);
        } else {
            return false;
        }

        // Set transparent color to white
        // Not sure that this is right, but it works
        // FIXME!!!
        // $c = imagecolortransparent($new);

        list($r, $g, $b) = fn_parse_rgb($bg_color);
        $c = imagecolorallocate($dst, $r, $g, $b);
        //imagecolortransparent($dst, $c);
        if ($make_box) {
            imagefilledrectangle($dst, 0, 0, $dst_width, $dst_height, $c);
            $x = number_format(($dst_width - $w) / 2, 0, ',', '');
            $y = number_format(($dst_height - $h) / 2, 0, ',', '');
        } else {
            imagefilledrectangle($dst, 0, 0, $w, $h, $c);
            $x = 0;
            $y = 0;
        }
        imagecopyresampled($dst, $new, $x, $y, 0, 0, $w, $h, $width, $height);
        
        //if ($gd_settings['convert_to'] == 'original') {
            $gd_settings['convert_to'] = $ext;
       // }

        if (empty($img_functions[$gd_settings['convert_to']])) {
            foreach ($img_functions as $k => $v) {
                if ($v == true) {
                    $gd_settings['convert_to'] = $k;
                    break;
                }
            }
        }

        $pathinfo = pathinfo($dest);
        $new_filename = $pathinfo['dirname'] . '/' . basename($pathinfo['basename'], empty($pathinfo['extension']) ? '' : '.' . $pathinfo['extension']);
        
        // Remove source thumbnail file
        /*
        if (!$save_original) {
            fn_rm($src);
        }
        */

        switch ($gd_settings['convert_to']) {
            case 'gif':
                $new_filename .= '.gif';
                imagegif($dst, $new_filename);
                break;
            case 'jpg':
                $new_filename .= '.jpg';
                imagejpeg($dst, $new_filename, 100);
                break;
            case 'png':
                $new_filename .= '.png';
                imagepng($dst, $new_filename);
                break;
        }
        $dest = $new_filename;
        @chmod($dest, 0755);

        //return true;
    }

    //return false;
}
function resize_image($src, $dest, $new_width = 0, $new_height = 0, $make_box = true, $bg_color = '#ffffff', $save_original = false)
{

    
    static $notification_set = false;
    static $gd_settings = array();
    if(file_exists($dest)){
        unlink($dest);
    }
    if (file_exists($src) && !empty($dest) && (!empty($new_width) || !empty($new_height)) && extension_loaded('gd')) {

        $img_functions = array(
            'png' => function_exists('imagepng'),
            'jpg' => function_exists('imagejpeg'),
            'gif' => function_exists('imagegif'),
        );
        /*
        if (empty($gd_settings)) {
            $gd_settings = fn_get_settings('Thumbnails');
        }
        */

        $dst_width = $new_width;
        $dst_height = $new_height;

        list($width, $height, $mime_type) = fn_get_image_size($src);
        if (empty($width) || empty($height)) {
            return false;
        }

        if ($width < $new_width) {
            $new_width = $width;
        }
        if ($height < $new_height) {
            $new_height = $height;
        }

        if ($dst_height == 0) { // if we passed width only, calculate height
            $new_height = $dst_height = ($height / $width) * $new_width;

        } elseif ($dst_width == 0) { // if we passed height only, calculate width
            $new_width = $dst_width = ($width / $height) * $new_height;

        } else { // we passed width and height, limit image by height! (hm... not sure we need it anymore?)
            if ($new_width * $height / $width > $dst_height) {
                $new_width = $width * $dst_height / $height;
            }
            $new_height = ($height / $width) * $new_width;
            if ($new_height * $width / $height > $dst_width) {
                $new_height = $height * $dst_width / $width;
            }
            $new_width = ($width / $height) * $new_height;
        }

        $w = number_format($new_width, 0, ',', '');
        $h = number_format($new_height, 0, ',', '');

        $ext = fn_get_image_extension($mime_type);

        if (!empty($img_functions[$ext])) {
            if ($make_box) {
                $dst = imagecreatetruecolor($dst_width, $dst_height);
            } else {
                $dst = imagecreatetruecolor($w, $h);
            }
            if (function_exists('imageantialias')) {
                imageantialias($dst, true);
            }
        } elseif ($notification_set == false) {
            $msg = fn_get_lang_var('error_image_format_not_supported');
            $msg = str_replace('[format]', $ext, $msg);
            fn_set_notification('E', fn_get_lang_var('error'), $msg);
            $notification_set = true;
            return false;
        }

        if ($ext == 'gif' && $img_functions[$ext] == true) {
            $new = imagecreatefromgif($src);
        } elseif ($ext == 'jpg' && $img_functions[$ext] == true) {
            $new = imagecreatefromjpeg($src);
        } elseif ($ext == 'png' && $img_functions[$ext] == true) {
            $new = imagecreatefrompng($src);
        } else {
            return false;
        }

        // Set transparent color to white
        // Not sure that this is right, but it works
        // FIXME!!!
        // $c = imagecolortransparent($new);

        list($r, $g, $b) = fn_parse_rgb($bg_color);
        $c = imagecolorallocate($dst, $r, $g, $b);
        //imagecolortransparent($dst, $c);
        if ($make_box) {
            imagefilledrectangle($dst, 0, 0, $dst_width, $dst_height, $c);
            $x = number_format(($dst_width - $w) / 2, 0, ',', '');
            $y = number_format(($dst_height - $h) / 2, 0, ',', '');
        } else {
            imagefilledrectangle($dst, 0, 0, $w, $h, $c);
            $x = 0;
            $y = 0;
        }
        imagecopyresampled($dst, $new, $x, $y, 0, 0, $w, $h, $width, $height);
        
        //if ($gd_settings['convert_to'] == 'original') {
            $gd_settings['convert_to'] = $ext;
       // }

        if (empty($img_functions[$gd_settings['convert_to']])) {
            foreach ($img_functions as $k => $v) {
                if ($v == true) {
                    $gd_settings['convert_to'] = $k;
                    break;
                }
            }
        }

        $pathinfo = pathinfo($dest);
        $new_filename = $pathinfo['dirname'] . '/' . basename($pathinfo['basename'], empty($pathinfo['extension']) ? '' : '.' . $pathinfo['extension']);
        
        // Remove source thumbnail file
        /*
        if (!$save_original) {
            fn_rm($src);
        }
        */

        switch ($gd_settings['convert_to']) {
            case 'gif':
                $new_filename .= '.gif';
                imagegif($dst, $new_filename);
                break;
            case 'jpg':
                $new_filename .= '.jpg';
                imagejpeg($dst, $new_filename, 100);
                break;
            case 'png':
                $new_filename .= '.png';
                imagepng($dst, $new_filename);
                break;
        }
        $dest = $new_filename;
        @chmod($dest, 0755);

        return $dest;
    }

    return 0;
}
//
// Check supported GDlib formats
//
function fn_check_gd_formats()
{
    $avail_formats = array(
        'original' => fn_get_lang_var('same_as_source'),
    );

    if (function_exists('imagegif')) {
        $avail_formats['gif'] = 'GIF';
    }
    if (function_exists('imagejpeg')) {
        $avail_formats['jpg'] = 'JPEG';
    }
    if (function_exists('imagepng')) {
        $avail_formats['png'] = 'PNG';
    }

    return $avail_formats;
}

//
// Get image extension by MIME type
//
function fn_get_image_extension($image_type)
{
    static $image_types = array (
        'image/gif' => 'gif',
        'image/pjpeg' => 'jpg',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'application/x-shockwave-flash' => 'swf',
        'image/psd' => 'psd',
        'image/bmp' => 'bmp',
    );

    return isset($image_types[$image_type]) ? $image_types[$image_type] : false;
}

//
// Getimagesize wrapper
// Returns mime type instead of just image type
// And doesn't return html attributes
function fn_get_image_size($file)
{
    // File is url, get it and store in temporary directory
    if (strpos($file, '://') !== false) {
        $tmp = fn_create_temp_file();

        if (fn_put_contents($tmp, fn_get_contents($file)) == 0) {
            return false;
        }

        $file = $tmp;
    }

    list($w, $h, $t, $a) = @getimagesize($file);

    if (empty($w)) {
        return false;
    }

    $t = image_type_to_mime_type($t);

    return array($w, $h, $t);
}

function fn_attach_image_pairs($name, $object_type, $object_id = 0, $lang_code = CART_LANGUAGE, $object_ids = array (), $parent_object = '', $parent_object_id = 0)
{
    $icons = fn_filter_uploaded_data($name . '_image_icon');
    $detailed = fn_filter_uploaded_data($name . '_image_detailed');
    $pairs_data = !empty($_REQUEST[$name . '_image_data']) ? $_REQUEST[$name . '_image_data'] : array();

    return fn_update_image_pairs($icons, $detailed, $pairs_data, $object_id, $object_type, $object_ids, $parent_object, $parent_object_id, true, $lang_code);
}

function fn_generate_thumbnail($image_path, $width, $height = 0, $make_box = false)
{
    if (empty($image_path)) {
        return '';
    }
    
    if (strpos($image_path, '://') === false) {
        if (strpos($image_path, '/') !== 0) { // relative path
            $image_path = Registry::get('config.current_path') . '/' . $image_path;
        }
        $image_path = (defined('HTTPS') ? ('https://' . Registry::get('config.https_host')) : ('http://' . Registry::get('config.http_host'))) . $image_path;
    }

    $_path = str_replace(Registry::get('config.current_location') . '/', '', $image_path);

    $image_url = explode('/', $_path);
    $image_name = array_pop($image_url);
    $image_dir = array_pop($image_url);
    $image_dir .= '/' . $width . (empty($height) ? '' : '/' . $height);
    $filename = $image_dir . '/' . $image_name;
    $real_path = htmlspecialchars_decode(DIR_ROOT . '/' . $_path, ENT_QUOTES);
    $th_path = htmlspecialchars_decode(DIR_THUMBNAILS . $filename, ENT_QUOTES);

    if (!fn_mkdir(DIR_THUMBNAILS . $image_dir)) {
        return '';
    }

    if (!file_exists($th_path)) {
        if (fn_get_image_size($real_path)) {
            $image = fn_get_contents($real_path);
            fn_put_contents($th_path, $image);
            fn_resize_image($th_path, $th_path, $width, $height, $make_box, Registry::get('settings.Thumbnails.thumbnail_background_color'));
            $filename_info = pathinfo($filename);
            $th_path_info = pathinfo($th_path);
            $filename = $filename_info['dirname'] . '/' . $th_path_info['basename'];
        } else {
            return '';
        }
    }

    return Registry::get('config.thumbnails_path') . $filename;
}

function fn_parse_rgb($color)
{
    $r = hexdec(substr($color, 1, 2));
    $g = hexdec(substr($color, 3, 2));
    $b = hexdec(substr($color, 5, 2));
    return array($r, $g, $b);
}

function fn_find_valid_image_path($image_pair, $object_type, $get_flash, $lang_code)
{
    if (isset($image_pair['icon']['absolute_path']) && is_file($image_pair['icon']['absolute_path'])) {
        if (!$get_flash && isset($image_pair['icon']['is_flash']) && $image_pair['icon']['is_flash']) {
            // We don't need Flash at all -- no need to crawl images any more.
            return false;
        } else {
            return $image_pair['icon']['image_path'];
        }
    }

    // Try to get the product's image.
    if (!empty($image_pair['image_id'])) {
        $image = fn_get_image($image_pair['image_id'], $object_type, 0, $lang_code);

        if (isset($image['absolute_path']) && is_file($image['absolute_path'])) {
            if (!$get_flash && isset($image['is_flash']) && $image['is_flash']) {
                return false;
            }

            return $image['image_path'];
        }
    }

    // If everything above failed, try to generate the thumbnail.
    if (!empty($image_pair['detailed_id'])) {
        $image = fn_get_image($image_pair['detailed_id'], 'detailed', 0, $lang_code);

        if (isset($image['absolute_path']) && is_file($image['absolute_path'])) {
            if (isset($image['is_flash']) && $image['is_flash']) {
                if ($get_flash) {
                    // No need to call fn_generate_thumbnail()
                    return $image['image_path'];
                } else {
                    return false;
                }
            }

            $image = fn_generate_thumbnail($image['image_path'], Registry::get('settings.Thumbnails.product_details_thumbnail_width'), Registry::get('settings.Thumbnails.product_details_thumbnail_height'), false);
            if (!empty($image)) {
                return $image;
            }
        }
    }

    return false;
}

function fn_convert_relative_to_absolute_image_url($image_path)
{
    return 'http://' . Registry::get('config.http_host') . $image_path;
}

?>
