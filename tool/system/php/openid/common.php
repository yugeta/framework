<?php
// *********************************************************
// デバッグ用ログファイルの位置
// ( コメントにすると、ログは出力されません )
// *********************************************************
$logfile = "./debug.log";
// *********************************************************
// 証明書の位置
// *********************************************************
$openid_pem = realpath("./cacert.pem");
// *********************************************************
// 作業ディレクトリの位置
// *********************************************************
$store_path = realpath("../") . DIRECTORY_SEPARATOR  . "_php_consumer_dir";
if (!file_exists($store_path) && !mkdir($store_path)) {
	print "保存用ディレクトリを作成できませんでした '$store_path'".
	" 書き込み権限をチェックして下さい";
	exit(0);
}

// *********************************************************
// OpenID 用 URL 文字列
// *********************************************************
$scheme = 'http';
if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
	$scheme .= 's';
}

// 戻ってきた情報を受け取る場所
$return_to = "return.php";
$return_to = 
	sprintf("%s://%s:%s%s/$return_to",
		$scheme, $_SERVER['SERVER_NAME'],
		$_SERVER['SERVER_PORT'],
		dirname($_SERVER['PHP_SELF'])
	);

// 呼び出し元のディレクトリ
$trust_root = 
	sprintf("%s://%s:%s%s/",
		$scheme, $_SERVER['SERVER_NAME'],
		$_SERVER['SERVER_PORT'],
		dirname($_SERVER['PHP_SELF'])
	);

// *********************************************************
// HTTP ヘッダ
// *********************************************************
header( "Content-Type: text/html; Charset=shift_jis" );
header( "Expires: Wed, 31 May 2000 14:59:58 GMT" );

// *********************************************************
// デバッグログ開始位置
// *********************************************************
log_file("+++++++++++++++++++");
log_file("openid_pem=$openid_pem");
log_file("store_path=$store_path");
log_file("scheme=$scheme");
log_file("return_to=$return_to");
log_file("trust_root=$trust_root");

// *********************************************************
// Windows 環境とランダム要素(/dev/urandom)の対応
// *********************************************************
if ( substr( strtoupper( php_uname("s") ), 0, 7 ) == 'WINDOWS' ) {

	log_file("windowです");

	define('Auth_OpenID_RAND_SOURCE', NULL);
	if ( !extension_loaded( "curl" ) ) {
		log_file("php_curl.dll load");
//		dl("php_curl.dll"); 非推奨または定義されない関数
		exit("curl を使用できません");
	}
	if ( !extension_loaded( "openssl" ) ) {
		log_file("php_openssl.dll load");
//		dl("php_openssl.dll"); 非推奨または定義されない関数
		exit("openssl を使用できません");
	}
}
else {
	if ( @is_readable('/dev/urandom') ) {
	}
	else {
		define('Auth_OpenID_RAND_SOURCE', NULL);
	}
}

// *********************************************************
// include_path に、PHP OpenID Library の位置をセット
// *********************************************************
$path_extra = realpath("../openid2");
$path = ini_get('include_path');
$path = $path_extra . PATH_SEPARATOR . $path;
ini_set('include_path', $path);

// *********************************************************
// PHP OpenID Library ( 利用側 )
// *********************************************************
require_once "Auth/OpenID/Consumer.php";
require_once "Auth/OpenID/FileStore.php";
require_once "Auth/OpenID/SReg.php";
require_once "Auth/OpenID/PAPE.php";
require_once "Auth/OpenID/AX.php";


// *********************************************************
// デバッグログ
// *********************************************************
function log_file($message) {
	if ( $GLOBALS['logfile'] != "" ) {
		error_log("$message\n", 3, $GLOBALS['logfile']);
	}
}

?>
