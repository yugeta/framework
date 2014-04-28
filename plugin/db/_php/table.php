<?

class table{
	
	function index(){
		$tpl = new TEMPLATE();
		
		return $tpl->read_tpl(TOOL.$_REQUEST[tool].'/tpl/'.$_REQUEST[menu].'.html');
	}
	
	function lists_add(){
		$_REQUEST['ini'] = date(YmdHis);
		$_REQUEST['data'][0]['master_name'] = $_REQUEST['master_title'];
		$this->write();
	}
	
	function lists_del(){
		$path = "data/".$_REQUEST['tool']."/".UID."/";
		for($i=0,$c=count($_REQUEST['lists']);$i<$c;$i++){
			$data_file = $path.$_REQUEST['lists'][$i]['ini'];
			if($_REQUEST['lists'][$i]['flg'] && file_exists($data_file)){
				rename($data_file , $data_file.".bak");
			}
		}
		$url = new URL();
		$url->move($url->url()."?tool=".$_REQUEST['tool']."&menu=".$_REQUEST['menu']);
	}
	
	function add(){
		if($_REQUEST['data'] && $_REQUEST['ini']){
			unset($data);
			$max_id=0;
			
			for($i=1,$c=count($_REQUEST['data']);$i<$c;$i++){
				if(!preg_match("/^[0-9]$/",$_REQUEST['data'][$i][1])){continue;}
				if($_REQUEST['data'][$i][1] > $max_id){$max_id = $_REQUEST['data'][$i][1];}
			}
			$_REQUEST['data'][$i][1] = ($max_id + 1);
		}
		$this->write();
	}
	
	function del(){
		$url = new URL();
		//die("delete");
		//$dir = "data/".$_REQUEST['tool']."/".UID."/";
		//if(!is_dir($dir)){mkdir($dir,0777,true);}
		//$path = $dir.$_REQUEST['ini'].".ini";
		$path = "data/".$_REQUEST['tool']."/".UID."/".$_REQUEST['ini'].".ini";
		if(file_exists($path)){
			rename($path , $path.".bak.".date(YmdHis));
		}
		$url->move($url->url()."?tool=".$_REQUEST['tool']."&menu=".$_REQUEST['menu']);
	}
	
	function write(){
		
		//データ名称の確認（必須）
		if($_REQUEST['db']['name']===''){
			die("名称を記入してください。");
		}
		
		if(!$_REQUEST['ini']){
			$_REQUEST['ini'] = date(YmdHis);
		}
		
		$url = new URL();
		
		$db_key = array("name","type");
		
		$ini_data = "";
		
		//setting
		$dir = "data/".$_REQUEST['tool']."/".UID."/";
		if(!is_dir($dir)){mkdir($dir,0777,true);}
		$path = $dir.$_REQUEST['ini'].".ini";
		$write_flg=0;
		
		//header-data
		for($i=0,$c=count($db_key);$i<$c;$i++){
			$ini_data.= $db_key[$i].",".$_REQUEST['db'][$db_key[$i]]."\n";
		}
		
		//ini-data
		for($i=0,$c=count($_REQUEST['data']['id']);$i<$c;$i++){
			
			//飛び番対応
			//if(!isset($_REQUEST['data']['name'][$i])){continue;}
			//Nameを記入していない箇所は飛ばす
			//if($_REQUEST['data']['name'][$i]===''){continue;}
			
			
			//$val = $_REQUEST['data'][$i];
			//$key = $val[0];
			//$ini_data.= "data,".join(",",$val)."\n";
			
			$ini_data.= "data,";
			$ini_data.= $_REQUEST['data']['id'][$i].",";
			$ini_data.= $_REQUEST['data']['name'][$i].",";
			$ini_data.= $_REQUEST['data']['master_id'][$i].",";
			$ini_data.= $_REQUEST['data']['master_section'][$i].",";
			$ini_data.= $_REQUEST['data']['default'][$i].",";
			$ini_data.= $_REQUEST['data']['comment'][$i].",";
			$ini_data.= "\n";
			
			//flg
			$write_flg++;
		}
		
		//データ削除
		if(!$write_flg){
			unlink($path);
		}
		
		//データ書込
		else{
			file_put_contents($path , $ini_data);
		}
		$url->move($url->url()."?tool=".$_REQUEST['tool']."&menu=".$_REQUEST['menu']."&ini=".$_REQUEST['ini']);
	}
	/*
	function lists_array($user){
		if(!is_dir("data/db/".$user)){return;}
		unset($data);
		$folder = new FOLDER();
		$path = "data/db/".$user."/";
		$lists = $folder->lists($path);
		for($i=0,$c=count($lists);$i<$c;$i++){
			if($_REQUEST['ini'].".ini"==$lists[$i]){continue;}
			$lines = explode("\n",file_get_contents($path.$lists[$i]));
			$name = $lines[0];
			$data[$_REQUEST['ini']] = $name;
		}
		return $data;
	}
	
	function ini2title($ini){
		$ini_file = "data/".$_REQUEST[tool]."/".UID."/".$ini;
		if(!file_exists($ini_file)){return;}
		
	}
	*/
	/*
	//data-only (multi lines)
	function datas_view($type,$list_id=null){
		$datas = $this->datas($type,$list_id);
		return $this->datas_html($datas);
	}
	// other 'data'
	function datas_value($type,$list_id=null){
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
	
	function datas($type,$ini_id=null){
		
		$user = UID;
		
		//データ有
		$path = $ini_id.".ini";
		if(file_exists("data/".$_REQUEST['tool']."/".$user."/".$path)){
			$lines = explode("\n",file_get_contents("data/".$_REQUEST['tool']."/".$user."/".$path));
			
			unset($keys);
			for($i=0,$c=count($lines);$i<$c;$i++){
				$sp = explode(",",$lines[$i]);
				if($sp[0]!=$type){continue;}
				$keys[] = $sp;
			}
			return $keys;
		}
	}
	
	function datas_html($datas){
		$lists = $this->lists_array(UID);
		$lists_array = array_keys($lists);
		$html = "";
		for($i=0,$c=count($datas);$i<$c;$i++){
			$html.= "<tr>"."\n";
			//contents
			$html.= "<td class='id_no'><input type='text' class='data' name='db[".$i."][1]' value='".$datas[$i][1]."' data-type='id' style='text-align:center;' readonly></td>"."\n";
			$html.= "<td><input type='text' class='data' name='db[".$i."][2]' value='".$datas[$i][2]."'></td>"."\n";
			$html.= "<td><select class='data' name='db[".$i."][3]'>";
			$html.= "<option value=''></option>"."\n";
			for($j=0,$c2=count($lists_array);$j<$c2;$j++){
				$sel = ($lists_array[$j]==$datas[$i][3])?"selected":"";
				$html.= "<option value='".$lists_array[$j]."' ".$sel.">".$lists[$lists_array[$j]]."</option>"."\n";
			}
			$html.= "</select></td>"."\n";
			$html.= "<td><input type='text' class='data' name='db[".$i."][4]' value='".$datas[$i][4]."'></td>"."\n";
			$html.= "</tr>"."\n";
		}
		return $html;
	}
	*/
}