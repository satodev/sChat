<div id="main_menu" class="container-fluid">
	<ul>
		<li><a href="index.php" title="0">index</a></li>
		<li>
			<a href="chat.php" title="1">chat</a>
		</li>
		<li>
			<a href="rooms.php" title="2">rooms</a>
		</li>
		<li>
			<a href="contact.php" title="3">contact</a>
		</li>
	</ul>
	<section id="subscribe_container">
		<a id="subcribe_button" href="#">Subscribe</a>
		<form action="" method="POST">
			<input type="text" name="type_of_form" value="subscribe" style="visibility:hidden; display:none;">
			<input type="text" name="nickname" placeholder="Pseudo">
			<input type="text" name="email" placeholder="Email">
			<input type="text" name="name" placeholder="Name">
			<input type="password" name="password" placeholder="Password">
			<input type="submit" value="go">
		</form>
	</section>
	<section id="login_container">
		<a id="login_button" href="#">Login</a>
		<form action="" method="POST">
			<input type="text" name="type_of_form" value="login" style="visibility:hidden; display:none;">
			<input type="text" name="name" placeholder="email or pseudo">
			<input type="password" name="password" placeholder="password">
			<input type="submit" value="go">
		</form>
	</section>
</div>