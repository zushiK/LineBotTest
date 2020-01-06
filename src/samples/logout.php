<?php 

    require_once "../src/LineSocialAPI.php";
    use MoonLineApiSdk\LineSocialAPI;

    $channel_id = "[チャネルID]";
    $channel_secret = "[チャネルシークレット]";
    $access_token = "[アクセストークン]";

    $obj = new LineSocialAPI($channel_id, $channel_secret);
    $obj->setAccessToken($access_token);

    echo "<pre>";
    var_dump($obj->logoutUser());
    echo "</pre>";
?>