/**
 HTML Editor
**/
(function(){
	var $={};
	
	$.set=function(){
		var html_editor = document.getElementById("html_editor");
		if(html_editor==null){return}
		
		//console.log(html_editor.contentWindow.document.body);
		
		html_editor.contentWindow.document.body.contentEditable = true;
		html_editor.contentWindow.document.designMode = "on";
	}
	
	
	$IDEACOMPO.$event.add(window,"load",$.set);
	
	return $;
})();