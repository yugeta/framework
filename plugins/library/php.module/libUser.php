<?php

class libUser extends fw_define{

	public $user_file = "data/config/users.json";

	function setUser(){

		//分岐
		if(isset($_REQUEST['mode'])){
			if($_REQUEST['mode']=="edit"){
				$this->setUserEdit($_REQUEST["mode"]);
				$libUrl = new libUrl();
				header("Location: ".$libUrl->getUrl()."?menu=user");
				exit;
			}
			else if($_REQUEST["mode"]=="add"){
				$this->setUserEdit($_REQUEST["mode"]);
				$libUrl = new libUrl();
				header("Location: ".$libUrl->getUrl());
				exit;
			}
		}

		//GLOBAL-set
		if(!isset($_SESSION[$this->session_name])){return;}

		$user = $_SESSION[$this->session_name]['id'];
		if(!$user){return;}

		$user_file = $this->user_file;
		if(!is_file($user_file)){return;}

		$datas = explode("\n",file_get_contents($user_file));
		for($i=count($datas)-1;$i>=0;$i--){
			if(!$datas[$i]){continue;}
			$json = json_decode($datas[$i],true);
			if($json['id']==$user){
				$GLOBALS['user'] = $json;
				break;
			}
		}

	}

	function setUserEdit($mode=""){

		$date = date("YmdHis");
		$user_file = $this->user_file;

		$libAuth = new libAuth();
		$data = $libAuth->checkUser($_REQUEST["user"]["id"]);

		//addの時は、重複チェック
		if($mode=="add"){
			if($data["id"]==$_REQUEST["user"]["id"]){
				$libErr = new libErr();
				$libErr->view("アカウント名が既に登録されています。");
			}
			$data["entry"] = $date;
		}

		//$data = $_REQUEST["user"];
		foreach($_REQUEST["user"] as $key=>$val){
			$data[$key] = $val;
		}
		$data["update"] = $date;

		//password-change
		if($_REQUEST["login_pw"]){
			$data["md5"] = md5($_REQUEST["login_pw"]);
		}

		$json = json_encode($data);
		file_put_contents($user_file,$json."\n",FILE_APPEND);
	}


}
