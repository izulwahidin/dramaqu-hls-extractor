<?php
require_once(__DIR__.'/lib.php');

$url = 'https://dramaqu.day/nonton-maestra-strings-of-truth-2023-subtitle-indonesia/';

try {
    // get iframe url id
    $rawHTML = fetch($url);
    preg_match('/<iframe.*src=".*\?id=(.*?)"/', $rawHTML, $iframe);
    $iframeID = $iframe[1];

    // get response API p2p 
    $responseAPI = fetch(
        API_ENDPOINT,
        [], //headers
        'POST',
        [
            'id' => $iframeID
        ]
    );
    $responseObj = json_decode($responseAPI); // convert response to object
    // check response
    if($responseObj->status !== 200){
        throw new Exception("Error: Something wrong, please check your ID", 1);
    }

    // modify hls url to proxy
    $responseObj->file = PROXY_PATH.base64_encode($responseObj->file);

    echo json_encode($responseObj);

} catch (\Throwable $th) {
    http_response_code(500);
    $err = [
        'status' => false,
        'message' => $th->getMessage()
    ];

    echo json_encode($err);
}
