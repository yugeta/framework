<?

class OPENID{
    
    /*==========
    サイト別認証処理
    ==========*/
    function gmail($session_id=null){
        
        if(!$session_id){return;}
        
        $url = new URL();
        
        $mysite = $url->url();
        
        $data=array(
            'openid.ns'             => 'http://specs.openid.net/auth/2.0',
            'openid.ns.pape'        => 'http://specs.openid.net/extensions/pape/1.0',
            'openid.ns.max_auth_age'=> '300',
            'openid.claimed_id'     => 'http://specs.openid.net/auth/2.0/identifier_select',
            'openid.identity'       => 'http://specs.openid.net/auth/2.0/identifier_select',
            'openid.return_to'      => $mysite.'?mode=openid&service=gmail&action=return&session_id='.$session_id."&check=".$_REQUEST['check']."&cookie_time=".$_REQUEST['cookie_time'],
            'openid.realm'          => $mysite,
            'openid.mode'           => 'checkid_setup',
            'openid.ui.ns'          => 'http://specs.openid.net/extensions/ui/1.0',
            'openid.ui.mode'        => '=popup',
            'openid.ui.icon'        => 'true',
            'openid.ns.ax'          => 'http://openid.net/srv/ax/1.0',
            'openid.ax.mode'        => 'fetch_request',
            'openid.ax.type.email'  => 'http://axschema.org/contact/email',
            'openid.ax.type.language'=> 'http://axschema.org/pref/language',
            'openid.ax.required'    => 'email,language'
        );

        //get
        $keys = array_keys($data);
        for($i=0,$c=count($keys);$i<$c;$i++){
            $q[] = $keys[$i]."=".urlencode($data[$keys[$i]]);
        }
        
        //サイトへ移動
        header("Location: "."https://www.google.com/accounts/o8/ud"."?".join("&",$q));
        
    }
    function facebook($session_id=null){
        
        if(!$session_id){return;}
        
        $url = new URL();
        
        $mysite = $url->url();
        
        /*
        ?api_key=90376669494
          &cancel_url=https%3A%2F%2Fopen.login.yahoo.com%2Fopenid%2Fyrp%2Freturn_to%3Fsid%3D(なんかの文字列)
          &display=popup
          &fbconnect=1
          &next=https%3A%2F%2Fopen.login.yahoo.com%2Fopenid%2Fyrp%2Freturn_to%3Fsid%3D(なんかの文字列)
          &return_session=1
          &session_version=3
          &req_perms=email%2Cfriends_birthday%2Cfriends_education_history%2Cfriends_events%2Cfriends_location%2Cfriends_status%2Cfriends_work_history%2Coffline_access%2Cpublish_stream%2Cread_stream%2Cuser_about_me%2Cuser_activities%2Cuser_birthday%2Cuser_education_history%2Cuser_events%2Cuser_groups%2Cuser_interests%2Cuser_likes%2Cuser_location%2Cuser_religion_politics%2Cuser_status%2Cuser_work_history%2Cxmpp_login
          &v=1.0
          &locale=en_US
        */
        $data=array(
            'oapi_key' => '1404540399816130',
            'cancel_url' => $mysite,
            'display'=> 'popup',
            'fbconnect' => '1',
            'next' => $mysite.'?mode=openid&service=gmail&action=return&session_id='.$session_id,
            //'return_session'      => $mysite.'?mode=openid&service=gmail&action=return&session_id='.$session_id."&openid.id=",
            'return_session' => '1',
            'session_version' => '3',
            'req_perms' => 'email%2Cfriends_birthday%2Cfriends_education_history%2Cfriends_events%2Cfriends_location%2Cfriends_status%2Cfriends_work_history%2Coffline_access%2Cpublish_stream%2Cread_stream%2Cuser_about_me%2Cuser_activities%2Cuser_birthday%2Cuser_education_history%2Cuser_events%2Cuser_groups%2Cuser_interests%2Cuser_likes%2Cuser_location%2Cuser_religion_politics%2Cuser_status%2Cuser_work_history%2Cxmpp_login',
            'v' => '1.0',
            'locale' => 'en_US'
        );

        //get
        $keys = array_keys($data);
        for($i=0,$c=count($keys);$i<$c;$i++){
            $q[] = $keys[$i]."=".urlencode($data[$keys[$i]]);
        }
        
        //サイトへ移動
        //header("Location: "."http://www.facebook.com/developers/apps.php"."?".join("&",$q));
        header("Location: "."https://www.facebook.com/login.php"."?".join("&",$q));
        
    }
    
    
    
    //認証後に対象OPENID別に返り値を取得する。
    function getReturnData($service,$mode){
        
        if($service=="gmail"){
            if($mode=="id"){
                return $_REQUEST['openid_ext1_value_email'];
            }
            else if($mode=="mail"){
                return $_REQUEST['openid_ext1_value_email'];
            }
            else if($mode=='language'){
                return $_REQUEST['openid_ext1_value_language'];
            }
            else if($mode=='res_time'){
                return $_REQUEST['openid_response_nonce'];
            }
            else if($mode=='firstname'){
                
            }
            else if($mode=='lastname'){
                
            }
        }
        else if($service=='facebook'){
            if($mode=="id"){
                
            }
            else if($mode=='mail'){
                
            }
            else if($mode=='language'){
                
            }
            else if($mode=='res_time'){
                
            }
            else if($mode=='firstname'){
                
            }
            else if($mode=='lastname'){
                
            }
        }
        else if($service=='twitter'){
            if($mode=="id"){
                
            }
            else if($mode=='mail'){
                
            }
            else if($mode=='language'){
                
            }
            else if($mode=='res_time'){
                
            }
            else if($mode=='firstname'){
                
            }
            else if($mode=='lastname'){
                
            }
        }
        else if($service=='mixi'){
            if($mode=="id"){
                
            }
            else if($mode=='mail'){
                
            }
            else if($mode=='language'){
                
            }
            else if($mode=='res_time'){
                
            }
            else if($mode=='firstname'){
                
            }
            else if($mode=='lastname'){
                
            }
        }
    }
    
}



