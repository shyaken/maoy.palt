<?php
function wp_check_filetype( $filename, $mimes = null ) {
    if ( empty($mimes) )
        $mimes = get_allowed_mime_types();
    $type = false;
    $ext = false;
 
    foreach ( $mimes as $ext_preg => $mime_match ) {
        $ext_preg = '!\.(' . $ext_preg . ')(\?.*)?$!i';
        if ( preg_match( $ext_preg, $filename, $ext_matches ) ) {
            $type = $mime_match;
            $ext = $ext_matches[1];
            break;
        }
    }
 
    return compact( 'ext', 'type' );
}
?>
