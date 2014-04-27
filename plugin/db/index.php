<?

/**
* ACBook
* 単語：household-account-books-petty_cash_book
* 概要：家計簿システム（小遣い帳）
* 
**/

//$tool = new TOOL();

if($_REQUEST[menu]){
    if(class_exists($_REQUEST[menu])){
        if(!$_REQUEST[action]){$_REQUEST[action]='index';}
        
        //$GLOBALS[contents][html] = call_user_func(array($_REQUEST[menu],$_REQUEST[action]));
        
        eval('$cls = new '.$_REQUEST[menu].'();');
        
        $GLOBALS[contents][html] = call_user_func(array($cls,$_REQUEST[action]));
        
    }
    else if(file_exists(TOOL.$_REQUEST[tool].'/tpl/'.$_REQUEST[menu].'.html')){
        $GLOBALS[contents][html] = $tpl->read_tpl(TOOL.$_REQUEST[tool].'/tpl/'.$_REQUEST[menu].'.html');
    }
}
/*
else if($_REQUEST[menu]=='input'){//die($_REQUEST[menu]));
    
    if($_REQUEST[action]=='write'){
        $input = new INPUT();
        $input->write($_REQUEST[data],$_REQUEST[tool],$_REQUEST[user]);
        
        $url = new URL();
        header("Location: ".$url->url()."?tool=".$_REQUEST[tool]."&menu=".$_REQUEST[menu]);
    }
    
    $GLOBALS[contents][html] =  $tpl->read_tpl(TOOL.$_REQUEST[tool].'/tpl/'.$_REQUEST[menu].'.html');
}



else if($_REQUEST[menu]=='master'){
    
    $master = new MASTER();
    
    if($_REQUEST[action]=='write'){
        $master->write($_REQUEST[tool],UID);
    }
    
    $GLOBALS[contents][html] =  $tpl->read_tpl(TOOL.$_REQUEST[tool].'/tpl/'.$_REQUEST[menu].'.html');
}

else if($_REQUEST[menu]=='mobile_input'){
    //mobile_input();
    echo $tpl->read_tpl(TOOL.$_REQUEST[tool].'/tpl/'.$_REQUEST[menu].'.html');
    exit;
}

else if($_REQUEST[menu] && !$_REQUEST[action] && file_exists(TOOL.$_REQUEST[tool].'/tpl/'.$_REQUEST[menu].'.html')){
    
    //die(TOOL.$_REQUEST[tool].'/tpl/'.$_REQUEST[menu].'.html');
    $GLOBALS[contents][html] = $tpl->read_tpl(TOOL.$_REQUEST[tool].'/tpl/'.$_REQUEST[menu].'.html');
}


//default
else{
    $GLOBALS[contents][html] =  $tpl->read_tpl(TOOL.$_REQUEST[tool].'/tpl/index.html');
    //$GLOBALS[contents][html] = $tool->index();
}
*/
