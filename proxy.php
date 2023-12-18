<?php
require_once(__DIR__.'/lib.php');
// $_GET['id'] = 'aHR0cHM6Ly9ycjIxMTAtLS0weTFtenFseTFuZ2RlLS12cDNuMTFqMmR2Nmd1Z2h3dDc5Z2RtYXNrLjk5cmVzZXAuaW5mby9oZC9zZXJ2ZXIxL0tmb1NSMW1DeC0xZXZ5c3l4WVJWekEvMTcwMzAwNTIwMC85LzFiMDlkNTMxNzQ0MTZjZWI4OTI2OWNiMTgwNTUxNmMxL2luZGV4Lm0zdTg=';
try {
    if(!isset($_GET['id']) && empty($_GET['id'])){
        throw new Exception("What are you doing stepbro?");
    }

    $url = base64_decode($_GET['id']);

    // get m3u8 file
    $m3u8Index = fetch(
        $url,
        [
            "Origin: ".ORIGIN
        ]
    );

    // modify m3u8 to proxy
    $m3u8IndexMod = modifyHLSdata($m3u8Index);
    // return modified m3u8
    echo $m3u8IndexMod;
} catch (\Throwable $th) {
    http_response_code(500);
    echo $th->getMessage();
}