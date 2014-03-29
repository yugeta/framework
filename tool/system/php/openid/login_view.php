<HTML>
<HEAD>
<META http-equiv="Content-type" content="text/html; charset=shift_jis" />
<TITLE>ログイン</TITLE>
<STYLE type="text/css">
* {
	font-size: 16px;
}
</STYLE>
</HEAD>
<BODY>

<? if ( $_SESSION['id'] == "" ) { ?>
<A href="login.php?id=https%3A%2F%2Fmixi.jp"><IMG src="http://winofsql.jp/test/openid/openid_sample/login_btn002.gif" border=0></A>
<br><br>
<A href="login.php?id=https%3A%2F%2Fwww.google.com%2Faccounts%2Fo8%2Fid">Google でログイン</A>
<br><br>
<A href="login.php?id=https%3A%2F%2Fme.yahoo.co.jp">Yahoo でログイン</A>
<? } ?>

</BODY>
</HTML>
