document.onreadystatechange = function(){
	if(document.readyState == "complete"){
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
	mm = document.getElementById('main_menu');
	for(i in  mm.children){
		console.log(i);
		if(i == 'subscribe_container'){
			//roll on all those children and get the id
		}
	}
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
