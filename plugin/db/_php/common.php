<?
class DB_COMMON{
	
	// master list select-options
	function table_select_options(){
		$path = "data/".$_REQUEST['tool']."/".UID."/";
		
		if(!is_dir($path)){return;}
		
		$folder = new FOLDER();
		$lists = $folder->lists($path);
		
		$html = "";
		for($i=0,$c=count($lists);$i<$c;$i++){
			if(preg_match("/^(.*?)\.ini$/",$lists[$i],$match)){
				
				$data = explode("\n",file_get_contents($path.$lists[$i]));
				if($data[0]){
					$sp = explode(",",$data[0]);
					$name = $sp[1];
				}
				else{
					//$name = $match[1];
					$name = $lists[$i];
				}
				
				$sel="";
				if($_REQUEST[ini] && $_REQUEST[ini].".ini"==$lists[$i]){$sel="selected";}
				
				$html.= "<option value='".$match[1]."' ".$sel.">".$name."</option>"."\n";
			}
		}
		return $html;
	}
	
	//data-only (multi lines)
	function table_data($list_id=null){
		$datas = $this->table_data_load($list_id);
		return $this->table_view($datas);
	}
	
	// name , type , 'other'
	function table_value($type,$list_id=null){
		if(!$type || !$list_id){return;}
		
		$path = $list_id.".ini";
		if(file_exists("data/".$_REQUEST['tool']."/".UID."/".$path)){
			$lines = explode("\n",file_get_contents("data/".$_REQUEST['tool']."/".UID."/".$path));
			
			for($i=0,$c=count($lines);$i<$c;$i++){
				$sp = explode(",",$lines[$i]);
				if($sp[0]!=$type){continue;}
				return $sp[1];
			}
		}
	}
	
	function table_data_load($ini_id=null){
		
		//データ有
		$path = $ini_id.".ini";
		if(file_exists("data/".$_REQUEST['tool']."/".UID."/".$path)){
			$lines = explode("\n",file_get_contents("data/".$_REQUEST['tool']."/".UID."/".$path));
			
			unset($keys);
			for($i=0,$c=count($lines);$i<$c;$i++){
				$sp = explode(",",$lines[$i]);
				if($sp[0]!='data'){continue;}
				//$keys[] = $sp;
				$keys->{$sp[1]} = $sp;
			}
			return $keys;
		}
	}
	
	function table_view($datas){
		if(!$datas){
			$datas->{"1"}[1] = 1;
		}
		
		$lists = $this->table_array();
		//$lists_array = array_keys($lists);
		$html = "";
		
		foreach($datas as $key=>$val){
			$html.= "<tr>"."\n";
			$html.= $this->table_html($_REQUEST['ini'],$key,$datas->{$key},$lists);
			$html.= "</tr>"."\n";
		}
		return $html;
	}
	function table_html($ini,$key,$val,$lists){
		$html = '';
		$html.= "<td class='id_no'><input type='text' class='data' name='data[id][]' value='".$val[1]."' data-type='id' style='text-align:center;' readonly></td>"."\n";
		$html.= "<td><input type='text' class='data' name='data[name][]' value='".$val[2]."'></td>"."\n";
		$html.= "<td><select class='data' name='data[master_id][]'>";
		$html.= "<option value=''></option>"."\n";
		foreach($lists as $key2=>$val2){
			//if($lists->{$key2} == $ini){continue;}
			if($key2 == $ini){continue;}
			
			$sel = ($key2 === $val[3])?"selected":"";
			
			$html.= "<option value='".$key2."' ".$sel.">".$val2."</option>"."\n";
		}
		$html.= "</select></td>"."\n";
		$html.= "<td><input type='text' class='data' name='data[master_section][]' value='".$val[4]."'></td>"."\n";
		$html.= "<td><input type='text' class='data' name='data[default][]' value='".$val[5]."'></td>"."\n";
		$html.= "<td><input type='text' class='data' name='data[comment][]' value='".$val[6]."'></td>"."\n";
		return $html;
	}
	
	function table_array(){
		if(!is_dir("data/db/".UID)){return;}
		
		unset($data);
		$folder = new FOLDER();
		$path = "data/db/".UID."/";
		$lists = $folder->lists($path);
		
		for($i=0,$c=count($lists);$i<$c;$i++){
			if(preg_match("|(.*?)\.ini$|",$lists[$i],$match)){
				$lines = explode("\n",file_get_contents($path.$lists[$i]));
				for($j=0,$c2=count($lines);$j<$c2;$j++){
					$sp = explode(",",$lines[$j]);
					if($sp[0]=='name'){
						$data[$match[1]] = $sp[1];
						break;
					}
				}
			}
		}
		return $data;
	}
	
	function ini2title($ini){
		$ini_file = "data/".$_REQUEST[tool]."/".UID."/".$ini;
		if(!file_exists($ini_file)){return;}
	}
	
	/*
	// table headers
	// $GLOBALS[ini]
	*/
	function heads(){
		$dir = "data/".$_REQUEST['tool']."/".UID."/";
		
		if(!$_REQUEST['ini'] || !file_exists($dir.$_REQUEST['ini'].".ini")){return;}
		
		//グローバル変数に保存
		//$this->ini_set($_REQUEST['ini']);
		
		// inis
		$ini_lists = explode("\n",file_get_contents($dir.$_REQUEST['ini'].".ini"));
		
		//$ini_count = count($ini_lists)-1;
		
		//heads
		$cnt=0;
		$html = "";
		for($i=0,$c=count($ini_lists);$i<$c;$i++){
			$sp = explode(",",$ini_lists[$i]);
			
			if($sp[0]!='data'){continue;}
			
			$html.= "<th title='header-id:".$sp[1]."' data-col_id='".$sp[1]."'>".$sp[2]."</th>";
			
			//グローバル変数に保存
			//$this->ini_set($sp[3],$dir);
			$cnt++;
		}
		$GLOBALS['ini_count']=$cnt;
		return $html;
	}
	
	//グローバル変数に保存
	function ini_set($ini_id){
		$dir = "data/".$_REQUEST['tool']."/".UID."/";
		
		if(!$ini_id){return;}
		if(isset($GLOBALS['ini_name'][$ini_id])){return;}
		
		if(!isset($GLOBALS['ini_data'][$ini_id])){
			$GLOBALS['ini_data'][$ini_id] = $this->read_ini($ini_id);
		}
		/*
		//カラム情報
		if(!file_exists($dir.$ini_id.".ini")){return;}
		
		//$GLOBALS['ini_name'][$ini_id] = $ini_head[0];
		
		if(!isset($GLOBALS['ini_data'])){
			
			$ini_head = explode("\n",file_get_contents($dir.$ini_id.".ini"));
			
			for($i=0,$c=count($ini_head);$i<$c;$i++){
				if(!$ini_head[$i]){continue;}
				$sp = explode(",",$ini_head[$i]);
				$GLOBALS['ini_data'][$ini_id][$i] = $sp;
				
				if($sp[0]!='data'){
					$GLOBALS['ini_prof'][$sp[0]] = $sp;
				}
				else{
					$GLOBALS['ini_data'][$ini_id][$sp[1]] = $sp;
				}
				//$GLOBALS['ini_data'][$ini_id][$sp[0]] = $sp[1];
			}
		}
		*/
		//datデータの読み込み
		//echo $GLOBALS['ini_data'][$ini_id]['data'].",";
		//echo $GLOBALS['ini_data'][$ini_id][2].",";
		//if($GLOBALS['ini_data'][$ini_id]['data']){
			//$dat_ini = $GLOBALS['ini_data'][$ini_id][2];
			foreach($GLOBALS['ini_data'][$ini_id]['data'] as $key=>$dat){echo $dat[2].",";
				//echo $dat[2].",";
				//master指定のあるデータのみ処理
				if($dat[2]){
					$GLOBALS['dat_data'][$dat[2]] = $this->read_dat($dat[2]);
				}
			}
		//}
		//if(!file_exists($dir.$ini_id.".dat")){return;}
		//$GLOBALS['dat_data'][$ini_id] = $this->read_dat($ini_id);
	}
	
	function read_ini($ini_id){
		if(isset($GLOBALS['ini_data'][$ini_id])){return;}
		
		$dir  = "data/".$_REQUEST['tool']."/".UID."/";
		$path = $dir.$ini_id.".ini";
		if(!file_exists($path)){return;}
		$ini_head = explode("\n",file_get_contents($path));
		
		unset($data);
		
		for($i=0,$c=count($ini_head);$i<$c;$i++){
			if(!$ini_head[$i]){continue;}
			$sp = explode(",",$ini_head[$i]);
			
			if($sp[0]=='data'){
				$data[$sp[0]][$sp[1]] = $sp;
			}
			else{
				$data[$sp[0]] = $sp;
			}
		}
		return $data;
	}
	
	function read_dat($ini_id){//echo $ini_id."/";
		//ファイルチェック
		$dir = "data/".$_REQUEST['tool']."/".UID."/";
		$path = $dir.$ini_id.".dat";
		if(!file_exists($path)){return;}
		
		unset($data);
		
		$ini_data = explode("\n",file_get_contents($path));
		for($i=0,$c=count($ini_data);$i<$c;$i++){
			if(!$ini_data[$i]){continue;}
			$sp = explode(",",$ini_data[$i]);
			if($sp[0]==""){continue;}
			//0:ID 1:name(value)
			$data[$sp[0]] = $sp[1];
		}
		
		return $data;
	}
	
	// table datas
	function datas($type=''){
		$dir = "data/".$_REQUEST['tool']."/".UID."/";
		
		if(!$_REQUEST['ini'] || !file_exists($dir.$_REQUEST['ini'].".ini")){return;}
		
		/*
		// inis
		$ini_lists = explode("\n",file_get_contents($dir.$_REQUEST['ini'].".ini"));
		//$ini_count = count($ini_lists)-1;
		$ini_count = 0;
		for($i=0,$c=count($ini_lists);$i<$c;$i++){
			$sp = explode(",",$ini_lists[$i]);
			if($sp[0]=='data'){$ini_count++;}
		}
		*/
		//view
		$html = "";
		
		// datas
		if(file_exists($dir.$_REQUEST['ini'].".dat")){
			$ini_datas = explode("\n",file_get_contents($dir.$_REQUEST['ini'].".dat"));
			
			for($i=0,$c=count($ini_datas)-1;$i<$c;$i++){
				$sp = explode(",",$ini_datas[$i]);
				
				//$no = $sp[0];//
				$no = ($i+1);
				
				$html.= "<tr>";
				$html.= $this->html_datas($sp,$no,$type);
				$html.= "</tr>";
			}
		}
		return $html;
	}
	function html_datas($sp,$no,$type=""){
		
		$dir = "data/".$_REQUEST['tool']."/".UID."/";
		
		if(!isset($GLOBALS['ini_data'][$_REQUEST['ini']])){$this->ini_set($_REQUEST['ini']);}
		
		$html.= "<th class='id' title='row-id:".$sp[0]."' data-row_id='".$sp[0]."'>".$no."</th>";
		
		for($j=1;$j<=$GLOBALS['ini_count'];$j++){
			
			$html.= "<td class='cell'>";
			
			//別マスター参照
			//echo $_REQUEST['ini']." : ".join(",",$GLOBALS['ini_data'][$_REQUEST['ini']][$j])."<br>\n";
			if($GLOBALS['ini_data'][$_REQUEST['ini']][$j][3]){
				
				$ini_id = $GLOBALS['ini_data'][$_REQUEST['ini']][$j][3];
				if(!isset($GLOBALS['ini_data'][$ini_id])){$this->ini_set($ini_id);}
					
				if($type=='txt'){
					$html.= $GLOBALS['dat_data'][$ini_id][$sp[$j]];
				}
				else{
					$html.= "<select data-col-id='".$j."' data-row-id='".$sp[0]."'>";
					$html.= "<option value=''></option>";
					//for($k=0,$c=count($GLOBALS['dat_data'][$ini_id]);$k<$c;$k++){
					foreach($GLOBALS['dat_data'][$ini_id] as $key=>$val){
						$sel = ($sp[$j]==$key)?"selected":"";
						$html.= "<option value='".$key."' ".$sel.">".$GLOBALS['dat_data'][$ini_id][$key]."</option>";
					}
					$html.= "</select>";
				}
			}
			//通常データ
			else{
				if($type=='txt'){
					$html.= $sp[$j];
				}
				else{
					$html.= "<input type='text' value='".$sp[$j]."' data-col-id='".$j."' data-row-id='".$sp[0]."'>";
				}
			}
			
			$html.= "</td>";
		}
		return $html;
	}
	/*
	// table datas(text-view)
	function datas_text(){
		$dir = "data/".$_REQUEST['tool']."/".UID."/";
		
		if(!$_REQUEST['ini'] || !file_exists($dir.$_REQUEST['ini'].".ini")){return;}
		
		// inis
		$ini_lists = explode("\n",file_get_contents($dir.$_REQUEST['ini'].".ini"));
		
		$ini_count = count($ini_lists)-1;
		
		$html = "";
		
		// datas
		if(file_exists($dir.$_REQUEST['ini'].".dat")){
			$ini_datas = explode("\n",file_get_contents($dir.$_REQUEST['ini'].".dat"));
			
			for($i=0,$c=count($ini_datas)-1;$i<$c;$i++){
				$sp = explode(",",$ini_datas[$i]);
				//$no = $sp[0];//
				$no = ($i+1);
				
				$html.= "<tr>";
				$html.= $this->html_datas($ini_count,$sp,$no,'text');
				$html.= "</tr>";
			}
		}
		return $html;
	}
	*/
	/*
	function html_datas_text($ini_count,$sp,$no){
		
		$dir = "data/".$_REQUEST['tool']."/".UID."/";
		
		if(!isset($GLOBALS['ini_data'][$_REQUEST['ini']])){$this->ini_set($_REQUEST['ini'],$dir);}
		
		$html.= "<th class='id' title='row-id:".$sp[0]."' data-row_id='".$sp[0]."'>".$no."</th>";
		for($j=1;$j<$ini_count;$j++){
			
			if($GLOBALS['ini_data'][$_REQUEST['ini']][$j][3]){
				$ini_id = $GLOBALS['ini_data'][$_REQUEST['ini']][$j][3];
				if(!isset($GLOBALS['ini_data'][$ini_id])){$this->ini_set($ini_id,$dir);}
					
				$html.= "<td class='cell'>".$GLOBALS['dat_data'][$ini_id][$sp[$j]]."</td>";
			}
			else{
				$html.= "<td class='cell'>".$sp[$j]."</td>";
			}
		}
		return $html;
	}
	*/
	
}
