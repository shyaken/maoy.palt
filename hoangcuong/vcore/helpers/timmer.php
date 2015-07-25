<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Timer Start
if(!function_exists('timer_start')){
    function timer_start() {
        global $timestart;
        $timestart = microtime( true );
        return true;
    }
}

// Timer Stop
if(!function_exists('timer_stop')){
    function timer_stop( $display = 0, $precision = 3 ) { // if called like timer_stop(1), will echo $timetotal
        global $timestart, $timeend;
        $timeend = microtime( true );
        $timetotal = $timeend - $timestart;
        $r = ( function_exists( 'number_format_i18n' ) ) ? number_format_i18n( $timetotal, $precision ) : number_format( $timetotal, $precision );
        if ( $display )
            echo $r;
        return $r;
    }
}