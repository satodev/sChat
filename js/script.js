document.onreadystatechange = function(){
	if(document.readyState == "complete"){
		result = [];
		AuthentificationDisplay();
	}
}

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
function AuthentificationDisplay()
{
	sub_btn = document.getElementById('subcribe_button');
	log_btn = document.getElementById('login_button');
	recursiveChildNodes('main_menu');
	hideSubContForm();
	hideLogContForm();
	sub_btn.onclick = function(e){
		e.preventDefault();
		showSubContForm();
		hideLogContForm();
	}
	log_btn.onclick = function(e){
		e.preventDefault();
		showLogContForm();
		hideSubContForm();
	}
	document.onclick = function(e){
		if(e.target.id !== 'subcribe_button')
		{
			hideSubContForm();
		}
		if(e.target.id !== 'login_button')
		{
			hideLogContForm();
		}
	}
}
function showSubContForm()
{
	sub_cont = document.getElementById("subscribe_container");
	sub_cont_form = sub_cont.getElementsByTagName('form');
	sub_cont_form[0].style.display = "block";
}
function showLogContForm()
{
	log_cont = document.getElementById('login_container');
	log_cont_form = log_cont.getElementsByTagName('form');
	log_cont_form[0].style.display = 'block';
}
function hideSubContForm()
{
	sub_cont = document.getElementById("subscribe_container");
	sub_cont_form = sub_cont.getElementsByTagName('form');
	sub_cont_form[0].style.display = "none";
}
function hideLogContForm()
{
	log_cont = document.getElementById('login_container');
	log_cont_form = log_cont.getElementsByTagName('form');
	log_cont_form[0].style.display = 'none';
}
function recursiveChildNodes(args)
{

	if(typeof args != 'string'){
		elem = [];
		for(var i = 0; i < args.length; i++){
			elem.push(args[i]);	
			container = document.getElementById(elem[i]);
			console.log(container);
			console.log(result);
			// for(var j = 0; j < elem.childNodes.length; j++){
			// 	if(elem.childNodes[j] != null){
			// 		loop = elem.childNodes[j];
			// 		result.push(loop);	
			// 	}	
			// }
			// recursiveChildNodes(result);
		}
	}else{
		start_node = document.getElementById(args);
		console.log(start_node.childNodes.length);
		for(var i = 0; i < start_node.childNodes.length; i++){
			if(start_node.childNodes[i] != null){
				loop = start_node.childNodes[i];
				if(loop.id){
					result.push(loop.id);	
					console.log(result);
				}
			}	
		}
		recursiveChildNodes(result);
	}
	if(result.length >= 10){
		throw new Error('too long');
	}

}
