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

		//ログアウトチェック
		if($_REQUEST['mode']=="logout"){
			unset($_SESSION[$this->session_name]);
			$libUrl = new libUrl();
			header("Location: ".$libUrl->getUrl());
			exit();
		}

		//ログイン済みチェック
		if($_SESSION[$this->session_name]){
			return true;
		}

		//認証処理
		$user = $this->checkUser($_REQUEST['login_id']);
		if(!$user || !$user['id']){return;}

		//認証（MD5）
		if(md5($_REQUEST['login_pw'])===$user['md5']){
			$this->setSession($user);
			return true;
		}

	}


	function checkUser($user){
		if(!$user){return;}
		$users_json = "data/config/users.json";
		if(!is_file($users_json)){return;}
		$datas = explode("\n",file_get_contents($users_json));
		unset($id_flg,$user_data);
		for($i=count($datas);$i>=0;$i--){
			if(!$datas[$i]){continue;}
			$json = json_decode($datas[$i],true);
			if($json['id']!=$user){continue;}
			if($id_flg){continue;}
			$id_flg++;
			$user_data = $json;
		}
		return $user_data;
	}

	function checkUserAwk($user){
		$users_csv = "data/config/users.csv";
		if(!is_file($users_csv)){return;}
		$cmd = "awk -F, 'BEGIN{num=0}{if($0 && $2 && $2==\"".$user."\"){USER[num]=$0;num++;}}END{split(USER[num-1],data,\",\");if(data[1]!=\"1\"){print data[3];}}' ".$users_csv;
		unset($res);
		exec($cmd,$res);
		return $res;
	}

	//session
	function setSession($user){

		//$session_name = $this->session_name;
		$_SESSION[$this->session_name] = $user;

	}


}
