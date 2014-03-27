<?


class lists{
    
    function index(){
        //return "INDEX-class-test";
        $tpl = new TEMPLATE();
        
        return $tpl->read_tpl(TOOL.$_REQUEST[tool].'/tpl/'.$_REQUEST[menu].'.html');
        
    }
    
    function list_view($tool,$user){
        
        
        
        //return $tool."/".$user;
        
        $data_path = 'data/'.$tool.'/'.$user.'/';
        
        // check
        if(!is_dir($data_path."data")){return;}
        
        $folder = new FOLDER();
        
        //id-set
        $grp = $this->read_group($tool,'himoku.js');
        
        //master-set
        $mst = $this->read_master($tool,$user,'master');
        
        $html='';
        $num = 1;
        
        $data = $this->read_data($tool,$user);
        for($i=0,$c1=count($data);$i<$c1;$i++){
            
            $data[$i] = str_replace(array("\r","\n"),'',$data[$i]);
            
            if(!$data[$i]){continue;}
            
            $sp = explode(",",$data[$i]);
            
            if(!$sp[0]){continue;}
            
            $html.= $this->list_html($num,$grp,$mst,$sp);
            
            $num++;
            
        }
        
        return $html;
    }
    
    function read_data($tool,$user,$mode='normal',$id=''){
        
        $data_path = 'data/'.$tool.'/'.$user.'/data/data.dat';//die($data_path);
        if(!file_exists($data_path)){return;}
        
        unset($res);
        //exec("awk -F, -v mode='".$mode."' -f tool/".$tool."/awk/lists.awk ".$data_path,$res);
        exec("awk -F, -v mode='".$mode."' -v id=".(($id)?$id:'""')." -f tool/".$tool."/awk/lists.awk ".$data_path,$res);
        
        return $res;
    }
    
    function read_group($tool,$file_name){
        $himoku_dat = TOOL.$tool."/data/".$file_name;
        
        if(!file_exists($himoku_dat)){return;}
        
        $txt = file_get_contents($himoku_dat);
        if(!$txt){return;}
        $txt = str_replace(array("\n","\r"),"",$txt);
        
        $json = json_decode($txt);
        
        $html = "";
        
        if($json){
            unset($mst);
            foreach($json as $key => $val){
                /*
                //$html.= $key;
                $html.= "<label class='group' data-value='".$key."'>";
                    $html.= "<img class='icon' src='".TOOL.$_REQUEST[tool]."/img/".$val->{"img"}."'>".$val->{"name"}." ";
                $html.= "</label>";
                */
                $mst[$key] = $val->{"name"};
            }
        }
        
        return $mst;
    }
    
    function read_master($tool,$user,$file_name){
        unset($mst);
        $master = new MASTER();
        $data_master = $master->read($tool,$user,$file_name);
        if($data_master){
            $data_masters = explode("\n",$data_master);
            for($i=0,$c=count($data_masters);$i<$c;$i++){
                $sp = explode(',',$data_masters[$i]);
                if($sp[0]!=0 || !$sp[1]){continue;}
                
                $mst[$sp[1]] = $sp[2];
            }
        }
        return $mst;
    }
    
    function data2list(){
        
    }
    
    function list_html($num,$grp,$mst,$list){
        
        unset($html);
        
        $html.= '<tr class="line" data-id="'.$list[0].'">';
            $html.= '<th class="num">'.$num.'</th>';
            $html.= '<td>'.$list[2].'</td>';//date
            if($grp){
                $html.= '<td>'.(($list[7])?$grp[$list[7]]:'').'</td>';//group
            }
            if($mst){
                $html.= '<td>'.(($list[8])?$mst[$list[8]]:'').'</td>';//master
            }
            
            $html.= '<td>'.$list[4].'</td>';//price
            $html.= '<td>'.$list[5].'</td>';//count
            $html.= '<td>'.$list[6].'</td>';//sum
        
            $html.= '<td>'.$list[9].'</td>';//message
            
            $html.= '<td><input class="del" type="button" value="Del" data-id="'.$list[0].'" data-date="'.$list[2].'"></td>';
            
        $html.= '<tr>';
        $html.= "\n";
        
        return $html;
    }
    
    
    function del(){
        
        $data_path = 'data/'.$_REQUEST[tool].'/'.$_REQUEST[uid].'/data/data.dat';
        
        if(file_exists($data_path)){
            
            $dat = $_REQUEST[data][id].",,del"."\n";
            //$dat = $_REQUEST[data][id].",".$_REQUEST[data][date].",del"."\n";
            
            file_put_contents($data_path , $dat , FILE_APPEND);
        }
        
        $url = new URL();
        header("Location: ".$url->url()."?tool=".$_REQUEST[tool]."&menu=".$_REQUEST[menu]);
    }
    
    function write(){
        return "INDEX-write";
    }
    
}