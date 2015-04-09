<?php

class fw_lists extends fw_define{
    function viewDataFiles(){
        if(!is_dir("data")){return;}
        $lists = scanDir("data");
        $html = "";
        for($i=0;$i<count($lists);$i++){
            if($lists[$i]=="." || $lists[$i]==".."){continue;}
            $html.= "<tr>";
            $date = str_replace(".log","",$lists[$i]);
            $y = substr($date,0,4);
            $m = substr($date,4,2);
            $d = substr($date,6,2);
            $html.= "<td>".$y."/".$m."/".$d."</td>"."\n";

            unset($data);
            exec("awk -F, '{print $4}' "."data/".$lists[$i]."|wc -l" , $data);
            $html.= "<td>".$data[0]."</td>"."\n";

            unset($data);
            exec("awk -F, '{print $4}' "."data/".$lists[$i]."|sort|uniq|wc -l" , $data);
            $html.= "<td>".$data[0]."</td>"."\n";

            $html.= "</tr>";
        }
        return $html;
    }
}
