<?

/**
* テンプレート処理
* 
* ▶class
* 　　<!--%class:***:%%%(---)%-->
* 　　param:*** class名
* 　　param:%%% function
* 　　param:--- 受け渡し値
**/

class TEMPLATE{
	
    public $test="template_test";
    
	function read_tpl($file){
		
		//ファイルが存在しなければ処理しない
		if(!file_exists($file)){return;}
		
		//ファイルを読み込み
		$tpl = file_get_contents($file);
		
		//置換処理
        $tpl =  $this->change_tpl($tpl,'#');
		return $this->change_tpl($tpl);
	}
	
	function change_tpl($tpl , $sp="%"){
		
		
		if(preg_match_all('@<!--'.$sp.'(.*?)'.$sp.'-->@' , $tpl  , $fnc)){
			
			for($i=0,$c=count($fnc[1]);$i<$c;$i++){
				
				$data = explode(":",$fnc[1][$i]);
				
				$key = strtolower($data[0]);
				//$val = join(":",array_splice($data ,1,count($data)));
                
                //２重階層処理
                //$data[1] = $this->change_tpl($data[1],'#');
                
				array_shift($data);
				$val = join(":",$data);
				
				if($key=="function"){
					$tpl = $this->check_function($tpl,$key,$val,$sp);
				}
				else if($key=="request"){
					$tpl = $this->check_request($tpl,$key,$val,$sp);
				}
				else if($key=="post"){
					$tpl = $this->check_post($tpl,$key,$val,$sp);
				}
				else if($key=="get"){
					$tpl = $this->check_get($tpl,$key,$val,$sp);
				}
				else if($key== "server"){
					$tpl = $this->check_server($tpl,$key,$val,$sp);
				}
				else if($key== "define"){
					$tpl = $this->check_define($tpl,$key,$val,$sp);
				}
				else if($key=="tpl"){
					$tpl = $this->check_template($tpl,$key,$val,$sp);
				}
				else if($key=="system"){
					$tpl = $this->check_system($tpl,$key,$val,$sp);
				}
				else if($key=="globals"){
					$tpl = $this->check_globals($tpl,$data[0],$data[1],$sp);
					/*
					try{
						$tpl = $this->check_globals($tpl,$data[0],$data[1],$sp);
					}
					catch(Exception $e){
						die($e->getMessage());
					}
					*/
				}
				else if($key=="if"){
					$tpl = $this->check_if($tpl,$data,$sp);
				}
				else if($key=="class"){
					//$val2 = join(":",array_splice($data ,2,count($data)));
					//die($key."/".$data[1]."/".$val2);
					$data2 = explode(":",$fnc[1][$i]);
					array_shift($data);
					//die($key."/".$data2[1]."/".$val2);
					$tpl = $this->check_class($tpl,$key,$data2[1], join(":",$data),$sp);
				}
			}
		}
		
		return $tpl;
		
	}
	
	//function
	function check_function($tpl,$key,$fnc,$sp="%"){
		
		if(preg_match("@(.*?)\((.*?)\)@" , $fnc , $met)){
			$vals = explode(",",$met[2]);
			for($v=0,$c2=count($vals);$v<$c2;$v++){
				$vals[$v] = $this->change_tpl($vals[$v],"#");
				$vals[$v] = str_replace('"' , "", $vals[$v]);
				$vals[$v] = str_replace("'" , "", $vals[$v]);
			}
			$tpl = str_replace("<!--".$sp.$key.":".$fnc.$sp."-->" , call_user_func_array($met[1] , $vals) , $tpl);
		}
		
		else{
			$tpl = str_replace("<!--".$sp.$key.":".$fnc.$sp."-->" , call_user_func($fnc) , $tpl);
		}
		return $tpl;
	}
	//request
	function check_request($tpl,$key,$val,$sp="%"){
		return str_replace("<!--".$sp.$key.":".$val.$sp."-->" , $_REQUEST[$val] , $tpl);
	}
	//post
	function check_post($tpl,$key,$val,$sp="%"){
		return str_replace("<!--".$sp.$key.":".$val.$sp."-->" , $_POST[$val] , $tpl);
	}
	//get
	function check_get($tpl,$key,$val,$sp="%"){
		return str_replace("<!--".$sp.$key.":".$val.$sp."-->" , $_GET[$val] , $tpl);
	}
	//server
	function check_server($tpl,$key,$val,$sp="%"){
		return str_replace("<!--".$sp.$key.":".$val.$sp."-->" , $_SERVER[$val] , $tpl);
	}
	//define
	function check_define($tpl,$key,$val,$sp="%"){
		return str_replace("<!--".$sp.$key.":".$val.$sp."-->" , constant($val) , $tpl);
	}
	//template
	function check_template($tpl,$key,$val,$sp="%"){
		return str_replace("<!--".$sp.$key.":".$val.$sp."-->" , $this->read_tpl($val) , $tpl);
	}
	//system
	function check_system($tpl,$key,$val,$sp="%"){
		//ymdhis
		if($val == "ymdhis"){
			return str_replace("<!--".$sp.$key.":".$val.$sp."-->" , date(YmdHis) , $tpl);
		}
		
	}
	//GLOBALS
	function check_globals($tpl,$key1,$key2,$sp="%"){//die($key1."/".$key2."/".isset($GLOBALS[$key1][$key2])."/".$GLOBALS[$key1][$key2]);
		
		//echo $key1."/".$key2."/".isset($GLOBALS[$key1])."/".isset($GLOBALS[$key1][$key2])."<br>\n";
		
		if(!isset($GLOBALS[$key1])){$val="";}
		else if(isset($GLOBALS[$key1][$key2])){$val = $GLOBALS[$key1][$key2];}
		else{$val="";}
		/*
		try{
			$val = $GLOBALS[$key1][$key2];
		}
		catch(Exception $e){
			die($key1."/".$key2."/".$e->getMessage());
			//die($key1."/".$key2."/".isset($GLOBALS[$key1][$key2])."/".$GLOBALS[$key1][$key2]);
		}
		//die(isset($GLOBALS[$key1])."/".$GLOBALS[$key1][$key2]);
		*/
		
		return str_replace("<!--".$sp."globals:".$key1.":".$key2.$sp."-->" , $val , $tpl);
		
	}
	//if
	//data[0]：条件文
	//data[1]：結果文字列
	//data[2]：結果文字列(else)
	//※結果文字列内には「:」は使用できない。(&#58;)で使用する。
	function check_if($tpl,$data,$sp="%"){
		
		
		if($data[0]!=""){$check = $data[0];}
		else{$check = 0;}
		
		//die($check);
		
		$val='';
		
		if(eval("if(".$check."){return true;}else{return false;}")){
			$val = $data[1];
		}
		else if($data[2]){
			$val = $data[2];
		}
		
		return str_replace("<!--".$sp."if:".join(":",$data).$sp."-->" , $val , $tpl);
	}
	
	//class
	function check_class($tpl,$key,$class,$val,$sp="%"){
		
		eval('$cls = new '.$class.'();');
		
		if(preg_match("@(.*?)\((.*?)\)@" , $val , $met)){
			$fnc = $met[1];
			
			//$qry = explode(",",$met[2]);
			//$ret = call_user_func_array(array($cls,$fnc) , $qry);
			
			$vals = explode(",",$met[2]);
			
			for($v=0,$c2=count($vals);$v<$c2;$v++){
				$vals[$v] = $this->change_tpl($vals[$v],"#");
				$vals[$v] = str_replace('"' , "", $vals[$v]);
				$vals[$v] = str_replace("'" , "", $vals[$v]);
			}
			
			$ret = call_user_func_array(array($cls,$fnc) , $vals);
			
		}
		else{
			$fnc = $val;   
			$ret = call_user_func(array($cls,$fnc));
		}
		
		return str_replace("<!--".$sp.$key.":".$class.":".$val.$sp."-->" , $ret , $tpl);
	}
	
	
	
	
}