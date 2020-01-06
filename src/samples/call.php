<?php 

    require_once "../src/AuthorizeAPI.php";
    use MoonLineApiSdk\AuthorizeAPI;

    $channel_id = "[チャネルID]";
    $callback_url = "[チャネルに登録済みのコールバックurl]";

    $obj = new AuthorizeAPI($channel_id);
    $obj->callAuthorizeCGI($redirect_url);
?>
