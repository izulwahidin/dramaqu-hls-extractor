<?php
define('PROXY_PATH', '/proxy.php?id=');
define('ORIGIN', "https://m3irr6ty3exncsokt2i2kie55vxi4ohn.gugcloud.club"); // change this if the endpoint url changed
define('API_ENDPOINT', ORIGIN."/api/p2p"); // change this if the endpoint url changed

function fetch($url, $headers = [], $method = 'GET', $body = []){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if(strtolower($method) == 'post'){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
    }

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        throw new Exception('Error:' . curl_error($ch));
    }
    curl_close($ch);

    return $result;
}

function modifyHLSdata($str){
    return preg_replace_callback('/(\b(https?|ftp):\/\/[^\s\/$.?#].[^\s]*\b)/', function($matches) {
        $base64_encoded = base64_encode($matches[1]);
        return PROXY_PATH . $base64_encoded;
    }, $str);
}