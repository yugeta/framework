
(function(){
    var $={};
    
    //初期設定
    $.set=function(){
        
        var elm = document.getElementsByClassName('bak');
        for(var i=0;i<elm.length;i++){
            elm[i].onclick=function(){
                var id = this.getAttribute('data-id');
                
                $.data_send(id);
            };
        }
        
    };
    
    $.data_send=function(id){
        if(!document.form1){return}
        if(!id){return}
        
        //confirm
        var msg="このデータをゴミ箱から戻してもよろしいですか？";
        
        if(!confirm(msg)){return}
        
        document.form1['data[id]'].value   = id;
        
        document.form1.submit();
    };
    
    
    $IDEACOMPO.lib.event(window,"load",$.set);
    
    if(typeof(window.$IDEACOMPO)=='undefined'){window.$IDEACOMPO={}}
    window.$IDEACOMPO.lists=$;
    return $;
})();
