<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function sendmail($name,$from,$to,$subject,$message){        
    $mess =$message;
    $contact_name = "=?UTF-8?B?".base64_encode($name).'?=';
    $headers = "From: ".$contact_name." <".$from.">\n";
    $headers .= "Reply-To: ".$contact_name." <".$from.">\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: text/html; charset=\"utf-8\"\n";
    return @mail( $to, "=?UTF-8?B?".base64_encode($subject).'?=', $mess, $headers );          
}

function send($to,$subject,$message,$contact_name,$contact_email){
    $CI = get_instance();          
    $contact_name = "=?UTF-8?B?".base64_encode($contact_name).'?=';
    $headers = "From: ".$contact_name." <".$contact_email.">\n";
    $headers .= "Reply-To: ".$contact_name." <".$contact_email.">\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\n";
    return @mail( $to, "=?UTF-8?B?".base64_encode($subject).'?=', $message, $headers );          
}

function sendshare($nguoigui,$emailnguoigui,$emailnguoinhan,$subject,$message){
    $CI = get_instance();          
    $mess =$message;
    $nguoigui = "=?UTF-8?B?".base64_encode($nguoigui).'?='; 
    $headers = "From: ".$nguoigui." <".$emailnguoigui.">\n";
    $headers .= "Reply-To: ".$nguoigui." <".$emailnguoigui.">\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\n";
    return @mail( $emailnguoinhan, "=?UTF-8?B?".base64_encode($subject).'?=', $mess, $headers );          
}      
function info_send_mail(){
    $CI = get_instance();
    $CI->load->config('config_contact');
    $mess = '<div>----------------------------</div>';
    $mess .='<div><b>'.$CI->config->item('contact_name').'</b></div>';
    $mess .='<div><b>Địa chỉ:</b> '.$CI->config->item('contact_address').'</div>';
    $mess .='<div><b>Điện thoại:</b> '.$CI->config->item('contact_phone').' - '.$CI->config->item('contact_mobile').'</div>';
    $mess .='<div><b>Email:</b> '.$CI->config->item('contact_email').'</div>';
    $mess .='<div><b>Fax:</b> '.$CI->config->item('contact_fax').'</div>';
    return $mess;
}  