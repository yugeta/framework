<?

class datas{
	
	function index(){
		$tpl = new TEMPLATE();
		
		return $tpl->read_tpl(TOOL.$_REQUEST[tool].'/tpl/'.$_REQUEST[menu].'.html');
	}
	
	// master list select
	function inis(){
		$path = "data/".$_REQUEST['tool']."/".UID."/";
		
		if(!is_dir($path)){return;}
		
		$folder = new FOLDER();
		$lists = $folder->lists($path);
		
		$html = "";
		for($i=0,$c=count($lists);$i<$c;$i++){
			if(preg_match("/^(.*?)\.ini$/",$lists[$i],$match)){
				
				$data = explode("\n",file_get_contents($path.$lists[$i]));
				if($data[0]){
					$name = $data[0];
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
	/*
	// table headers
	// $GLOBALS[ini]
	*/
	/*
	function heads(){
		$path = "data/".$_REQUEST['tool']."/".UID."/";
		
		if(!$_REQUEST['ini'] || !file_exists($path.$_REQUEST['ini'].".ini")){return;}
		
		//グローバル変数に保存
		$this->ini_set($_REQUEST['ini'],$path);
		
		// inis
		$ini_lists = explode("\n",file_get_contents($path.$_REQUEST['ini'].".ini"));
		
		$ini_count = count($ini_lists)-1;
		
		//heads
		$html = "";
		for($i=1;$i<$ini_count;$i++){
			$sp = explode(",",$ini_lists[$i]);
			$html.= "<th title='header-id:".$sp[1]."' data-col_id='".$sp[1]."'>".$sp[2]."</th>";
			
			//グローバル変数に保存
			$this->ini_set($sp[3],$path);
		}
		
		return $html;
	}
	*/
	/*
	//グローバル変数に保存
	function ini_set($ini_id,$path){
		if(!$ini_id){return;}
		if(isset($GLOBALS['ini_name'][$ini_id])){return;}
		
		if(!file_exists($path.$ini_id.".ini")){return;}
		$ini_head = explode("\n",file_get_contents($path.$ini_id.".ini"));
		$GLOBALS['ini_name'][$ini_id] = $ini_head[0];
		for($i=1,$c=count($ini_head);$i<$c;$i++){
			if(!$ini_head[$i]){continue;}
			$sp = explode(",",$ini_head[$i]);
			$GLOBALS['ini_data'][$ini_id][$i] = $sp;
			//$GLOBALS['ini_data'][$ini_id][$sp[0]] = $sp[1];
		}
		
		
		if(!file_exists($path.$ini_id.".dat")){return;}
		$ini_data = explode("\n",file_get_contents($path.$ini_id.".dat"));
		for($i=0,$c=count($ini_data);$i<$c;$i++){
			if(!$ini_data[$i]){continue;}
			$sp = explode(",",$ini_data[$i]);
			//$GLOBALS['dat_data'][$ini_id][$i] = $sp;
			$GLOBALS['dat_data'][$ini_id][$sp[0]] = $sp[1];
		}
	}
	*/
	/*
	// table datas
	function datas(){
		$path = "data/".$_REQUEST['tool']."/".UID."/";
		if(!$_REQUEST['ini'] || !file_exists($path.$_REQUEST['ini'].".ini")){return;}
		
		// inis
		$ini_lists = explode("\n",file_get_contents($path.$_REQUEST['ini'].".ini"));
		
		$ini_count = count($ini_lists)-1;
		
		$html = "";
		
		// datas
		if(file_exists($path.$_REQUEST['ini'].".dat")){
			$ini_datas = explode("\n",file_get_contents($path.$_REQUEST['ini'].".dat"));
			
			for($i=0,$c=count($ini_datas)-1;$i<$c;$i++){
				$sp = explode(",",$ini_datas[$i]);
				//$no = $sp[0];//
				$no = ($i+1);
				
				$html.= "<tr>";
				$html.= $this->html_datas($ini_count,$sp,$no);
				$html.= "</tr>";
			}
		}
		return $html;
	}
	*/
	/*
	// table datas(text-view)
	function datas_text(){
		$path = "data/".$_REQUEST['tool']."/".UID."/";
		if(!$_REQUEST['ini'] || !file_exists($path.$_REQUEST['ini'].".ini")){return;}
		
		// inis
		$ini_lists = explode("\n",file_get_contents($path.$_REQUEST['ini'].".ini"));
		
		$ini_count = count($ini_lists)-1;
		
		$html = "";
		
		// datas
		if(file_exists($path.$_REQUEST['ini'].".dat")){
			$ini_datas = explode("\n",file_get_contents($path.$_REQUEST['ini'].".dat"));
			
			for($i=0,$c=count($ini_datas)-1;$i<$c;$i++){
				$sp = explode(",",$ini_datas[$i]);
				//$no = $sp[0];//
				$no = ($i+1);
				
				$html.= "<tr>";
				$html.= $this->html_datas_text($ini_count,$sp,$no);
				$html.= "</tr>";
			}
		}
		return $html;
	}
	
	function add_datas(){
		
		//data-file add-write
		$path = "data/".$_REQUEST['tool']."/".UID."/".$_REQUEST['ini'].".dat";
		$row_data = $_REQUEST['new_id'].",\n";
		file_put_contents($path , $row_data , FILE_APPEND);
		
		//view html
		$html = $this->html_datas($_REQUEST['col_max'],array($_REQUEST['new_id']),$_REQUEST['new_id']);
		echo $html;
		exit;
	}
	function html_datas($ini_count,$sp,$no){
		
		$path = "data/".$_REQUEST['tool']."/".UID."/";
		if(!isset($GLOBALS['ini_data'][$_REQUEST['ini']])){$this->ini_set($_REQUEST['ini'],$path);}
		
		$html.= "<th class='id' title='row-id:".$sp[0]."' data-row_id='".$sp[0]."'>".$no."</th>";
		for($j=1;$j<$ini_count;$j++){
			
			if($GLOBALS['ini_data'][$_REQUEST['ini']][$j][3]){
				$ini_id = $GLOBALS['ini_data'][$_REQUEST['ini']][$j][3];
				if(!isset($GLOBALS['ini_data'][$ini_id])){$this->ini_set($ini_id,$path);}
					
				$html.= "<td class='cell'>";
					$html.= "<select data-col-id='".$j."' data-row-id='".$sp[0]."'>";
					$html.= "<option value=''></option>";
					//for($k=0,$c=count($GLOBALS['dat_data'][$ini_id]);$k<$c;$k++){
					foreach($GLOBALS['dat_data'][$ini_id] as $key=>$val){
						$sel = ($sp[$j]==$key)?"selected":"";
						$html.= "<option value='".$key."' ".$sel.">".$GLOBALS['dat_data'][$ini_id][$key]."</option>";
					}
					$html.= "</select>";
				$html.= "</td>";
			}
			else{
				$html.= "<td class='cell'><input type='text' value='".$sp[$j]."' data-col-id='".$j."' data-row-id='".$sp[0]."'></td>";
			}
		}
		return $html;
	}
	function html_datas_text($ini_count,$sp,$no){
		
		$path = "data/".$_REQUEST['tool']."/".UID."/";
		if(!isset($GLOBALS['ini_data'][$_REQUEST['ini']])){$this->ini_set($_REQUEST['ini'],$path);}
		
		$html.= "<th class='id' title='row-id:".$sp[0]."' data-row_id='".$sp[0]."'>".$no."</th>";
		for($j=1;$j<$ini_count;$j++){
			
			if($GLOBALS['ini_data'][$_REQUEST['ini']][$j][3]){
				$ini_id = $GLOBALS['ini_data'][$_REQUEST['ini']][$j][3];
				if(!isset($GLOBALS['ini_data'][$ini_id])){$this->ini_set($ini_id,$path);}
					
				$html.= "<td class='cell'>".$GLOBALS['dat_data'][$ini_id][$sp[$j]]."</td>";
			}
			else{
				$html.= "<td class='cell'>".$sp[$j]."</td>";
			}
		}
		return $html;
	}
	*/
	function cell_write(){
		
		// ini-file check
		$data_path = "data/".$_REQUEST['tool']."/".UID."/".$_REQUEST['ini'].".dat";
		$temp_path = "data/".$_REQUEST['tool']."/".UID."/".$_REQUEST['ini'].".tmp";
		$bak_path  = "data/".$_REQUEST['tool']."/".UID."/bak/";
		
		$ini = $_REQUEST['ini'];
		$value = $_REQUEST['value'];
		$col = $_REQUEST['col_id'];
		$row = $_REQUEST['row_id'];
		$col_max = $_REQUEST['col_max'];
		$awk_path = "tool/".$_REQUEST['tool']."/awk/data_write.awk";
		if(file_exists($awk_path)){
			// ini-file awk
			unset($res);
			//exec("awk -F, -v value='".$value."' -v col=".$col." row=".$row." -f ".$awk_path." ".$data_path , $res);
			exec("awk -F, -v value='".$value."' -v col_max='".$col_max."' -v col=".$col." -v row=".$row." -f ".$awk_path." ".$data_path." > ".$temp_path , $res);
			
			if(!is_dir($bak_path)){
				mkdir($bak_path,0777,true);
			}
			//unlink($data_path);
			if(file_exists($data_path)){
				rename($data_path,$bak_path.$_REQUEST['ini'].".".date(Ymdhis).".dat");
			}
			rename($temp_path,$data_path);
			
			//echo "res:-----\n".join("\n",$res);
			echo "success";
		}
		else{
			echo "not-file : ".$awk_path;
		}
		
		
		exit;
	}
	
	
}
