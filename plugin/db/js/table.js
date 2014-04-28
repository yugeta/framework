
(function(tool){
	var $={};
	
	$.set=function(){
		
		
	};
	
	
	$.list={
		add:function(){
			var table = document.getElementsByTagName("table");
			
			for(var i=0;i<table.length;i++){
				if(table[i].className.indexOf('master_table')==-1){continue}
				
				var tbody = table[i].getElementsByTagName("tbody");
				var tr    = tbody[1].getElementsByTagName("tr");
				var new_tr = document.createElement("tr");
				
				//console.log(tr[tr.length-1].innerHTML);
				new_tr.innerHTML+=tr[tr.length-1].innerHTML;
				
				//IDの最大値を取得
				var cnt = 0;
				var id_no = tbody[1].getElementsByClassName("id_no");
				for(j=0;j<id_no.length;j++){
					var input = id_no[j].getElementsByTagName("input");
					if(input[0].value > cnt){
						cnt = input[0].value;
					}
				}
				cnt++;
				
				//console.log(tr.length);
				tbody[1].appendChild(new_tr);
				//console.log(tr.length);
				
				var last_cnt = tr.length - 1;
				var cell  = tr[last_cnt].childNodes;
				//var cell  = tbody[1].getElementsByTagName("td");
				var num=0;
				
				for(var j=0;j<cell.length;j++){
					if(cell[j].nodeType!=1){continue}
					
					if(num==0){
						//console.log(num+"/"+j+"/"+cell[j].innerHTML+"/"+cell[j].innerText);
						//cell[j].innerHTML = "a"+parseInt(cell[j].innerHTML,10)+1;
						var input = cell[j].getElementsByTagName("input");
						
						//input[0].style.setProperty("border","4px solid red","");
						
						//var last_num = e[0].value;
						//cnt = parseInt(input[0].value,10)+1;
						
						//console.log(tr.length+"/"+num+"/"+j+"/"+cnt);
						//console.log(last_cnt+"/"+input[0].value+"/"+cnt);
						
						//input[0].name = "data["+cnt+"][0]";
						input[0].value = cnt;
						
						//console.log(last_cnt+"/"+input[0].value+"/"+cnt);
					}
					else if(num==1){
						//cell[j].getElementsByTagName("input")[0].value = "1";
						var input = cell[j].getElementsByTagName("input");
						//input[0].name = "data["+cnt+"][1]";
						input[0].value = "";
					}
					else if(num==2){
						//cell[j].getElementsByTagName("select")[0].value = "2";
						var input = cell[j].getElementsByTagName("select");
						//input[0].name = "data["+cnt+"][2]";
						input[0].value = "";
					}
					else if(num==3){
						//cell[j].getElementsByTagName("input")[0].value = "3";
						var input = cell[j].getElementsByTagName("input");
						//input[0].name = "data["+cnt+"][3]";
						input[0].value = "";
					}
					else if(num==4){
						//cell[j].getElementsByTagName("input")[0].value = "4";
						var input = cell[j].getElementsByTagName("input");
						//input[0].name = "data["+cnt+"][4]";
						input[0].value = "";
					}
					else if(num==5){
						//cell[j].getElementsByTagName("input")[0].value = "4";
						var input = cell[j].getElementsByTagName("input");
						//input[0].name = "data["+cnt+"][4]";
						input[0].value = "";
					}
					num++;
				}
				
				break;
			}
			
			
		},
		del:function(){
			if(!confirm("このデータを削除しますか？（この操作は取り消せません）")){return}
			document.form1.action.value = 'del';
			document.form1.submit();
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
