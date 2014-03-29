<?php
// *********************************************************
// �f�o�b�O�p���O�t�@�C���̈ʒu
// ( �R�����g�ɂ���ƁA���O�͏o�͂���܂��� )
// *********************************************************
$logfile = "./debug.log";
// *********************************************************
// �ؖ����̈ʒu
// *********************************************************
$openid_pem = realpath("./cacert.pem");
// *********************************************************
// ��ƃf�B���N�g���̈ʒu
// *********************************************************
$store_path = realpath("../") . DIRECTORY_SEPARATOR  . "_php_consumer_dir";
if (!file_exists($store_path) && !mkdir($store_path)) {
	print "�ۑ��p�f�B���N�g�����쐬�ł��܂���ł��� '$store_path'".
	" �������݌������`�F�b�N���ĉ�����";
	exit(0);
}

// *********************************************************
// OpenID �p URL ������
// *********************************************************
$scheme = 'http';
if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
	$scheme .= 's';
}

// �߂��Ă��������󂯎��ꏊ
$return_to = "return.php";
$return_to = 
	sprintf("%s://%s:%s%s/$return_to",
		$scheme, $_SERVER['SERVER_NAME'],
		$_SERVER['SERVER_PORT'],
		dirname($_SERVER['PHP_SELF'])
	);

// �Ăяo�����̃f�B���N�g��
$trust_root = 
	sprintf("%s://%s:%s%s/",
		$scheme, $_SERVER['SERVER_NAME'],
		$_SERVER['SERVER_PORT'],
		dirname($_SERVER['PHP_SELF'])
	);

// *********************************************************
// HTTP �w�b�_
// *********************************************************
header( "Content-Type: text/html; Charset=shift_jis" );
header( "Expires: Wed, 31 May 2000 14:59:58 GMT" );

// *********************************************************
// �f�o�b�O���O�J�n�ʒu
// *********************************************************
log_file("+++++++++++++++++++");
log_file("openid_pem=$openid_pem");
log_file("store_path=$store_path");
log_file("scheme=$scheme");
log_file("return_to=$return_to");
log_file("trust_root=$trust_root");

// *********************************************************
// Windows ���ƃ����_���v�f(/dev/urandom)�̑Ή�
// *********************************************************
if ( substr( strtoupper( php_uname("s") ), 0, 7 ) == 'WINDOWS' ) {

	log_file("window�ł�");

	define('Auth_OpenID_RAND_SOURCE', NULL);
	if ( !extension_loaded( "curl" ) ) {
		log_file("php_curl.dll load");
//		dl("php_curl.dll"); �񐄏��܂��͒�`����Ȃ��֐�
		exit("curl ���g�p�ł��܂���");
	}
	if ( !extension_loaded( "openssl" ) ) {
		log_file("php_openssl.dll load");
//		dl("php_openssl.dll"); �񐄏��܂��͒�`����Ȃ��֐�
		exit("openssl ���g�p�ł��܂���");
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
// include_path �ɁAPHP OpenID Library �̈ʒu���Z�b�g
// *********************************************************
$path_extra = realpath("../openid2");
$path = ini_get('include_path');
$path = $path_extra . PATH_SEPARATOR . $path;
ini_set('include_path', $path);

// *********************************************************
// PHP OpenID Library ( ���p�� )
// *********************************************************
require_once "Auth/OpenID/Consumer.php";
require_once "Auth/OpenID/FileStore.php";
require_once "Auth/OpenID/SReg.php";
require_once "Auth/OpenID/PAPE.php";
require_once "Auth/OpenID/AX.php";


// *********************************************************
// �f�o�b�O���O
// *********************************************************
function log_file($message) {
	if ( $GLOBALS['logfile'] != "" ) {
		error_log("$message\n", 3, $GLOBALS['logfile']);
	}
}

?>
