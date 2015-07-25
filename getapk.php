<?php
header('Content-Type: text/html; charset=utf-8');
function getUrls($string) {
    $regex = '/https?\:\/\/download[^\" ]+/i';
    preg_match_all($regex, $string, $matches);
    //return (array_reverse($matches[0]));
    return ($matches[0]);
}
if(isset($_GET['url']) && $_GET['url']){
    $url = $_GET['url'];
    $url1 = "http://download.freeapk.ru/?package=".$url;
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url1,
        CURLOPT_USERAGENT => 'get apk file'
    ));
    $resp = curl_exec($curl);
    $urls = getUrls($resp);
    $downloadLink = $urls[1];
	if(empty($downloadLink)){
	$urlapk = $url;
	header("Location: $urlapk");
	}
	else if ($downloadLink == "http://download.freeapk.ru.com"){
	$urlapk = $url;
	header("Location: $urlapk");
	}
	else{
	header("Location: $downloadLink");
	}
}
?>