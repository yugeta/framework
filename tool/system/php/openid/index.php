<?
session_start();
header( "Content-Type: text/html; Charset=shift_jis" );
header( "Expires: Wed, 31 May 2000 14:59:58 GMT" );

if ( $_GET['logout'] != "" ) {
	$_SESSION['id'] = "";
	$_SESSION['nickname'] = "";
	$_SESSION['email'] = "";
	$_SESSION['dispname'] = "";
}

if ( $_SESSION['id'] == "" ) {
	// エラーメッセージを表示
	ini_set( 'display_errors', "1" );
	
	// ログインされていないので、ログインページを表示
	require_once( 'login_view.php' );


	exit();
}

?>
<HTML>
<HEAD>
<META http-equiv="Content-type" content="text/html; charset=shift_jis" />
<TITLE>ようこそ</TITLE>
<STYLE type="text/css">
* {
	font-size: 30px;
}
</STYLE>
<SCRIPT language="javascript" type="text/javascript">

</SCRIPT>
</HEAD>
<BODY>

<?
	if ( $_SESSION['nickname'] != '' ) {
		print "ようこそ {$_SESSION['nickname']} さん<br>";
	}
	if ( $_SESSION['email'] != '' ) {
		print "email : {$_SESSION['email']}<br>";
	}
	if ( $_SESSION['dispname'] != '' ) {
		print "表示名 : {$_SESSION['dispname']}<br>";
	}

?>
<br><br>
ID: <?= $_SESSION['id'] ?>

<FORM>
<INPUT type=submit name=logout value="ログアウト">
</FORM>
</BODY>
</HTML>
