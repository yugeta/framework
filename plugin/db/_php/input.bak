<?

class input{
    
    function index(){
        
        if($_REQUEST[id]){
            //die("ID:".$_REQUEST[id]."/".UID);
            $lists = new lists();
            
            $data = $lists->read_data($_REQUEST[tool],UID,'normal',$_REQUEST[id]);
            
            //return join("<br>",$data);
            //for($i=0,$c=count($data);$i<$c;$i++){
                $sp = explode(',',$data[0]);
                $GLOBALS[data][date]  = $sp[2];
                $GLOBALS[data][y]  = substr($sp[2],0,4);
                $GLOBALS[data][m]  = substr($sp[2],4,2);
                $GLOBALS[data][d]  = substr($sp[2],6,2);
                $GLOBALS[data][mode]  = $sp[3];
                $GLOBALS[data][price] = $sp[4];
                $GLOBALS[data][count] = $sp[5];
                $GLOBALS[data][sum]   = ($sp[4]*$sp[5]);
                $GLOBALS[data][group] = $sp[7];
                $GLOBALS[data][master]= $sp[8];
                $GLOBALS[data][memo]  = ($sp[9]);
                
                //break;
            //}
        }
        else{
            //$date = new date();
            $GLOBALS[data][y]  = date(Y);
            $GLOBALS[data][m]  = date(m);
            $GLOBALS[data][d]  = date(d);
            $GLOBALS[data][count] = 1;
        }
        
        $tpl = new TEMPLATE();
        return $tpl->read_tpl('tool/'.$_REQUEST[tool].'/tpl/'.$_REQUEST[menu].'.html');
    }
    /*
    //起動処理
    function view(){
        
        //$GLOBALS[sum]="aa";
        
        echo $tpl->read_tpl('tool/'.$_REQUEST[tool].'/tpl/index.html');
        
    }
    */
    
    
    //
    function master_read($tool,$user,$file,$master=""){
        
        $path = "data/".$tool."/".$user."/master/".$file.".dat";
        
        if(!file_exists($path)){return;}
        
        $data = file_get_contents($path);
        if(!$data){return;}
        
        $datas = explode("\n",$data);
        
        for($i=0,$c=count($datas);$i<$c;$i++){
            
            if(!$datas[$i]){continue;}
            
            $sp = explode(",",$datas[$i]);
            
            if($sp[0]!='0'){continue;}
            
            if($master && $master==$sp[1]){$sel="selected";}
            else{$sel = "";}
            
            $html.= "<option value='".$sp[1]."' ".$sel.">".$sp[2]."</option>";
        }
        
        return $html;
    }
    
    function himoku_list($group=""){
        $himoku_dat = TOOL.$_REQUEST[tool]."/data/himoku.js";
        
        
        if(!file_exists($himoku_dat)){return;}
        //return $himoku_dat;
        
        $txt = file_get_contents($himoku_dat);
        if(!$txt){return;}
        $txt = str_replace(array("\n","\r"),"",$txt);
        
        $json = json_decode($txt);
        
        $html = "";
        
        if($json){
            foreach($json as $key => $val){
                //$html.= $key;
                $html.= "<label class='group' data-value='".$key."'>";
                    $html.= "<img class='icon' src='".TOOL.$_REQUEST[tool]."/img/".$val->{"img"}."'>".$val->{"name"}." ";
                $html.= "</label>";
            }
        }
        
        return $html;
    }
    
    function write($d=null,$tool=null,$user=null){
        
        $input = new input();
        
        if($d==null){
            $d = $_REQUEST[data];
        }
        if($tool==null){
            $tool = $_REQUEST[tool];
        }
        if($user==null){
            $user = $_REQUEST[uid];
        }
        
        //
        unset($data);
        
        $d[y] = sprintf('%04d',$d[y]);
        $d[m] = sprintf('%02d',$d[m]);
        $d[d] = sprintf('%02d',$d[d]);
        
        //data write
        //[ 0:date 1:input-datetime , 2:data[price] 3:data[count] 4:*sum(2*3) 4:data[group] 5:data[master] 6:data[etc] ]
        if($_REQUEST[data][id]){
            $data[0] = $_REQUEST[data][id];
        }
        else{
            $data[0] = date(YmdHis);
        }
        $data[1] = date(YmdHis);
        $data[2] = $d[y].$d[m].$d[d];
        $data[3] = "";
        
        $price = $input->data_value_format($d[price]);
        $count = $input->data_value_format($d[count]);
        $data[4] = $price;
        $data[5] = $count;
        $data[6] = ($price * $count);
        
        $data[7] = $input->data_value_format($d[group]);
        $data[8] = $input->data_value_format($d[master]);
        $data[9] = $input->data_value_format($d[memo]);
        
        $dir  = 'data/'.$tool.'/'.$user.'/data/';
        if(!is_dir($dir)){
            mkdir($dir,true);
        }
        
        //$path = $dir.$d[y].$d[m].'.dat';
        $path = $dir.'data.dat';
        
        file_put_contents($path , join(',',$data)."\n" , FILE_APPEND);
        
        //$input = new INPUT();
        //$input->write($_REQUEST[data],$_REQUEST[tool],$_REQUEST[user]);
        
        $url = new URL();
        header("Location: ".$url->url()."?tool=".$tool."&menu=".$_REQUEST[menu]);
        
    }
    
    function data_value_format($data){
        
        $data = str_replace("\r",'',$data);
        $data = str_replace("\n",'',$data);
        
        $data = str_replace(',','&#44;',$data);
        
        return $data;
    }
    
}