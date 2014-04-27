
(function(){
    var $={};
    
    //初期設定
    $.set=function(){
        
        //ID指定がある場合のgroup値
        var data_group = document.form1['data[group]'];
        var data_group_value;
        if(data_group && data_group.value){
            data_group_value = data_group.value;
        }
        
        //group切り替えイベント
        var groups = document.getElementsByClassName('group');
        
        for(var i=0;i<groups.length;i++){
            groups[i].onclick=function(){
                var num = this.getAttribute('data-value');
                document.form1['data[group]'].value = num;
                $.himoku_list.view_switchs('close');
                //preview
                var inner = (num)?this.innerHTML:"";
                document.getElementById('himoku_preview').innerHTML = inner;
            };
            //ID指定の場合の表示
            if(data_group_value && groups[i].getAttribute('data-value')==data_group_value){
                //groups[i].style.setProperty('display','block','');
                document.getElementById('himoku_preview').innerHTML = groups[i].innerHTML;
            }
        }
        
        var himoku_button = document.getElementsByClassName('himoku_button');
        for(var i=0;i<himoku_button.length;i++){
            himoku_button[i].onclick = function(){$.himoku_list.view_switchs()};
        }
        var himoku_list = document.getElementsByClassName('himoku_list');
        for(var i=0;i<himoku_list.length;i++){
            var close = himoku_list[i].getElementsByClassName('close');
            for(var j=0;j<close.length;j++){
                close[j].onclick = function(){$.himoku_list.view_switchs('close')};
            }
        }
        
        //data_send
        var data_send = document.getElementsByClassName('data_send');
        for(var i=0;i<data_send.length;i++){
            data_send[i].onclick=function(){
                $.deta_send();
            };
        }
        
        //count up-down
        var cnt = document.getElementsByClassName('count');
        for(var i=0;i<cnt.length;i++){
            cnt[i].onclick=function(){
                var e = document.form1['data[count]'];
                if(!e){return}
                
                var flg = this.getAttribute('data-flg');
                var num = (Number(e.value))?Number(e.value):0;
                
                if(flg=='+'){//console.log('+:'+num);
                    num++;
                }
                else if(flg=='-'){//console.log('-');
                    num--;
                    if(num<1){num=1;}
                }
                //console.log(flg+":"+num);
                
                e.value = num;
                $.price_sum();
            };
        }
        
        var price = document.form1['data[price]'];
        if(price){
            price.onkeyup=function(){
                $.price_sum();
            };
        }
        
    };
    
    //himoku_list_view
    $.himoku_list={
        // flg[ open / close / blank ]
        view_switchs:function(flg){
            var himoku_list = document.getElementsByClassName('himoku_list');
            for(var i=0;i<himoku_list.length;i++){
                
                if(!flg){
                    if(himoku_list[i].getAttribute('data-himoku_list_view')=='open'){
                        flg = 'close';
                    }
                    else{
                        flg = 'open';
                    }
                }
                
                himoku_list[i].setAttribute('data-himoku_list_view',flg);
                himoku_list[i].style.setProperty('display',((flg=='open')?'block':'none'),'');
            }
        }
    };
    
    
    $.deta_send=function(){
        if(!document.form1){return}
        
        var data = document.form1;
        //data-check
        var y      = data['data[y]'];
        var m      = data['data[m]'];
        var d      = data['data[d]'];
        var group  = data['data[group]'];
        var master = data['data[master]'];
        var price  = data['data[price]'];
        var count  = data['data[count]'];
        var memo   = data['data[memo]'];
        
        if(!group.value
        || !price.value
        || !count.value
        || !y.value
        || !m.value
        || !d.value
        ){
            alert('入力されてない箇所があります。');
            return;
        }
        
        //confirm
        
        var msg="";
        msg+= "費目:"+group.value+"\n";
        if(master && master.value){
            msg+= "分類:"+master.value+"\n";
        }
        msg+= "計算:"+count.value+"個"+" × "+price.value+"円\n";
        msg+= "合計:"+(count.value*price.value)+"円\n";
        msg+= "摘要:"+memo.value+"\n";
        
        
        if(!confirm(msg)){return}
        
        //data-send
        document.form1.submit();
        
    };
    
    //金額合計（個数×単価）
    $.price_sum=function(){
        var count = document.form1['data[count]'];
        var price = document.form1['data[price]'];
        var sum   = document.form1['data[sum]'];
        if(!count || !price || !sum){return}
        
        var num_count = (!count.value)?1:Number(count.value);
        var num_price = (!price.value)?0:Number(price.value);
        
        //if(!count.value){count.value = num_count}
        
        
        sum.value = (num_count * num_price);
        
    };
    
    
    $IDEACOMPO.lib.event(window,"load",$.set);
    
    if(typeof(window.$IDEACOMPO)=='undefined'){window.$IDEACOMPO={}}
    window.$IDEACOMPO.acbook=$;
    return $;
})();

(function(){
    $={};
    
    $.deta_send=function(){
        
        var str = '';
        
        str += "-----\n";
        str += document.form1.year.value+"年"+document.form1.month.value+"月"+document.form1.date.value+"\n";
        str += "金額："+document.form1.price.value+"\n";
        str += "種別："+document.form1.group.value+"\n";
        str += "備考--\n";
        str += document.form1.memo.value+"\n";
        str += "-----\n";
        
        if(!confirm(str+'データを送信しますか？')){return}
        //var f = document.form1;
        
        document.form1.submit();
    };
    
    
    if(typeof(window.$IDEACOMPO)=='undefined'){window.$IDEACOMPO={}}
    window.$IDEACOMPO.account=$;
    
    return $;
})();