
(function(tool){
	var $={};
	
	$.set=function(){
		
		
	};
	
	
	$.list={
		add:function(){
			document.form1.action.value = "add";
			document.form1.submit();
			/*
			var table = document.getElementById("master");
			var id_no = table.getElementsByClassName('id_no');
			var max = 0;
			//console.log(id_no.length);
			var last_tr = id_no[id_no.length-1].parentNode;
			var new_tr  = last_tr.innerHTML;
			//last_tr.parentNode.appendChild(new_tr);
			last_tr.parentNode.innerHTML += "<tr>"+new_tr+"</tr>";
			
			//value-rewrite
			var inputs = id_no[id_no.length-1].getElementsByTagName("input");
			for(i=0;i<inputs.length;i++){
				//flg
				if(inputs[i].type=='checkbox'){
					inputs[i].checked = false;
				}
				//id
				else if(inputs[i].getAttribute("data-type")=="id"){
					//console.log(typeof(inputs[i].value));
					inputs[i].value = inputs[i].value++;
				}
				//else
				else{
					inputs[i].value = "";
				}
			}
			//console.log(id_no.length);
			*/
		}
	};
	
	$.lists={
		add:function(){
			var id = window.prompt("マスター名を入力してください。");
			document.form0.master_title.value = id;
			document.form0.action.value = "lists_add";
			document.form0.submit();
		},
		del:function(){
            document.form0.action.value = "lists_del";
            document.form0.submit();
		}
	};
	
	$.inis_change=function(elm){
        var url  = location.href.split("?");
        var tool = document.form2.tool.value;
        var menu = document.form2.menu.value;
        if(elm.value){
            location.href = url[0]+'?tool='+tool+"&menu="+menu+"&ini="+elm.value;
        }
        else{
            location.href = url[0]+'?tool='+tool+"&menu="+menu;
        }
    };
	
	
	//$IDEACOMPO.lib.event(window,"load",$.set);
	
	if(typeof(window.$IDEACOMPO)=='undefined'){window.$IDEACOMPO={}}
	window.$IDEACOMPO[tool]=$;
	return $;
})("db");
