document.onreadystatechange = function(){
	if(document.readyState == "complete"){
		AuthentificationDisplay();
		hideContForm();
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
function hideContForm()
{
	//target : avoir l'ensemble des nom de noeuds enfants et des id correspondant a partir du noeud défini en paramètres 
	document.onclick = function(event){
		var i;
		var sub_cont_form_status = false;
		var sub_cont_log_status = false;
		for (i = 0; i < event.path.length; i++){
			if( event.path[i].id == 'subscribe_container' || event.target.id == 'subcribe_button'){
				sub_cont_form_status = true;
				showSubContForm();
				hideLogContForm();
				break;
			}
			if( event.path[i].id == 'login_container' || event.target.id == 'login_button'){
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
