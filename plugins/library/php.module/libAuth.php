<?php

/**
* テンプレート処理
*
* 概要
* <%パターン:命令:値-1%>
*
* パターン
* - class
*  + 　　<%class:***:%%%(---)%>
*  + 　　param:*** class名
*  + 　　param:%%% function
*  + 　　param:--- 受け渡し値
*/

class libAuth extends fw_define{

	function fw_auth(){

		$libOpenid = new libOpenid();
		$libOpenid->openid_1_0("google");
		//return true;

	}



}
