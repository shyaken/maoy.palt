<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function vnit_editor($value,$name, $by){
    $data = '<textarea class="editor" row="50" name="'.$name.'" id="'.$by.'">'.$value.'</textarea>';
    $data .='
    <script type="text/javascript">
        var cke = CKEDITOR.replace(\''.$by.'\',{
            toolbar : \'full\'
        });
    </script>    
    ';
    return $data;
    
}
