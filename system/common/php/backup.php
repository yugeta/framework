<?
//============================
//バックアップシステム
//    1ファイルのデータを特定の1ファイルに世代管理するバックアップシステム
//	・ファイル仕様
//	[date , data]
//	※dateが世代管理番号になる。
//============================

class BACKUP{
    //指定のバックアップファイルをselectタグ形式で表示
    function backup_select($path,$data=null){
    	if(!file_exists($path)){
    		return $path;
    		return "バックアップファイルはありません。";
    	}
    	else{
    		
    		$html ="";
    		$html.="<select>\n";
    		$list = $this->backup_generation($path);
    		$html.= $this->backup_option($path,$data);
    		$html.="</select>";
    		
    		return $html;
    	}
    }
    //指定のバックアップファイルをoptionタグ形式で表示
    function backup_option($path,$data=null){
    	if(!file_exists($path)){
    		$html ="";
    		$html.="<option value=''>** バックアップファイルはありません。 **</option>\n";
    		return $html;
    	}
    	else{
            
            $dt = new DATE();
            
    		$html ="";
    		$list = $this->backup_generation($path);
    		for($i=0,$c=count(count($list));$i<$c;$i++){
    			if($data == $list[$i]){
    				$sel="selected";
    			}
    			else{
    				$sel="";
    			}
    			$html.="<option value='".$list[$i]."' ".$sel.">".(count($list) - $i)." : ".$dt->dateFormat($list[$i],1)."</option>\n";
    		}
    		return $html;
    	}
    }
    
    //バックアップファイルから、世代リストを返す※配列の最初は最新ID（逆ソート）
    function backup_generation($path){
    	
    	unset($data);
    	exec("cat ".$path." | awk -F',' 'BEGIN{F} {if($1){print $1}}' | sort -r|uniq" , $data);
    	
    	return $data;
    }
    
    //データ書き込み
    function write($path,$data){
    	
    	//書き込み日
    	$date = date(YmdHis);
    	
        //dataフォーマット調整
        $data = str_replace(array("\r\n","\r"),"\n",$data);
        
    	//バックアップファイル書き込み
    	$bak = explode("\n",$data);
    	for($i=0,$c=count($bak);$i<$c;$i++){
    		$bak[$i] = str_replace("\r","%r",$bak[$i]);
            $bak[$i] = str_replace("\n","%n",$bak[$i]);
            
    		$bak[$i] = str_replace(",","&#44;",$bak[$i]);
    		
    		//データ作成
    		$bak[$i] = $date.",".$bak[$i];
    	}
    	
        file_put_contents($path.'.bak',join("\n",$bak)."\n",FILE_APPEND);
    	
    	return true;
    }
    
    //データ読み込み
    function backup_read($path,$id){
    	
    	if(!file_exists($path) || !$id){return;}
    	
    	unset($data);
    	exec("cat ".$path." | awk -F',' 'BEGIN{F} {if($1==".$id."){print $2}}'" , $data);
    	
    	//カンマ文字列調整
    	for($i=0,$cnt=count($data);$i<$cnt;$i++){
    		$data[$i] = str_replace("&#44;",",",$data[$i]);
    	}
    	
    	return join("\n",$data)."\n";
    	
    }
    
    //バックアップファイルの読み込み
    function page_js_backup($id){
    	if(!$id){return;}
    	$data = $this->backup_read("user/".$_REQUEST[user]."/".$_REQUEST[api]."/".$_REQUEST[page].".bak" , $id);
    	
    	//カンマ文字列調整
    	$data = str_replace("&#44;",",",$data);
    	
    	$data = @str_replace('$NC_DATA.spc={' , '$NC_BACKUP={' , $data);
    	
    	return $data;
    }
}