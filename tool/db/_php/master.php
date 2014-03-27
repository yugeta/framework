<?

class master{
	
	function index(){
		$tpl = new TEMPLATE();
		
		return $tpl->read_tpl(TOOL.$_REQUEST[tool].'/tpl/'.$_REQUEST[menu].'.html');
	}
	
	function lists_add(){
		$_REQUEST['ini'] = date(YmdHis).".ini";
		$_REQUEST['data'][0]['master_name'] = $_REQUEST['master_title'];
		$this->write();
	}
	
	function lists_del(){
		$path = "data/".$_REQUEST['tool']."/".UID."/";
		for($i=0,$c=count($_REQUEST[lists]);$i<$c;$i++){
			$data_file = $path.$_REQUEST[lists][$i]['ini'];
			if($_REQUEST[lists][$i]['flg'] && file_exists($data_file)){
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
	
	function write(){
		$url = new URL();
		
		if($_REQUEST['data'] && $_REQUEST['ini']){
			unset($data);
			$dir = "data/".$_REQUEST['tool']."/".UID."/";
			if(!is_dir($dir)){mkdir($dir,0777,true);}
			$path = $dir.$_REQUEST['ini'];
			
			//title
			$data[] = $_REQUEST['data'][0]['master_name']."\n";
			
			for($i=1,$c=count($_REQUEST['data']);$i<$c;$i++){
				unset($str);
				$str[] = ($_REQUEST['data'][$i]['flg'])?1:0;
				for($j=1,$c2=count($_REQUEST['data'][$i]);$j<=$c2;$j++){
					$str[] = $_REQUEST['data'][$i][$j];
				}
				/*
				$str[] = $_REQUEST['data'][$i][1];
				$str[] = $_REQUEST['data'][$i][2];
				$str[] = $_REQUEST['data'][$i][3];
				$str[] = $_REQUEST['data'][$i][4];
				*/
				
				$data[] = join(",",$str)."\n";
			}
			file_put_contents($path , join("",$data));
		}
		$url->move($url->url()."?tool=".$_REQUEST['tool']."&menu=".$_REQUEST['menu']."&ini=".$_REQUEST['ini']);
	}
	
	function lists_array($user){
		if(!is_dir("data/db/".$user)){return;}
		unset($data);
		$folder = new FOLDER();
		$path = "data/db/".$user."/";
		$lists = $folder->lists($path);
		for($i=0,$c=count($lists);$i<$c;$i++){
			if($_REQUEST['ini']==$lists[$i]){continue;}
			if(preg_match("/^(.*?)\.ini$/",$lists[$i],$match)){
				//$data[] = $match[1];
				$lines = explode("\n",file_get_contents($path.$lists[$i]));
				//$name = ($lines[0])?$lines[0]:$match[1];
				$name = $lines[0];
				//$name = str_replace("\n","-",file_exists($path.$lists[$i]));
				//$name = join("/",$lines);
				
				$data[$match[1]] = $name;
			}
		}
		return $data;
	}
	
	function lists($user){
		//return UID;
		//return $user;
		if(!is_dir("data/db/".$user)){return;}
		$folder = new FOLDER();
		$path = "data/db/".$user."/";
		$lists = $folder->lists($path);
		/*
		$html="";
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
				
				$html.= "<div>";
				$html.= "<a href='?tool=".$_REQUEST[tool]."&menu=".$_REQUEST[menu]."&ini=".$lists[$i]."'>".$name."</a>";
				$html.= "</div>";
			}
			
		}
		*/
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
				
				$html.= "<tr>"."\n";
					$html.= "<th><input type='checkbox' class='flg' name='lists[".$i."][flg]'></th>"."\n";
					$html.= "<td>"."\n";
                        $html.= "<input type='hidden' name='lists[".$i."][ini]' value='".$lists[$i]."'>"."\n";
						$html.= "<a href='?tool=".$_REQUEST[tool]."&menu=".$_REQUEST[menu]."&ini=".$lists[$i]."'>".$name."</a>"."\n";
					$html.= "</td>"."\n";
				$html.= "</tr>"."\n";
			}
			
		}
		
		return $html;
	}
	
	function ini2title($ini){
		$ini_file = "data/".$_REQUEST[tool]."/".UID."/".$ini;
		if(!file_exists($ini_file)){return;}
		
	}
	
	function datas_view($user,$list_id=null){
		return $this->datas_html($this->datas($user,$list_id));
	}
	
	function datas($user,$list_id=null){
		//データ有
		if(file_exists("data/db/".$user."/".$list_id)){
			$datas = explode("\n",file_get_contents("data/db/".$user."/".$list_id));
			
			unset($keys);
			for($i=0,$c=count($datas)-1;$i<$c;$i++){
				$datas[$i] = str_replace("\n","",$datas[$i]);
				$datas[$i] = str_replace("\r","",$datas[$i]);
				//if(!$datas[$i]){continue;}
				$keys[] = explode(",",$datas[$i]);
			}
			return $keys;
		}
		//データ無
		else{
			
		}
	}
	
	function datas_html($datas){
		
		$lists = $this->lists_array(UID);
		$lists_array = array_keys($lists);
		
		//if(!$datas){return;}
		$html = "";
		
		$html.= "<input type='hidden' name='data[0][master_name]' value='".$datas[0][0]."'>"."\n";
		
		$html.= "<table class='master_table'>"."\n";
		//header
		$html.= "<tbody>"."\n";
		$html.= "<tr>"."\n";
			$html.= "<th style='width:50px;' class='flg'>Flg</th>"."\n";
			$html.= "<th style='width:50px;'>ID</th>"."\n";
			$html.= "<th style='width:100px;'>Name</th>"."\n";
			$html.= "<th style='width:100px;'>マスター</th>"."\n";
			$html.= "<th>Comment</th>"."\n";
		$html.= "</tr>"."\n";
		$html.= "</tbody>"."\n";
		
		$html.= "<tbody>"."\n";
		for($i=1,$c=count($datas);$i<$c;$i++){
			$html.= "<tr>"."\n";
			//flg
			$html.= "<th class='flg'><input type='checkbox' class='flg' name='data[".$i."][flg]' ".(($datas[$i][0])?"checked":"")."></th>"."\n";
			//contents
			$html.= "<td class='id_no'><input type='text' class='data' name='data[".$i."][1]' value='".$datas[$i][1]."' data-type='id' style='text-align:center;' readonly></td>"."\n";
			$html.= "<td><input type='text' class='data' name='data[".$i."][2]' value='".$datas[$i][2]."'></td>"."\n";
			$html.= "<td><select class='data' name='data[".$i."][3]'>";
			$html.= "<option value=''></option>"."\n";
			for($j=0,$c2=count($lists_array);$j<$c2;$j++){
				$sel = ($lists_array[$j]==$datas[$i][3])?"selected":"";
				$html.= "<option value='".$lists_array[$j]."' ".$sel.">".$lists[$lists_array[$j]]."</option>"."\n";
			}
			$html.= "</select></td>"."\n";
			$html.= "<td><input type='text' class='data' name='data[".$i."][4]' value='".$datas[$i][4]."'></td>"."\n";
			$html.= "</tr>"."\n";
		}
		$html.= "</tbody>"."\n";
		
		//send
		$html.= "<tbody>"."\n";
		$html.= "<tr>"."\n";
			$html.= "<th colspan='5' style='text-align:right;'>"."\n";
				$html.= "<input type='button' value='削除したリストを表示'>"."\n";
				$html.= "<input type='button' value='リスト削除'>"."\n";
				$html.= "<input type='button' value='リスト追加' onclick='".'$IDEACOMPO.db.list.add()'."'>"."\n";
				$html.= "<input type='submit' value='更新'>"."\n";
			$html.= "</th>"."\n";
		$html.= "</tr>"."\n";
		$html.= "</tbody>"."\n";
		
		$html.= "</table>"."\n";
		
		return $html;
	}
}