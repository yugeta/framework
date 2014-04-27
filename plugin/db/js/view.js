(function(tool){
	
	var $={};
	
	//spread sheets set
	$.set=function(){
		
		var cells = document.getElementsByClassName("cell");
		
		//console.log(cells.length);
		
		for(var i=0;i<cells.length;i++){
			var input  = cells[i].getElementsByTagName("input");
			var select = cells[i].getElementsByTagName("select");
			if(input.length){
				$IDEACOMPO.$event.add(input[0],"focus"  ,$.cell._focus);
				$IDEACOMPO.$event.add(input[0],"change" ,$.cell._blur);
			}
			else if(select.length){
				$IDEACOMPO.$event.add(select[0],"focus" ,$.cell._focus);
				$IDEACOMPO.$event.add(select[0],"change",$.cell._blur);
			}
		}
		
	};
	
	$.inis_change=function(elm){
		//if(!elm.value){return}
		//document.form1.submit();
		var url = location.href.split("?");
		var tool = document.form1.tool.value;
		var menu = document.form1.menu.value;
		if(elm.value){
			location.href = url[0]+'?tool='+tool+"&menu="+menu+"&ini="+elm.value;
		}
		else{
			location.href = url[0]+'?tool='+tool+"&menu="+menu;
		}
	};
	
	//cell control
	$.cell={
		_focus:function(evt){
			var e = evt.target;
			e.setAttribute("data-check",e.value);
		},
		_blur:function(evt){
			//console.log("blur:"+evt.target.value);
			var e = evt.target;
			var url = $IDEACOMPO.lib.url();
			var tool = document.form1.tool.value;
			var menu = document.form1.menu.value;
			var ini  = document.form1.ini.value;
			//var col_max = document.getElementsByClassName("data-header")[0].getElementsByTagName("th").length;
			var col_max = $.col_count();
			//console.log(url.url+url.query.tool);
			//return;
			
			//書き換えがなければ処理しない
			if(e.getAttribute("data-check")==e.value){return}
			
			$IDEACOMPO.ajax.start({
				url		: url.url,
				data	: {
					tool:tool,
					menu:menu,
					action:"cell_write",
					ini: ini,
					col_id : e.getAttribute("data-col-id"),
					row_id : e.getAttribute("data-row-id"),
					col_max:col_max,
					value  : e.value
				},
				onSuccess	: function(res){
					console.log(res);
				},
				onError		: function(status_code){ alert('Error! status:' +status_code); },
				onTimeout	: function(){ alert('タイムアウトしました'); }
			});
		}
	};
	
	//
	$.col_count=function(){
		return document.getElementsByClassName("data-header")[0].getElementsByTagName("th").length;
	},
	
	//
	$.row_add=function(){
		var tool = document.form1.tool.value;
		var menu = document.form1.menu.value;
		var ini  = document.form1.ini.value;
		var url = $IDEACOMPO.lib.url();
		var col_max = $.col_count();
		
		//rows
		var max_id=0;
		var data_area = document.getElementById("cell_datas");
		var rows = data_area.getElementsByClassName("id");
		for(var i=0;i<rows.length;i++){
			var id = rows[i].getAttribute("data-row_id");
			if(max_id < id){max_id = id}
		}
		max_id++;
		
		//console.log(col_max);
		
		$IDEACOMPO.ajax.start({
			url		: url.url,
			data	: {
				tool:tool,
				menu:menu,
				action:"add_datas",
				ini:ini,
				col_max:col_max,
				new_id:max_id,
				no:max_id
			},
			onSuccess	: function(res){
				//console.log(res);
				
				var data_area = document.getElementById("cell_datas");
				var tr = document.createElement("tr");
				tr.innerHTML = res;
				data_area.appendChild(tr);
				
				//add-event
				var cells = tr.getElementsByClassName("cell");
				for(var i=0;i<cells.length;i++){
					var input  = cells[i].getElementsByTagName("input");
					var select = cells[i].getElementsByTagName("select");
					if(input.length){
						$IDEACOMPO.$event.add(input[0],"focus"  ,$.cell._focus);
						$IDEACOMPO.$event.add(input[0],"change" ,$.cell._blur);
					}
					else if(select.length){
						$IDEACOMPO.$event.add(select[0],"focus" ,$.cell._focus);
						$IDEACOMPO.$event.add(select[0],"change",$.cell._blur);
					}
				}
			},
			onError		: function(status_code){ alert('Error! status:' +status_code); },
			onTimeout	: function(){ alert('タイムアウトしました'); }
		});
	};
	/*
	$.row_add2=function(){
		//console.log("log");
		
		//
		var data_area = document.getElementById("cell_datas");
		
		//rows
		var max_id=0;
		var rows = data_area.getElementsByClassName("id");
		for(var i=0;i<rows.length;i++){
			var id = rows[i].getAttribute("data-row_id");
			if(max_id < id){max_id = id}
		}
		max_id++;
		
		//cols
		//var data_headers = document.getElementsByClassName("data-header");
		
		//add_cell_datas
		var col_max = $.col_count();
		var add_cell_datas="";
		add_cell_datas+= "<th class='id' data-row_id='"+max_id+"'>"+max_id+"</th>";
		for(var i=1;i<col_max;i++){
			add_cell_datas+= "<td class='cell'><input type='text' data-col_id='"+i+"' data-row_id='"+max_id+"'></td>";
		}
		
		//row-add
		var tr = document.createElement("tr");
		tr.innerHTML = add_cell_datas;
		data_area.appendChild(tr);
		
	};
	*/
	
	$IDEACOMPO.$event.add(window,"load",$.set);
	
	if(typeof($IDEACOMPO)=='undefined'){$IDEACOMPO={}}
	if(typeof($IDEACOMPO[tool])=='undefined'){$IDEACOMPO[tool]={}}
	$IDEACOMPO[tool].datas = $;
	return $;
})("db");