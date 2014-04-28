
{
    
    # normal-mode
    #if(mode=="normal"){
    #    if($3=="del"){D[$1]=""}
    #    else{D[$1]=$0}
    #}
    
    # trash-mode
    #else if(mode=="trash"){
    #    if($4){D[$1]=$0}
    #    if($3=="del"){T[$1]=""}
    #}
    
    if(!$4){
        if(id && id!=$1){
            #D[$1]=$0
        }
        else{
            D[$1]=$0
        }
    }
    else if($4=="del"){
        T[$1]++;
    }
    else if($4=="bak"){
        T[$1]=0;
    }
}

END{
    # normal-mode
    if(mode=="normal"){
        for(i in D){
            if(D[i] && !T[i]){
                print D[i];
                #print id":"i;
            }
        }
    }
    
    # trash-mode
    else if(mode=="trash"){
        for(i in T){
            if(T[i] && D[i]){
                print D[i];
            }
        }
    }
    
}
