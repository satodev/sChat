document.onreadystatechange = function(){
	if(document.readyState == "complete"){
		function ajaxLoad(){
			var x = new XMLHttpRequest();
			x.onreadystatechange = function(){
				if(x.readyState== 4 && x.status == 200){
						console.log(x.responseText);
				}
			}
			x.open("GET","index.php?id=1",true);
			x.send();
		}
	}
}
