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
	// �G���[���b�Z�[�W��\��
	ini_set( 'display_errors', "1" );
	
	// ���O�C������Ă��Ȃ��̂ŁA���O�C���y�[�W��\��
	require_once( 'login_view.php' );


	exit();
}

?>
<HTML>
<HEAD>
<META http-equiv="Content-type" content="text/html; charset=shift_jis" />
<TITLE>�悤����</TITLE>
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
		print "�悤���� {$_SESSION['nickname']} ����<br>";
	}
	if ( $_SESSION['email'] != '' ) {
		print "email : {$_SESSION['email']}<br>";
	}
	if ( $_SESSION['dispname'] != '' ) {
		print "�\���� : {$_SESSION['dispname']}<br>";
	}

?>
<br><br>
ID: <?= $_SESSION['id'] ?>

<FORM>
<INPUT type=submit name=logout value="���O�A�E�g">
</FORM>
</BODY>
</HTML>
