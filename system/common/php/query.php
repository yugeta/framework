<?
/**
 * Query string control
 * 
 * query = [ key1=val1&key2=val2... ]
 * 
**/

class QUERY{
    
    //key delete
    function key_del($query='',$key=''){
        
        if(!$query){return '';}
        if(!$key){return $query;}
        
        $querys = explode('&',$query);
        unset($new_query);
        
        for($i=0,$c=count($querys);$i<$c;$i++){
            $sp = explode('=',$querys[$i]);//echo $sp[0]."<br>";
            if($sp[0] == $key){continue;}
            
            $new_query[] = $querys[$i];
        }
        //die($query."/".count($querys)."/".join('&',$new_query));
        
        return join('&',$new_query);
    }
    
    //key additional
    // same-key is overwrite
    function key_add($query='',$key='',$val=''){
        
        if(!$key){return $query;}
        
        $quqrys = explode('&',$query);
        unset($new_query);
        
        for($i=0,$c=count($querys);$i<$c;$i++){
            $sp = explode('=',$querys[$i]);
            if($sp==$key){continue;}
            
            $new_query[] = $querys[$i];
        }
        
        $new_query[] = $key.'='.$val;
        
        return join('&',$new_query);
        
    }
    
    
    
}