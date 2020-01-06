<?php 

    require_once "../src/LineSocialAPI.php";
    use MoonLineApiSdk\LineSocialAPI;

    $channel_id = "[チャネルID]";
    $channel_secret = "[チャネルシークレット]";
    $callback_url = "[チャネルに登録済みのコールバックurl]";
    
    $auth_code = $_GET["code"];
    $user_status = $_GET["state"]; //call時に投げた値。APIレスポンスが正しいものが帰ってきているか判断するために使う
    
    $obj = new LineSocialAPI($channel_id, $channel_secret);
    $token_obj = $obj->grantAccessToken($auth_code, $callback_url);

    // アクセストークン取得
    var_dump($token_obj);

    // そのままプロフィールを取得
    echo "<pre>";
    var_dump($obj->getProfile());
    echo "</pre>";
