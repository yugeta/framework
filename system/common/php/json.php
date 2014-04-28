<?

/**
* JSON処理
**/

class JSON{
    // Hash → Json コンバート
    function hash2json($data , $mode=null , $cnt=0){
        $a0 = array();
        if(is_array($data)){
            $a1 = array();
            $d0 = @array_keys($data);
            for($i=0,$cnt=count($d0);$i<$cnt;$i++){
                $d2 = hash2json($data[$d0[$i]] , $mode , ($cnt+1));
                if(!$d2){continue;}
                if($mode && $d2=="''"){continue;}
                
                $a1[] = json_tab($cnt)."'".json_key($d0[$i])."':".$d2;
            }
            if(!count($a1)){return;}
            $a0[] = "{\n".@join(",\n",$a1)."\n".json_tab($cnt - 1)."}";
        }
        else{
            $a0[] = "'".json_value($data)."'";
        }
        return @join(",",$a0);
    }
    function json_value($value){
    	$value = @str_replace("\r\n", "\n"  , $value);
    	$value = @str_replace("\r"  , ""    , $value);
    	$value = @str_replace("\n"  , "<br>", $value);
    	$value = @str_replace("\\" , "\\\\" , $value);
    	$value = @str_replace("'" , "\'" , $value);
    	
    	return $value;
    }
    function json_key($value){
    	
    	$value = @str_replace("%5B" , "[" , $value);
    	$value = @str_replace("%5D" , "]" , $value);
    	
    	return $value;
    }
    
    function json_tab($cnt){
    	$tab = "";
    	for($i=0;$i<$cnt;$i++){
    		$tab.="\t";
    	}
    	return $tab;
    }
    
    //classオブジェクトデータで返す。
    //※デフォルトのクォートはダブルクォート。$quoteに値を入れることでシングルクォートに変更できる。
    function json2hash($data,$start,$quote=null){
    	
    	//コメント削除
    	$data = str_replace("\t","",$data);
    	
    	//文字列調整
    	$data = @str_replace("\r","",$data);
    	$data = @str_replace("\n","",$data);
    	
    	if(!$quote){
    		$data = @str_replace("'",'"',$data);
    	}
    	
    	//階層分解
    	$start = str_replace('.','\.',$start);
    	$start = str_replace('$','\$',$start);
    	
    	preg_match("/^(.*?)".$start."=\{(.*?)\};(.*)$/",$data,$match);
    //	preg_match("/(.*)".$start."={(.*?)};(.*)/",$data,$match);
    	
    //	die($match[2]);
    //	die("/(.*)".$start."={(.*?)};(.*)/");
    //	die($data."/".$start);
    	
    	return json_decode('{'.$match[2].'}');
    }
    
    //文字列で返す。
    //※デフォルトのクォートはダブルクォート。$quoteに値を入れることでシングルクォートに変更できる。
    function json2string($data,$start,$quote=null){
    	
    	//コメント削除
    	$data = str_replace("\t","",$data);
    	
    	//文字列調整
    	$data = @str_replace("\r","",$data);
    	$data = @str_replace("\n","",$data);
    	
    	if(!$quote){
    		$data = @str_replace("'",'"',$data);
    	}
    	//階層分解
    	$start = str_replace('.','\.',$start);
    	$start = str_replace('$','\$',$start);
    //	preg_match("/(.*)".$start."={(.*?)};(.*)/",$data,$match);
    	preg_match("/^(.*?)".$start."=\{(.*?)\};(.*)$/",$data,$match);
    	
    	return '{'.$match[2].'}';
    	
    }
    
    function pich_json_test(){
    	
    	$a1 = file_get_contents("user/".$_REQUEST[user]."/product.js");
    	
    	
    	$a2 = json2hash($a1,'$NC.$domain');
    	
    	
    	
    	$a3 = $a2->{'0'};
    	
    //	die($a3);
    	
    	
    	return $a3;
    	
    }
    
    
    /**
     * json_decode
     * @param  string  $data   対象文字列
     * @param  string  $start  取得文字列
     * @return string  $json_decodeした文字列を返す
     */
    function json_decoder($data,$start){
        //文字列調整
        $data = @str_replace(array("\r","\n","\t"),'',$data);
        $data = @str_replace('\'','"',$data);
        //階層分解        
        $start = str_replace('.','\.',$start);
        $start = str_replace('$','\$',$start);
        
        //取得文字列指定
        preg_match('/'.$start.'={(.*)}/',$data,$match);
        
        if(count($match) > 0){
            $pos = strpos($match[1],'}');
            $j_str = substr($match[1],0,$pos);
            if(substr($j_str,strlen($j_str)-1,1) == ','){
                $j_str = substr($j_str,0,strlen($j_str)-1);
            }
            $j_str = json_decode('{'.$j_str.'}',true);
        }else{
            $j_str = '';
        }
    
        return $j_str;
    }
}

