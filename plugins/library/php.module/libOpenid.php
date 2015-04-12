<?php

class libOpenid extends fw_define{

	function getJsonOpenid(){echo $path;
		$path = $this->define_plugins."/".$this->define_library."/data/openid.json";
		//echo $path;
		//echo file_get_contents($path),true;
		if(!is_file($path)){return;}
		return json_decode(file_get_contents($path),true);
	}

	//open-idタイプ別リクエスト処理
	function openid_1_0($service){

		if(!$service){return;}

		//$session_id = session_id();
		//if(!$session_id){return;}

		$openid_json = $this->getJsonOpenid();//print_r($openid_json);
		if(!$openid_json || !isset($openid_json[$service])){return;}

		//$openid_url = $GLOBALS['system']['openid'][$service]['url'];
		$openid_url = $openid_json[$service]['url'];
		//die($openid_url);

		$url = new libUrl();
		$mysite = $url->getUrl();

		$data = array(
			'openid.ns'				=> 'http://specs.openid.net/auth/1.0',
			'openid.ns.pape'		=> 'http://specs.openid.net/extensions/pape/1.0',
			'openid.ns.max_auth_age'=> '300',
			'openid.claimed_id'		=> 'http://specs.openid.net/auth/1.0/identifier_select',
			'openid.identity'		=> 'http://specs.openid.net/auth/1.0/identifier_select',
			'openid.return_to'		=> $mysite.'?page=openid&service='.$service.'&action=return&session_id='.$session_id."&check=".$check."&cookie_time=".$_REQUEST['cookie_time'],
			'openid.realm'			=> $mysite,
			'openid.mode'			=> 'checkid_setup',
			'openid.ui.ns'			=> 'http://specs.openid.net/extensions/ui/1.0',
			'openid.ui.mode'		=> '=popup',
			'openid.ui.icon'		=> 'true',
			'openid.ns.ax'			=> 'http://openid.net/srv/ax/1.0',
			'openid.ax.mode'		=> 'fetch_request',
			'openid.ax.type.email'	=> 'http://axschema.org/contact/email',
			'openid.ax.type.guid'	=> 'http://schemas.openid.net/ax/api/user_id',
			'openid.ax.type.language'=>'http://axschema.org/pref/language',
			'openid.ax.required'	=> 'email,guid,language'
		);

		foreach($data as $key=>$val){
			$q[] = $key."=".urlencode($val);
		}

		//separate
		$separate_value = "?";
		if(preg_match("/".$separate_value."/",$openid_url)){
			$separate_value = "&";
		}

		//サイトへ移動
		header("Location: ".$openid_url.$separate_value.join("&",$q));
	}
	function openid_2_0($service){

		//http://192.168.33.12/tools/login/
		//$session_id = session_id();
		//if(!$session_id){return;}
		$openid_json = $this->getJsonOpenid();//print_r($openid_json);
		if(!$openid_json || !isset($openid_json[$service])){return;}

		//$openid_url = $GLOBALS['system']['openid'][$service]['url'];
		$openid_url = $openid_json[$service]['url'];

		$url = new libUrl();
		$mysite = $url->getUrl();
		//$mysite = "http://wordpress.ideacompo.com/";
		$session_id = "test";
		$check = "-";
		$cookie_time = $_REQUEST['cookie_time'];

		$data=array(
			'openid.ns'				=> 'http://specs.openid.net/auth/2.0',
			'openid.ns.pape'		=> 'http://specs.openid.net/extensions/pape/1.0',
			'openid.ns.max_auth_age'=> '300',
			'openid.claimed_id'		=> 'http://specs.openid.net/auth/2.0/identifier_select',
			'openid.identity'		=> 'http://specs.openid.net/auth/2.0/identifier_select',
			'openid.return_to'		=> $mysite.'?page=openid&service='.$service.'&action=return&session_id='.$session_id."&check=".$check."&cookie_time=".$cookie_time,
			'openid.realm'			=> $mysite,
			'openid.mode'			=> 'checkid_setup',
			'openid.ui.ns'			=> 'http://specs.openid.net/extensions/ui/1.0',
			'openid.ui.mode'		=> '=popup',
			'openid.ui.icon'		=> 'true',
			'openid.ns.ax'			=> 'http://openid.net/srv/ax/1.0',
			'openid.ax.mode'		=> 'fetch_request',
			'openid.ax.type.email'	=> 'http://axschema.org/contact/email',
			'openid.ax.type.guid'	=> 'http://schemas.openid.net/ax/api/user_id',
			'openid.ax.type.language'=>'http://axschema.org/pref/language',
			'openid.ax.required'	=> 'email,guid,language'
		);

		foreach($data as $key=>$val){
			$q[] = $key."=".urlencode($val);
		}

		//separate
		$separate_value = "?";
		if(preg_match("/".$separate_value."/",$openid_url)){
			$separate_value = "&";
		}

		//サイトへ移動
		//print_r($data);exit();
		//die($openid_url.$separate_value.join("&",$q));
		header("Location: ".$openid_url.$separate_value.join("&",$q));
	}

}
