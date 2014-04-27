
(function(){
    var $={};
    
    //初期設定
    $.set=function(){
        
        var del = document.getElementsByClassName('del');
        for(var i=0;i<del.length;i++){
            del[i].onclick=function(){
                var id = this.getAttribute('data-id');
                //var dt = this.getAttribute('data-date');
                //console.log(id);
                $.data_send(id);
            };
        }
        
        // click->edit
        var line = document.getElementsByClassName('line');
        for(var i=0;i<line.length;i++){
            
            line[i].onclick=function(){
                var id = this.getAttribute("data-id");
                //console.log(id);
                var url = $IDEACOMPO.lib.url();
                //console.log(url.url);
                location.href = url.url+"?tool="+url.query.tool+"&menu=input&id="+id;
            };
            
            /*
            line[i].onclick=(function(i){
                return function(){
                    //console.log(i);
                    
                };
            })(i);
            */
        }
        
    };
    
    $.data_send=function(id){
        if(!document.form1){return}
        if(!id){return}
        
        //confirm
        var msg="このデータを削除してもよろしいですか？";
        
        if(!confirm(msg)){return}
        
        document.form1['data[id]'].value   = id;
        //document.form1['data[date]'].value = dt;
        
        //data-send
        document.form1.submit();
    };
    
    
    
    $IDEACOMPO.lib.event(window,"load",$.set);
    
    if(typeof(window.$IDEACOMPO)=='undefined'){window.$IDEACOMPO={}}
    window.$IDEACOMPO.lists=$;
    return $;
})();
