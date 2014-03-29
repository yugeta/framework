<HTML>
<HEAD>
<META http-equiv="Content-type" content="text/html; charset=shift_jis" />
<TITLE>ログイン</TITLE>
<STYLE type="text/css">
* {
	font-size: 12px;
}
</STYLE>
</HEAD>
<BODY>

<A href="login.php?id=https%3A%2F%2Fmixi.jp"><IMG src="http://winofsql.jp/test/openid/openid_sample/login_btn002.gif" border=0></A>
<br>
<B><?= $error_message ?></B>

<? if ( $error_message == '' ) { ?>
<SCRIPT type="text/javascript">
	window.location = "index.php";
</SCRIPT>
<? } ?>
</BODY>
</HTML>
