<?

/*
 * アカウント情報の操作
 * データ配置
 * [ 0:flg 1:service 2:auth 3:ID 4:PassWord 5:Name 6:Mail ]
 */

class ACCOUNT{
	
	var $user_file = "data/system/users.dat";
	
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
	}
	//アカウントデータの書き込み
	function setAccount($data){
		
		//IDの登録は必須
		if(!$data[3]){return;}
		
		//[ 0:flg 1:service 2:auth 3:ID 4:PassWord 5:Name 6:Mail ]
		file_put_contents($this->user_file , join(",",$data).",\n" , FILE_APPEND);
		
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
	
	
}