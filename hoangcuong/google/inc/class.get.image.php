<?php
class GetImage {

var $source;
var $save_to;
var $set_extension;
var $quality;
var $img_name;

function download($method = 'curl') // default method: cURL
{
        $info = @GetImageSize($this->source);
        $mime = $info['mime'];

        // What sort of image?
        $type = substr(strrchr($mime, '/'), 1);

        switch ($type)
        {
        case 'jpeg':
            $image_create_func = 'ImageCreateFromJPEG';
            $image_save_func = 'ImageJPEG';
            $new_image_ext = 'jpg';

            // Best Quality: 100
            $quality = isSet($this->quality) ? $this->quality : 100;
            break;

        case 'png':
            $image_create_func = 'ImageCreateFromPNG';
            $image_save_func = 'ImagePNG';
            $new_image_ext = 'png';

            // <span class="IL_AD" id="IL_AD9">Compression</span> Level: from 0  (no compression) to 9
            $quality = isSet($this->quality) ? $this->quality : 0;
            break;

        case 'bmp':
            $image_create_func = 'ImageCreateFromBMP';
            $image_save_func = 'ImageBMP';
            $new_image_ext = 'bmp';
            break;

        case 'gif':
            $image_create_func = 'ImageCreateFromGIF';
            $image_save_func = 'ImageGIF';
            $new_image_ext = 'gif';
            break;

        case 'vnd.wap.wbmp':
            $image_create_func = 'ImageCreateFromWBMP';
            $image_save_func = 'ImageWBMP';
            $new_image_ext = 'bmp';
            break;

        case 'xbm':
            $image_create_func = 'ImageCreateFromXBM';
            $image_save_func = 'ImageXBM';
            $new_image_ext = 'xbm';
            break;

        default:
            $image_create_func = 'ImageCreateFromJPEG';
            $image_save_func = 'ImageJPEG';
            $new_image_ext = 'jpg';
        }

        $new_name = $this->img_name.'.'.$new_image_ext;

        //$new_name = str_replace('%20','_',$new_name);

        $save_to = $this->save_to.$new_name;
      
            if($method == 'curl')
            {
                echo $save_image = $this->LoadImageCURL($save_to);
            }
            elseif($method == 'gd')
            {
            $img = $image_create_func($this->source);

                if(isSet($quality))
                {
                   $save_image = $image_save_func($img, $save_to, $quality);
                }
                else
                {
                   $save_image = $image_save_func($img, $save_to);
                }
            }

            return $new_name;
}

function LoadImageCURL($save_to)
{
$ch = curl_init($this->source);
$fp = fopen($save_to, "wb");

// set URL and other appropriate options
$options = array(CURLOPT_FILE => $fp,
                 CURLOPT_HEADER => 0,
                 CURLOPT_FOLLOWLOCATION => 1,
                 CURLOPT_TIMEOUT => 60); // 1 minute timeout (should be enough)

curl_setopt_array($ch, $options);

curl_exec($ch);
curl_close($ch);
fclose($fp);
}
}
?>