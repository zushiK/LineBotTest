<?php 

namespace MoonLineApiSdk;

class AuthorizeAPI{

    private $endpoint_url = "https://access.line.me/oauth2/v2.1/authorize";
    private $method = "GET";
    private $scope = ["openid","profile"/*,"email"*/];

    private $channelId;


    /**
     * @var string
     * @param LineDeveloper チャネルID
     */
    function __construct($channelId){
        $this->channelId = $channelId;
    }

    /**
     * @var string
     * 
     * LINEから認可用のCGIを呼び出します。
     * 呼び出しに失敗した時はエラー400のCGI画面が帰ってきます。
     * 
     */
    public function callAuthorizeCGI($redirect_url){

        $param_datas = [
            "response_type" => "code",
            "client_id" => $this->channelId,
            "redirect_uri" => $redirect_url,
            "state" => $this->random(16),
            "scope" => implode($this->scope," "),

            // "nonce" =>  "",
            // "prompt" => "",
            // "max_age" => "",
            // "ui_locales" => "",
            // "bot_prompt" => "",
        ];
        $url = $this->endpoint_url."?".http_build_query($param_datas);

        header('Location:'.$url);
        exit;
    }

    private function random($length = 16)
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
}