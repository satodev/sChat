document.onreadystatechange = function(){
	if(document.readyState == "complete"){
		AuthentificationDisplay();
		hideContForm();
	}
}
function AuthentificationDisplay()
{
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
		sendFormAjax('logout');
	}
}
function sendFormAjax(arg)
{
	ajax = new XMLHttpRequest();
	ajax.onreadystatechange = function(e){
		if(ajax.readyState == 4 && ajax.status == 200){
			console.log(ajax.responseText)
		}
	}
	ajax.open('POST', 'script/ajax.php', true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send("logout=true");
}
function showSubContForm()
{
	sub_cont = document.getElementById("subscribe_container");
	sub_cont_form = document.getElementById('subscribe_form');
	sub_cont_form.style.display = "block";
}
function showLogContForm()
{
	log_cont = document.getElementById('login_container');
	log_cont_form = document.getElementById('login_form');
	log_cont_form.style.display = 'block';
}
function hideSubContForm()
{
	sub_cont = document.getElementById("subscribe_container");
	sub_cont_form = document.getElementById('subscribe_form');
	sub_cont_form.style.display = "none";
}
function hideLogContForm()
{
	log_cont = document.getElementById('login_container');
	log_cont_form = document.getElementById('login_form');
	log_cont_form.style.display = 'none';
}
function hideContForm()
{
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
