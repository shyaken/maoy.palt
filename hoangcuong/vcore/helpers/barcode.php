<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function create_barcode($code,$number, $length){
    $strlen = strlen($number);
    $arr    =    array();
    $diff    =    $length -  $strlen;
    // Push Leading Zeros
    while ( $diff>0 ){
        array_push( $arr,0 );
        $diff--;
    }
    // For PHP 4.x
    $arrNumber    =    array();
    for ($i = 0; $i < $strlen; $i++) {
        $arrNumber[] = substr($number,$i,1);
    }
    // For PHP 5.x: $arrNumber    =    str_split( $number );

    $arr        =    array_merge( $arr,$arrNumber );

    return $arr;
}

function barcode($code,$number, $length=8){
    // KH, KH00000, 8;
    $number = str_replace($code,'',$number) + 1;
    $arr =  create_barcode($code, $number, $length);
    $barcode =$code;
    foreach ($arr as $digit){
        $barcode .=$digit;
    };    
    return $barcode;
}