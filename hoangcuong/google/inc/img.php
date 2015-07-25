<?php
class img{
    function __construct(){
        
        $this->libimg = new libimg();
    }
    
    function resize($path_old, $path_new, $width = 0, $height = 0){
        $this->libimg->source_path = $path_old;
        $ext = substr($this->libimg->source_path, strrpos($this->libimg->source_path, '.') + 1);
        $this->libimg->target_path = $path_new;
        if (!$this->libimg->resize($width, $height, ZEBRA_IMAGE_BOXED, -1)){
            return false;
        }else{
            return true;
        }
    }
    
    function crop($path_old, $path_new, $width_resize = 0, $height_resize = 0, $width_crop = 100, $height_crop = 100){
        $file_name_ = explode('/',$path_new);
        $file_name = $file_name_[sizeof($file_name_)-1];
        $path_new_resize = ROOT_TAM.'data/'.$file_name;
        
        $this->resize($path_old, $path_new_resize, $width_resize, $height_resize);
        $this->libimg->source_path = $path_new_resize;
        $this->libimg->target_path = $path_new;
        if (!$this->libimg->crop(0, 0, $width_crop, $height_crop)){
            return false;
        }else{
            //unlink($path_new_resize);
            return true;
        }
    }
}