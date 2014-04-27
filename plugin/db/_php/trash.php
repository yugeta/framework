<?

class trash{
    
    function index(){
        
        $tpl = new TEMPLATE();
        
        return $tpl->read_tpl(TOOL.$_REQUEST[tool].'/tpl/'.$_REQUEST[menu].'.html');
    }
    
    function list_view($tool,$user){
        
        $lists = new lists();
        
        //return $tool."/".$user;
        
        $data_path = 'data/'.$tool.'/'.$user.'/';
        
        // check
        if(!is_dir($data_path."data")){return;}
        
        $folder = new FOLDER();
        
        //id-set
        $grp = $lists->read_group($tool,'himoku.js');
        
        //master-set
        $mst = $lists->read_master($tool,$user,'master');
        
        $html='';
        $num = 1;
        
        $data = $lists->read_data($tool,$user,'trash');
        //die("trash:".count($data));
        
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
    
    function list_html($num,$grp,$mst,$list){
        
        unset($html);
        
        $html.= '<tr class="line" data-id="'.$list[0].'">';
            $html.= '<th class="num">'.$num.'</th>';
            $html.= '<td>'.$list[2].'</td>';//date
            
            //$html.= '<td>'.$list[6].'</td>';//group
            if($grp){
                $html.= '<td>'.(($list[7])?$grp[$list[7]]:'').'</td>';//master
            }
            if($mst){
                $html.= '<td>'.(($list[8])?$mst[$list[8]]:'').'</td>';//master
            }
            
            $html.= '<td>'.$list[4].'</td>';//price
            $html.= '<td>'.$list[5].'</td>';//count
            $html.= '<td>'.$list[6].'</td>';//sum
            
            $html.= '<td>'.$list[9].'</td>';//message
            
            $html.= '<td><input class="bak" type="button" value="Back" data-id="'.$list[0].'"></td>';
            
        $html.= '<tr>';
        $html.= "\n";
        
        return $html;
    }
    
    // back from the trash.
    function bak(){
        
        $data_path = 'data/'.$_REQUEST[tool].'/'.$_REQUEST[uid].'/data/data.dat';
        
        if(file_exists($data_path)){
            
            $dat = $_REQUEST[data][id].",,bak"."\n";
            
            file_put_contents($data_path , $dat , FILE_APPEND);
        }
        
        $url = new URL();
        header("Location: ".$url->url()."?tool=".$_REQUEST[tool]."&menu=".$_REQUEST[menu]);
    }
    
}