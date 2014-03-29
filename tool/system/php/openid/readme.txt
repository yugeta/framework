**************************************************
 1) 
**************************************************

http://www.janrain.com/openid-enabled

上記の URL からライブラリをダウンロードして、openid2/Auth/OpenID.php と
なるように配置します。


[openid_sample]
    common.php
    その他のファイル

[openid2]
    [Auth]
        [OpenID]
        [Yadis]
        OpenID.php


**************************************************
 2)
**************************************************

Auth/Yadis/ParanoidHTTPFetcher.php を以下のように変更します
( 2箇所ある、curl_exec($c); の直前に以下を記述 )

if ($this->isHTTPS($url)) {
    curl_setopt($c, CURLOPT_CAINFO, $GLOBALS['openid_pem']);
}

**************************************************
 3) 
**************************************************

OpenID.php のログ処理を以下のように書き換えます

    static function log($format_string)
    {
        $args = func_get_args();
        $message = call_user_func_array('sprintf', $args);
//      error_log($message);
        if ( $GLOBALS['logfile'] != "" ) {
            error_log("$message\n", 3, $GLOBALS['logfile']);
        }
    }

**************************************************
 4)
**************************************************

http://curl.haxx.se/docs/caextract.html から、cacert.pem をダウンロード
して、openid_sample フォルダ内に置きます。


**************************************************
 5) windows のみ
**************************************************

windows では、php.ini の extension=php_curl.dll と
extension=php_openssl.dll を有効にします



■著作権

このプログラムはフリーです。どうぞ自由に御使用ください。
著作権は作者である私(lightbox)が保有しています。
また、本ソフトを運用した結果については、作者は一切責任を
負えせんのでご了承ください。
