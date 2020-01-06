<?php 

namespace MoonLineApiSdk;

class LineSocialAPI{

    private $channel_id;
    private $channel_secret;

    private $access_token;
    private $refresh_token;
    private $id_token;

    function __construct($channel_id, $channel_secret){
        $this->channel_id = $channel_id;
        $this->channel_secret = $channel_secret;
    }

    public function isAccessToken(){
        if($this->access_token){
            return true;
        }else{
            return false;
        }
    }

    public function getAccessToken(){
        return $this->access_token;
    }

    public function setAccessToken($access_token){
        $this->access_token = $access_token;
    }

    public function getRefreshToken(){
        return $this->refresh_token;
    }

    public function setRefreshToken($refresh_token){
        $this->refresh_token = $refresh_token;
    }


    /**
     * アクセストークンを発行する
     * HTTPリクエスト
     * POST https://api.line.me/oauth2/v2.1/token
     *
     * レスポンス
     * ステータスコード200と以下の情報を含むJSONオブジェクトを返します。
     * 
     * プロパティ	タイプ	説明
     * access_token	String	アクセストークン。有効期間は30日です。
     * expires_in	Number	アクセストークンの有効期限が切れるまでの秒数
     * id_token	String	ユーザー情報を含むJSONウェブトークン（JWT）。このプロパティは、スコープにopenidを指定した場合にのみ返されます。IDトークンについて詳しくは、「IDトークンからプロフィール情報とメールアドレスを取得する」を参照してください。
     * refresh_token	String	新しいアクセストークンを取得するためのトークン。アクセストークンの有効期限が切れてから最長10日間有効です。
     * scope	String	ユーザーが付与する権限。詳しくは、「スコープ」を参照してください。
     * token_type	String	Bearer
     * 
     * @var string
     * @param $auth_code 認証後callback urlに付いてくるcode
     * @return array
     */
    public function grantAccessToken($auth_code, $refirect_uri){
        $method = "POST";
        $url = "https://api.line.me/oauth2/v2.1/token";

        $header = [
            'Content-Type: application/x-www-form-urlencoded',
        ];

        $data = [
            "grant_type" => "authorization_code",
            "code" => $auth_code,
            "redirect_uri" => $refirect_uri,
            "client_id" => $this->channel_id,
            "client_secret" => $this->channel_secret,
        ];

        $curl = curl_init(); // 初期化
        curl_setopt($curl, CURLOPT_URL, $url); // APIエンドポイント指定
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // curl_execの結果を文字列で返す(文字列で返さないと画面に出力されてしまう)
        $response = curl_exec($curl);
        $result = json_decode($response, true);
        curl_close($curl);

        $this->access_token = $result["access_token"];
        $this->refresh_token = $result["refresh_token"];
        $this->id_token = $result["id_token"];

        return $result;
    }

    /**
     * アクセストークンを検証する
     * 
     * GET https://api.line.me/oauth2/v2.1/verify
     * 
     * レスポンス
     * アクセストークンが有効である場合は、アクセストークンについて以下の情報を含むJSONレスポンスが返されます。
     * 
     * scope	String	アクセストークンを介して付与される権限
     * client_id	String	アクセストークンを発行する対象のチャネルID
     * expires_in	Number	アクセストークンの有効期限。APIが呼び出された時点から期限切れまでの残り秒数で表されます。
     * 
     * エラーの場合
     * "error": "invalid_request",
     * "error_description": "access token expired"
     * 
     * @return array
     */
    public function vertifyAccessToken(){
        
        $method = "GET";
        $url = "https://api.line.me/oauth2/v2.1/verify";
        
        $datas = [
            "access_token" => $this->access_token,
        ];

        $url = $url."?".http_build_query($datas);

        $curl = curl_init(); // 初期化
        curl_setopt($curl, CURLOPT_URL, $url); // APIエンドポイント指定
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // curl_execの結果を文字列で返す(文字列で返さないと画面に出力されてしまう)
        $response = curl_exec($curl);
        $result = json_decode($response, true);
        curl_close($curl);

        return $result;
    }

    /**
     * 
     * アクセストークンを更新する
     * 
     * POST https://api.line.me/oauth2/v2.1/token
     * 
     * レスポンス
     * 呼び出しが成功すると、新しいアクセストークンとリフレッシュトークンが返されます。
     * 
     * プロパティ	タイプ	説明
     * access_token	String	アクセストークン。有効期間は30日です。
     * token_type	String	Bearer
     * refresh_token	String	新しいアクセストークンを取得するためのトークン。アクセストークンの有効期限が切れてから最長10日間有効です。
     * expires_in	Number	アクセストークンの有効期限。APIが呼び出された時点から期限切れまでの残り秒数で表されます。
     * scope	String	アクセストークンを介して付与される権限
     * 
     * @return array
     */
    public function refreshAccessToken(){
        $method = "POST";
        $url = "https://api.line.me/oauth2/v2.1/token";

        $header = [
            'Content-Type: application/x-www-form-urlencoded',
        ];

        $data = [
            "grant_type" => "refresh_token",
            "refresh_token" => $this->refresh_token,
            "client_id" => $this->channel_id,
            "client_secret" => $this->channel_secret,
        ];

        $curl = curl_init(); // 初期化
        curl_setopt($curl, CURLOPT_URL, $url); // APIエンドポイント指定
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // curl_execの結果を文字列で返す(文字列で返さないと画面に出力されてしまう)
        $response = curl_exec($curl);
        $result = json_decode($response, true);
        curl_close($curl);

        $this->access_token = $result["access_token"];
        $this->refresh_token = $result["refresh_token"];

        return $result;
    }

    /**
     * 
     * ユーザーの表示名、プロフィール画像、およびステータスメッセージを取得します。
     * HTTPリクエスト
     * GET https://api.line.me/v2/profile
     *
     * リクエストヘッダー
     * Authorization    Bearer {access token}
     * 
     * レスポンス
     * displayName	String	ユーザーの表示名
     * userId	String	ユーザーID
     * pictureUrl	String	プロフィール画像のURL。スキームはhttpsです。ユーザーがプロフィール画像を設定していない場合はレスポンスに含まれません。
     * statusMessage	String	ユーザーのステータスメッセージ。ユーザーがステータスメッセージを設定していない場合はレスポンスに含まれません。
     * 
     * @return array
     */
    public function getProfile(){
     
        $method = "GET";
        $url = "https://api.line.me/v2/profile";

        $header = [
            'Authorization: Bearer '.$this->access_token,
        ];

        $curl = curl_init(); // 初期化
        curl_setopt($curl, CURLOPT_URL, $url); // APIエンドポイント指定
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // curl_execの結果を文字列で返す(文字列で返さないと画面に出力されてしまう)
        $response = curl_exec($curl);
        $result = json_decode($response, true);
        curl_close($curl);

        return $result;
    }


    /**
     * 
     * ユーザーをログアウトする
     * 
     * POST https://api.line.me/oauth2/v2.1/revoke
     * リクエストヘッダー
     * 
     * @return bool
     * 
     */
    public function logoutUser(){

        $method = "POST";
        $url = "https://api.line.me/oauth2/v2.1/revoke";

        $data = [
            "client_id" => $this->channel_id,
            "client_secret" => $this->channel_secret,
            "access_token" => $this->access_token,
        ];
        $curl = curl_init(); // 初期化
        curl_setopt($curl, CURLOPT_URL, $url); // APIエンドポイント指定
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // curl_execの結果を文字列で返す(文字列で返さないと画面に出力されてしまう)
        curl_setopt($curl, CURLOPT_HEADER, true);

        $response = curl_exec($curl);
        $status_code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        if($status_code == 200){
            return true;
        }else{
            return false;
        }
    }

}