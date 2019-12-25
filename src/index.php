<?php
DEFINE("ACCESS_TOKEN","aNtAFl9JpEJbqlCwZGLPZLivaUsNDz2UBqsYI6xTTGsQaqLQJXl85HjAjq57daphx0ax1I2AI57dlawJtUAvFAUACU2nbTXYsTWNjmNXfUUSiXQDY1KBzIddZrNI8TgZWhrL119kGLi1FySZRE+NfAdB04t89/1O/w1cDnyilFU=");
DEFINE("SECRET_TOKEN","d78b09078bb5f637091fe5a5a6004278

");

use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\Constant\HTTPHeader;

//LINESDKの読み込み
require_once(__DIR__."/vendor/autoload.php");

//LINEから送られてきたらtrueになる
if(isset($_SERVER["HTTP_".HTTPHeader::LINE_SIGNATURE])){

//LINEBOTにPOSTで送られてきた生データの取得
  $inputData = file_get_contents("php://input");

//LINEBOTSDKの設定
  $httpClient = new CurlHTTPClient(ACCESS_TOKEN);
  $Bot = new LINEBot($HttpClient, ['channelSecret' => SECRET_TOKEN]);
  $signature = $_SERVER["HTTP_".HTTPHeader::LINE_SIGNATURE]; 
  $Events = $Bot->parseEventRequest($InputData, $Signature);

//大量にメッセージが送られると複数分のデータが同時に送られてくるため、foreachをしている。
    foreach($Events as $event){
        $SendMessage = new MultiMessageBuilder();
        $TextMessageBuilder = new TextMessageBuilder("よろぽん！");
        $SendMessage->add($TextMessageBuilder);
        $Bot->replyMessage($event->getReplyToken(), $SendMessage);
    }
}