<?php 

    require_once "../src/LineSocialAPI.php";
    use MoonLineApiSdk\LineSocialAPI;

    $access_token = "[アクセストークン]";

    $obj = new LineSocialAPI($channel_id, $channel_secret);
    $obj->setAccessToken($access_token);
    echo "<pre>";
    var_dump($obj->vertifyAccessToken($access_token));
    echo "</pre>";

?>