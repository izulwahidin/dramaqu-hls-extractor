<?php
define('PROXY_PATH', '/proxy.php?id=');
define('ORIGIN', "https://m3irr6ty3exncsokt2i2kie55vxi4ohn.gugcloud.club"); // change this if the endpoint url changed
define('API_ENDPOINT', ORIGIN."/api/p2p"); // change this if the endpoint url changed

header('Access-Control-Allow-Origin: *'); //allow all cors origin


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
    $response_headers = [];
    curl_setopt($ch, CURLOPT_HEADERFUNCTION,
        function($curl, $header) use (&$response_headers) {
            $len = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) < 2) // ignore invalid headers
            return $len;
            $response_headers[strtolower(trim($header[0]))][] = trim($header[1]);
            return $len;
        }
    );

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        throw new Exception('Error:' . curl_error($ch));
    }
    
    curl_close($ch);
    http_response_code($response_code);
    foreach($response_headers as $name => $values){
        foreach($values as $value){
            header("$name: $value", false);
        }
    }

    return $result;
}

function modifyHLSdata($str){
    return preg_replace_callback('/(\b(https?|ftp):\/\/[^\s\/$.?#].[^\s]*\b)/', function($matches) {
        $base64_encoded = base64_encode($matches[1]);
        return PROXY_PATH . rtrim($base64_encoded,'=') . '.eot';
    }, $str);
}
