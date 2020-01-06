<?php 

    require_once "../src/LineSocialAPI.php";
    use MoonLineApiSdk\LineSocialAPI;

    $refresh_token = "[リフレッシュトークン]";

    $channel_id = "[チャネルID]";
    $channel_secret = "[チャネルシークレット]";

    $obj = new LineSocialAPI($channel_id, $channel_secret);
    $obj->setRefreshToken($refresh_token);
    echo "<pre>";
    var_dump($obj->refreshAccessToken());
    echo "</pre>";

?>