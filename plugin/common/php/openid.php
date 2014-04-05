<?

class OPENID{
	
	/*==========
	サイト別認証処理
	==========*/
	function gmail($session_id=null){
		
		if(!$session_id){return;}
		
		$this->specs("gmail","https://www.google.com/accounts/o8/ud",$session_id,$_REQUEST['check'],$_REQUEST['cookie_time']);
		//$this->specs("gmail","https://www.google.com/accounts/o8/ud",$session_id,1,$_REQUEST['cookie_time']);
	}
	function yahoo($session_id=null){
		
		if(!$session_id){return;}
		
		$this->specs_20("yahoo","https://login.yahoo.co.jp/config/login",$session_id,$_REQUEST['check'],$_REQUEST['cookie_time']);
		
	}
	
	function mixi($session_id=null){
		
		if(!$session_id){return;}
		
		$this->specs("mixi","https://mixi.jp/openid_server.pl",$session_id,$_REQUEST['check'],$_REQUEST['cookie_time']);
		//$this->specs("mixi","https://mixi.jp/openid_server.pl",$session_id,1,$_REQUEST['cookie_time']);
	}
	
	//open-idデフォルト
	function specs($service,$openid_url,$session_id,$check,$cookie_time){
		
		if(!$session_id){return;}
		
		$url = new URL();
		
		$mysite = $url->getUrl();
		
		$data=array(
			'openid.ns'				=> 'http://specs.openid.net/auth/2.0',
			'openid.ns.pape'		=> 'http://specs.openid.net/extensions/pape/1.0',
			'openid.ns.max_auth_age'=> '300',
			'openid.claimed_id'		=> 'http://specs.openid.net/auth/2.0/identifier_select',
			'openid.identity'		=> 'http://specs.openid.net/auth/2.0/identifier_select',
			'openid.return_to'		=> $mysite.'?m=openid&service='.$service.'&action=return&session_id='.$session_id."&check=".$check."&cookie_time=".$cookie_time,
			'openid.realm'			=> $mysite,
			'openid.mode'			=> 'checkid_setup',
			'openid.ui.ns'			=> 'http://specs.openid.net/extensions/ui/1.0',
			'openid.ui.mode'		=> '=popup',
			'openid.ui.icon'		=> 'true',
			'openid.ns.ax'			=> 'http://openid.net/srv/ax/1.0',
			'openid.ax.mode'		=> 'fetch_request',
			'openid.ax.type.email'	=> 'http://axschema.org/contact/email',
			'openid.ax.type.language'=> 'http://axschema.org/pref/language',
			'openid.ax.required'	=> 'email,language'
		);
		
		//key->get
		/*
		$keys = array_keys($data);
		for($i=0,$c=count($keys);$i<$c;$i++){
			$q[] = $keys[$i]."=".urlencode($data[$keys[$i]]);
		}
		*/
		
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
	//open-idデフォルト
	function specs_20($service,$openid_url,$session_id,$check,$cookie_time){
		
		if(!$session_id){return;}
		
		$url = new URL();
		
		$mysite = $url->getUrl();
		
		$data=array(
			'openid.ns'				=> 'http://specs.openid.net/auth/2.0',
			'openid.ns.pape'		=> 'http://specs.openid.net/extensions/pape/1.0',
			'openid.ns.max_auth_age'=> '300',
			'openid.claimed_id'		=> 'http://specs.openid.net/auth/2.0/identifier_select',
			'openid.identity'		=> 'http://specs.openid.net/auth/2.0/identifier_select',
			'openid.return_to'		=> $mysite.'?m=openid&service='.$service.'&action=return&session_id='.$session_id."&check=".$check."&cookie_time=".$cookie_time,
			'openid.realm'			=> $mysite,
			'openid.mode'			=> 'checkid_setup',
			'openid.ui.ns'			=> 'http://specs.openid.net/extensions/ui/1.0',
			'openid.ui.mode'		=> '=popup',
			'openid.ui.icon'		=> 'true',
			'openid.ns.ax'			=> 'http://openid.net/srv/ax/1.0',
			'openid.ax.mode'		=> 'fetch_request',
			'openid.ax.type.email'	=> 'http://axschema.org/contact/email',
			'openid.ax.type.language'=> 'http://axschema.org/pref/language',
			'openid.ax.required'	=> 'email,language'
		);
		
		//key->get
		/*
		$keys = array_keys($data);
		for($i=0,$c=count($keys);$i<$c;$i++){
			$q[] = $keys[$i]."=".urlencode($data[$keys[$i]]);
		}
		*/
		
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
	
	
	//認証後に対象OPENID別に返り値を取得する。
	function getReturnData($service,$mode){
		
		if($service=="gmail"){
			if($mode=="id"){
				return $_REQUEST['openid_ext1_value_email'];
			}
			else if($mode=="mail"){
				return $_REQUEST['openid_ext1_value_email'];
			}
			else if($mode=='language'){
				return $_REQUEST['openid_ext1_value_language'];
			}
			else if($mode=='res_time'){
				return $_REQUEST['openid_response_nonce'];
			}
			else if($mode=='firstname'){
				
			}
			else if($mode=='lastname'){
				
			}
		}
		else if($service=='yahoo'){
			if($mode=="id"){
				
			}
			else if($mode=='mail'){
				
			}
			else if($mode=='language'){
				
			}
			else if($mode=='res_time'){
				
			}
			else if($mode=='firstname'){
				
			}
			else if($mode=='lastname'){
				
			}
		}
		else if($service=='twitter'){
			if($mode=="id"){
				
			}
			else if($mode=='mail'){
				
			}
			else if($mode=='language'){
				
			}
			else if($mode=='res_time'){
				
			}
			else if($mode=='firstname'){
				
			}
			else if($mode=='lastname'){
				
			}
		}
		else if($service=='mixi'){
			if($mode=="id"){
				if(!$_REQUEST['openid_claimed_id']){return;}
				$mixi_id = explode("/",$_REQUEST['openid_claimed_id']);
				return $mixi_id[3];
			}
			else if($mode=="mail"){
				return $_REQUEST['openid_ext1_value_email'];
			}
			else if($mode=='language'){
				return $_REQUEST['openid_ext1_value_language'];
			}
			else if($mode=='res_time'){
				return $_REQUEST['openid_response_nonce'];
			}
			else if($mode=='firstname'){
				
			}
			else if($mode=='lastname'){
				
			}
		}
	}
	
}



