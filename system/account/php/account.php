<?

class ACCOUNT{
	
	//対象ファイル
	var $user_file = "data/common/users.dat";
	
	//新規登録
	function new_regist($mode,$service,$data){
		
		$template = new template();
		$url = new URL();
		
		if($mode=='add'){
			
			//登録済み確認（登録されていればtrueがかえる）
			if($this->checkAccountID("",$data['id'])){
				$GLOBALS['view']['message'] = "「".$data['id']."」は登録済みのアカウントです。";
				$_REQUEST['m'] = "regist";
			}
			//情報不足（登録が完了した場合にtrueがかえる）
			else if(!$this->setAccount(array("0",$service,"",$data['id'],$data['pw'],$data['nm'],$data['mail'],$data['img']))){
				$GLOBALS['view']['message'] = "入力情報が不足しています。";
				$_REQUEST['m'] = "regist";
			}
			//登録成功
			else{
				//header("Location: ".$url->getUrl());
				$GLOBALS['view']['message'] = "登録が完了しました。";
				$_REQUEST['m'] = "regist";
			}
		}
		//update
		else if($mode=='update'){
			$this->setAccount(array("0",$service,"",$data['id'],$data['pw'],$data['nm'],$data['mail'],$data['img']));
			//header("Location: ".$url->url()."?tool=system&mode=account_setting_complete");
			$GLOBALS['view']['message'] = "登録情報を更新しました。";
			$_REQUEST['p2'] = "account";
			$_REQUEST['m'] = "setting";
			
		}
		//登録画面
		else{
			//$template->file2HTML($_REQUEST['tool'],$_REQUEST['page'] , "regist");
			$_REQUEST['m'] = "regist";
		}
	}
	
	//アカウントデータの読み込み
	function getAccount($id){
		
		if(!$id){return array();}
		
		if(!file_exists($this->user_file)){return array();}
		
		//ユーザーデータ読み込み
		$data_users = explode("\n",file_get_contents($this->user_file));
		
		$flg=0;
		unset($data);
		
		//データ内のライン処理
		for($i=0,$c=count($data_users);$i<$c;$i++){
			
			//ラインの文字列を分解
			$sp = explode(",",$data_users[$i]);
			
			//データカラムチェック
			if(count($sp)<=1){continue;}
			
			//service(open-id)チェック
			//if($sp[1]!=$service){continue;}
			
			//論理削除フラグフラグ
			if($sp[0]!="0"){
				unset($data);
				$flg=0;
				continue;
			}
			
			//アカウント判別
			if($sp[0]=="0" && $sp[3]==$id){
				$data=array();
				//$data=$sp;
				/*
				$data = array(
					"flg"=>$sp[0],
					"service"=>$sp[1],
					"auth"=>$sp[2],
					"id"=>$sp[3],
					"pw"=>$sp[4],
					"nm"=>$sp[5],
					"mail"=>$sp[6]
				);
				*/
				
				$data["flg"]  = $sp[0];
				$data["service"]= $sp[1];
				$data["auth"] = $sp[2];
				$data["id"]   = $sp[3];
				$data["pw"]   = $sp[4];
				$data["nm"]   = $sp[5];
				$data["mail"] = $sp[6];
				
				$flg++;
			}
		}
		
		if($flg && isset($data)){return $data;}
		//return array();
	}
	//個別アカウント情報の取得
	function getAccountInfo($mode){
		//基本ID
		$id = $_SESSION['id'];
		
		if(!$id){return;}
		
		//IDのみ簡易処理
		if($mode=="id"){return $id;}
		
		if(!file_exists($this->user_file)){return;}
		
		//アカウントデータ読み込み
		$datas = explode("\n",file_get_contents($this->user_file));
		
		for($i=0,$c=count($datas);$i<$c;$i++){
			$sp = explode(",",$datas[$i]);
			
			//論理削除チェック
			if($sp[0]!="0"){
				
				//削除済みIDのの場合はデータをフラッシュする
				if($sp[3]==$id){
					unset($account);
				}
				
				continue;
			}
			
			//ID判定
			if($sp[3]!=$id){continue;}
			
			//データ上書き
			unset($account);
			$account = $sp;
		}
		
		if(!count($account)){die("NG");}
		
		if($mode=="flg"){
			return $account[0];
		}
		else if($mode=="service"){
			return $account[1];
		}
		else if($mode=="auth"){
			return $account[2];
		}
		else if($mode=="id"){
			return $account[3];
		}
		else if($mode=="pw"){
			return $account[4];
		}
		else if($mode=="nm"){
			return $account[5];
		}
		else if($mode=="mail"){
		 	return $account[6];
		}
		else if($mode=="img"){
		 	return $account[7];
		}
	}
	//アカウントデータの書き込み
	function setAccount($data){
		
		//IDの登録は必須
		if(!$data[3]){return;}
		
		//[ 0:flg 1:service 2:auth 3:ID 4:PassWord 5:Name 6:Mail 7:image ]
		file_put_contents($this->user_file , join(",",$data).",\n" , FILE_APPEND);
		
		//session更新
		unset($data2);
		$data2['service'] = $data[1];
		$data2['auth'] = $data[2];
		$data2['id'] = $data[3];
		$data2['name'] = $data[5];
		$data2['mail'] = $data[6];
		$data2['img'] = $data[7];
		
		//session情報更新
		$login = new LOGIN();
		$login->setSessionData($data2);
		
		return true;
	}
	function checkAccountID($service,$id){
		
		if(!$id){return;}
		
		if(!file_exists($this->user_file)){return;}
		
		//ユーザーデータ読み込み
		$data_users = explode("\n",file_get_contents($this->user_file));
		
		//データ内のライン処理
		for($i=0,$c=count($data_users);$i<$c;$i++){
			
			//ラインの文字列を分解
			$sp = explode(",",$data_users[$i]);
			
			//データカラムチェック
			if(count($sp)<=1){continue;}
			
			//service(open-id)チェック
			if($sp[1]!=$service){continue;}
			
			//論理削除フラグフラグ
			if($sp[0]!="0"){
				$pw_data="";
				continue;
			}
			
			//アカウント判別
			if($sp[3]==$id){return true;}
			
		}
		
	}
	
	//アカウント画像
	function getAccountImage(){
		//die("b:".$_SESSION['img']);
		//$string = new STRING();
		
		//$imgFile = str_replace("%","%25",$_SESSION['img']);
		//die("file : ".is_file("data/common/account/https%3A%2F%2Fwww.google.com%2Faccounts%2Fo8%2Fid%3Fid%3DAItOawkVahVTA4B0dTSfV9xlxoHrFSfliBxlriM.jpg")." : ".file_exists($imgFile)."<br>\n".$imgFile."<br>\n"."data/common/account/https%3A%2F%2Fwww.google.com%2Faccounts%2Fo8%2Fid%3Fid%3DAItOawkVahVTA4B0dTSfV9xlxoHrFSfliBxlriM.jpg");
		//$imgFile = urlencode($_SESSION['img']);
		//die("b:".is_file($imgFile)." : ".is_file($_SESSION['img'])." : ".$imgFile);
		if($_SESSION['img'] && is_file($_SESSION['img'])){
			$imgFile = str_replace("%","%25",$_SESSION['img']);
			return "<img class='account_image' src='".$imgFile."?".date(YmdHis)."' />";
		}
		//デフォルト画像
		else{
			$template = new TEMPLATE();
			return $template->file2HTML($GLOBALS['sys']['system']."/account/html/account_image_default.html");
		}
		
	}
	
	//
	function getInfo($user_id){
		//UID指定がない場合は処理無し
		if(!$user_id){return;}
		
		//$string = new STRING();
		
		
		
		$html="";
		//die("a:".$_SESSION['img']);
		//画像表示
		if($_SESSION['img'] && is_file($_SESSION['img'])){
			$imgFile = str_replace("%","%25",$_SESSION['img']);
			$html.= "<img class='account_image' src='".$imgFile."?".date(YmdHis)."'>\n";
			$html.= "<br>\n";
		}
		else{
			$tpl = new TEMPLATE();
			$html.= $tpl->file2HTML($GLOBALS['sys']['system']."/account/html/account_image_default.html");
			$html.= "<br>\n";
		}
		
		//名前表示
		if($_SESSION['name']){
			$html.= "name:".$_SESSION['name'];
			$html.= "<br>\n";
		}
		
		//openidチェック
		if($_SESSION['service']){
			$html.= "open-id:".$_SESSION['service'];
			$html.= "<br>\n";
		}
		
		//$html.= $_SESSION['service']."\n";
		
		return $html;
		
	}
	
	
}