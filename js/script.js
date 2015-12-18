document.onreadystatechange = function(){
	if(document.readyState == "complete"){
		AuthentificationDisplay();
		hideContForm();
		submitFormAction();
	}
}
function AuthentificationDisplay(){
	sub_btn = document.getElementById('subcribe_button');
	log_btn = document.getElementById('login_button');
	logout_btn = document.getElementById('logout_button');
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
	logout_btn.onclick = function(e){
		e.preventDefault();
		sendFormAjax('logout=true');
	}
}
function sendFormAjax(arg){
	ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function(e){
		if(ajax.readyState == 4 && ajax.status == 200){
			console.log(ajax.responseText);
		}
	}
	ajax.open('POST', 'script/ajax.php', true);
	// ajax.open('POST', 'classes/Controller.class.php', true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send(arg);
}
function showSubContForm(){
	sub_cont = document.getElementById("subscribe_container");
	sub_cont_form = document.getElementById('subscribe_form');
	sub_cont_form.style.display = "block";
}
function showLogContForm(){
	log_cont = document.getElementById('login_container');
	log_cont_form = document.getElementById('login_form');
	log_cont_form.style.display = 'block';
}
function hideSubContForm(){
	sub_cont = document.getElementById("subscribe_container");
	sub_cont_form = document.getElementById('subscribe_form');
	sub_cont_form.style.display = "none";
}
function hideLogContForm(){
	log_cont = document.getElementById('login_container');
	log_cont_form = document.getElementById('login_form');
	log_cont_form.style.display = 'none';
}
function hideContForm(){
	//target : avoir l'ensemble des nom de noeuds enfants et des id correspondant a partir du noeud défini en paramètres 
	document.onclick = function(event){
		var i;
		var sub_cont_form_status = false;
		var sub_cont_log_status = false;
		for (i = 0; i < event.path.length; i++){
			if(event.path[i].id == 'subscribe_container' || event.target.id == 'subcribe_button'){
				sub_cont_form_status = true;
				showSubContForm();
				hideLogContForm();
				break;
			}
			if(event.path[i].id == 'login_container' || event.target.id == 'login_button'){
				sub_cont_log_status = true;
				showLogContForm();
				hideSubContForm();
				break;
			}
			if(i == event.path.length - 1) {
				hideLogContForm();
				hideSubContForm();
			}
		}
	}
}
function submitFormAction(){
	//login_variables
	login_submit = document.querySelectorAll('#login_form>input[type=submit]');
	login_input_name = document.querySelectorAll('#login_form>input[name=login]');
	login_input_password = document.querySelectorAll('#login_form>input[name=password]');
	//subcribe_variable
	subscribe_submit = document.querySelectorAll('#subscribe_form>input[type=submit]');
	subscribe_pseudo = document.querySelectorAll('#subscribe_form>input[name=pseudo]');
	subscribe_email = document.querySelectorAll('#subscribe_form>input[name=email]');
	subscribe_name = document.querySelectorAll('#subscribe_form>input[name=name]');
	subscribe_password = document.querySelectorAll('#subscribe_form>input[name=password]');

	login_submit[0].onclick = function(e){
		e.preventDefault();
		if(!login_input_name[0].value){
			alert('fill user before authentificate');
			return false;
		}
		if(!login_input_password[0].value){
			alert('fill password before authentificate');
			return false;	
		}
		sendFormAjax('login=true&user='+b64EncodeUnicode(login_input_name[0].value)+'&pwd='+b64EncodeUnicode(login_input_password[0].value));
	};
	subscribe_submit[0].onclick = function(e){
		e.preventDefault();
		if(!subscribe_pseudo[0].value){
			alert('fill pseudo before subscribe please');
			return false;
		}
		if(!subscribe_email[0].value){
			alert('fill email before subscribe please');
			return false;
		}
		if(!subscribe_name[0].value){
			alert('fill name before subscribe please');
			return false;
		}
		if(!subscribe_password[0].value){
			alert('fill password before subscribe please');
			return false;
		}
		sendFormAjax('subscribe=true&pseudo='+b64EncodeUnicode(subscribe_pseudo[0].value)+'&email='+b64EncodeUnicode(subscribe_email[0].value)+'&name='+b64EncodeUnicode(subscribe_name[0].value)+'&password='+b64EncodeUnicode(subscribe_password[0].value));
	}
}
function b64EncodeUnicode(str) {
	return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
		return String.fromCharCode('0x' + p1);
	}));
}