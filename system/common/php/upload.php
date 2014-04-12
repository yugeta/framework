<?

/**
 * ファイルアップロード
 * 
**/

class UPLOAD{
	
	/*-----
	* 単一ファイルアップロードを特定位置にセットする
	* ■対象タグ
	* <input type='file' name='file1'>
	* ■クエリ
	* return:ファイル処理後のリダイレクト先※ない場合はそのまま終了
	* path:ファイル保存先フォルダ（必須）
	* filename:保存するファイル名※無い場合はそのままのファイル名
	* ext:拡張子の指定
	-----*/
	function setFile(){
		
		//拡張子取得
		if(!$_REQUEST['ext']){
			$exts = explode(".",$_FILES['file1']['name']);
			$ext = $exts[count($exts)-1];
		}
		else{
			$ext = $_REQUEST['ext'];
		}
		
		//ファイル保存場所の確保
		if(!is_dir($_REQUEST['path'])){
			mkdir($_REQUEST['path'],0777,true);
		}
		
		//保存ファイル名
		if($_REQUEST['filename']){
			$file = $_REQUEST['filename'].".".$ext;
		}
		else{
			$file = $_FILES['file1']['name'];
		}
		$file = urlencode($file);
		
		//既存データがある場合は削除
		if(is_file($_REQUEST['path'].$file)){
			unlink($_REQUEST['path'].$file);
		}
		
		//ファイルを所定の場所にコピー
		rename($_FILES['file1']['tmp_name'] , $_REQUEST['path'].$file);
		
		//リダイレクト処理
		if($_REQUEST['return']){
			$return = $_REQUEST['return'];
			$return.= "?path=".$_REQUEST['path'];
			$return.= "&filename=".$file;
			
			header("Location: ".$return);
		}
		
		exit();
	}
	
	//複数アップロードデータを単一データに変換
	function file_update_property($file , $num){
		
		unset($data);
		$data[name] = $file[name][$num];
		$data[tmp_name] = $file[tmp_name][$num];
		$data[type] = $file[type][$num];
		$data[size] = $file[size][$num];
		$data[error] = $file[error][$num];
		
		return $data;
	}
	
	//ファイル情報
	function file_update_info($f){
		unset($i);
		$i[] = "Name:".$f[name];
		$i[] = "tmp_name:".$f[tmp_name];
		$i[] = "type:".$f[type];
		$i[] = "size:".$f[size]."Bite";
		$i[] = "error:".$f[error];
		
		echo join("<br>",$i)."<hr>";
		return $i;
	}
	
	function file_upload_start($tmp,$file,$dir=""){
		/*
		if(!$dir){
			$dir="data/";
		}
		$parent = $this->make_dir($parent);
		$dir = $this->make_dir($dir);
		*/
		
		if($dir && !is_dir($dir)){
			mkdir($dir , 0777 , true);
		}
		
		//データをテンポラリから本番へ移動
		if(file_exists($tmp)){
			rename($tmp , $dir.$file);
		}
	}
	
	//多重階層作成（ディレクトリのみ）[階層文字列を返す]
	function make_dir($dir,$parent=""){
		if(!$dir){return;}
		
		$path = $parent.$dir;
		
		//同名ファイルとして存在確認
		if(file_exists($path)){}
		//ディレクトリの存在確認
		else if(is_dir($path)){}
		//上記ヒット無い場合はフォルダ作成
		else{
			$dirs = split("\/",$dir);
			$dir_path = "";
			//階層が複数ある場合は、ループ処理
			if(count($dirs)>1){
				for($i=0;$i< count($dirs);$i++){
					$dir_path.= $dirs[$i]."/";
					if(is_dir($parent.$dir_path)){continue;}
					mkdir($parent.$dir_path);
				}
			}
			else{
				$dir_path = $dirs[0];
				mkdir($parent.$dir_path);
			}
			$path = $parent.$dir_path;
		}
		
		//階層表記判定
		if(!preg_match("/\/$/",$path)){
			$path .= "/";
		}
		
		return $path;
	}
	
	//ディレクトリ内のリストを取得
	function searchDir($path,$val){
		
		if($d = @dir($path)){
			while ($entry = $d->read()) {
				if ($entry != '.' && $entry != '..' && preg_match('/'.$val.'/',$entry,$match)){
					$data[] = $entry;
				}
			}
			unset($d); $d = null;
			@sort($data);
			return $data;
		}
	}
	
	//文字列のエンコード処理
	function value_encode($str){
		$arr = array('&','"',"'",'=',' ');
		
		for($i=0;$i< count($arr);$i++){
			$str = str_replace($arr[$i],rawurlencode($arr[$i]),$str);
		}
		return $str;
	}
	//コマンド用文字列変換
	function value_exec($str){
		$arr = array('&','"',"'",'=',' ');
		
		for($i=0;$i< count($arr);$i++){
			$str = str_replace($arr[$i],"\\".$arr[$i],$str);
		}
		return $str;
	}
	
	//type値を取得
	function check_type($path){
		if(is_link($path)){
			return "link";
		}
		if(is_file($path)){
			return "file";
		}
		else if(is_dir($path)){
			return "folder";
		}
		else{
			return "";
		}
	}
	
}
