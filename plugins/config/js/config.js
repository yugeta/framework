(function(){
	var $$={}

	$$.set=function(){

		var config = document.getElementsByClassName("config");
		for(var i=0;i<config.length;i++){
			$$.event.add(config[i],"change",$$.proc.config_change);
		}

	};

	$$.proc ={
		config_change:function(evt){
			//console.log(evt.target.value);
			var urls = location.href.split("?");


			location.href = urls[0]+"?plugins="+document.form1.plugins.value+"&config="+evt.target.value;
		}
	};


	$$.event = {
		add:function(t, m, f){

			//other Browser
			if (t.addEventListener){
				t.addEventListener(m, f, false);
			}

			//IE
			else{
				if(m=='load'){
					var d = document.body;
					if(typeof(d)!='undefined'){d = window;}

					if((typeof(onload)!='undefined' && typeof(d.onload)!='undefined' && onload == d.onload) || typeof(eval(onload))=='object'){
						t.attachEvent('on' + m, function() { f.call(t , window.event); });
					}
					else{
						f.call(t, window.event);
					}
				}
				else{
					t.attachEvent('on' + m, function() { f.call(t , window.event); });
				}
			}
		}
	};

	$$.event.add(window,"load",$$.set);
	return $$;
})();
