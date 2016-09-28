<?php
	setlocale(LC_TIME, "de_DE");
	ini_set('display_errors', 'On');
	error_reporting(E_ALL | E_STRICT);

	include './assets/TwitterController.php';
	include './assets/controller.php';

	$cnfg_file = file_get_contents('./secret/config.json');
	$cnfg = json_decode($cnfg_file, True);
	$notice_messages = [];

	$twtr 			= new TwitterController($cnfg['twtr_user'], $cnfg['twtr_key'], $cnfg['twtr_secret']);
	$mailman 		= new MailController($cnfg);

	if (mail_pending()) {
		process_mail($mailman);
		$notice_messages[] = 'Danke für ihre Kontaktanfrage';
		//echo "<meta http-equiv='refresh' content='0'>";
	}

	$yt_links = preg_split("/((\r?\n)|(\r\n?))/", $cnfg['yt_list']);
?>

<!DOCTYPE html>
<html lang="de">

<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width,initial-scale=1" />

	<!-- search meta -->
	<title>Generation Energiewende</title>
	<meta name="author" content="Nicolai Ferchl" />
	<meta name="description" content="[]" />
	<meta name="keywords" content="[]" />
	<meta name="robots" contents="[]" />

	<!-- social meta -->
	<meta property='og:title' content='Generation Energiewende' />
	<meta property='og:type' content='[]' />
	<meta property='og:description' content='[]' />
	<meta property='og:image' content='assets/share.jpg' />
	<meta property='og:url' content='[]' />
	<meta property='og:site_name' content='[]' />
	<meta name='twitter:card' content='[]' />

	<!-- styles -->
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/2.1.0/normalize.css" />
	<link rel="stylesheet" href="assets/main.css" />
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,700" rel="stylesheet">

	<!-- icons -->
	<link rel="shortcut icon" href="assets/favicon.ico" />

	<!-- head scripts -->
	<!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>


<body>

	<header>
		<h1>Generation Energiewende</h1>
	</header>

	<main>
		<section class="youtube">
			<div class="content">
				<h3>YouTube</h3>
				<div class="carousel">
					<?php
						foreach ($yt_links as $key => $link) {
							if($link != '') {
								echo '<iframe class="carousel_item" width="560" height="315" src="https://www.youtube.com/embed/' . $link . '?showinfo=0&modestbranding=1" frameborder="0" allowfullscreen></iframe>';
							}
						}
					?>
				</div>
			</div>
		</section>

		<!--<section class="twitter">
			<div class="content">
				<h3>Twitter</h3>
				<?php echo $twtr->draw() ?>
			</div>
		</section>
		-->
	</main>

	<div class="clearer"></div>
	<footer>
		<?php
		if ($notice_messages != []){
			foreach ($notice_messages as $key => $value) {
				echo "<div class=\"notice_message\">".$value."</div>";
			}
		}
		?>
		<div class="footer-wrapper">
		<small> &copy; 2016 </small>
		<section class="impressum">
			<a class="btn" >Impressum</a>
			<div class="content">
				<a class="btn" href="https://github.com/mrtukkin/generation-energiewende">Code auf Git</a>
			</div>
		</section>

		<section class="contact">
			<a class="btn" >Kontakt</a>
			<form id="CONTACT_FORM" class="content" method="post" >
				<h2>Schreib uns:</h2>
				<div class='name'>
					<input id="CONTACT_FORM_FIRST_NAME" class='first' name='first_name' placeholder='Vorname' type='text'>
					<input id="CONTACT_FORM_LAST_NAME" class='last' name='last_name' placeholder='Nachname' type='text'>
				</div>
				<div class='mailbox'>
					<input id="CONTACT_FORM_MAIL" class='email' name='mail_adress' placeholder='E-mail' type='text'>
				</div>
				<div class='message'>
					<textarea id="CONTACT_FORM_MESSAGE" name='message_text' placeholder='Deine Nachricht!'></textarea>
				</div>
				<textarea name="notice_messages"></textarea>
				<div class="btnbx">
					<button type="button" id="CONTACT_FORM_SUBMIT">Abschicken!</button>
				</div>
			</form>
		</section>


		<section class="login">
			<a class="btn">Login</a>
			<form id="LOGIN_FORM" class="content" method="post" action="admin.php" >
				<input id="LOGIN_FORM_PASSWORD" class='pass' name='password' placeholder='password' type='password'>
				<div class="btnbx">
					<button type="submit" id="LOGIN_FORM_SUBMIT">Enter</button>
				</div>
			</form>
		</section>
		</div>
	</footer>


	<!-- foot scripts -->
	<script src="assets/main.js"></script>

	<script>
		var form = document.getElementById("CONTACT_FORM");
		function validateEmail(email) {
    		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    		return re.test(email);
		}

		document.getElementById("CONTACT_FORM_SUBMIT").addEventListener("click", function () {
			var go = true;

			var element = document.getElementById('CONTACT_FORM_MAIL');
			console.log(validateEmail(element.value));
			if (validateEmail(element.value) != true | element.value.length == 0) {
				element.classList.add("wrongInput");
				go = false;
			}else if (element.classList.contains("wrongInput")){
				element.classList.remove("wrongInput");
			}

			var element = document.getElementById('CONTACT_FORM_FIRST_NAME');
			if (element.value.length > 150 | element.value.length == 0) {
				element.classList.add("wrongInput");
				go = false;
			}else if (element.classList.contains("wrongInput")){
				element.classList.remove("wrongInput");
			}

			var element = document.getElementById('CONTACT_FORM_LAST_NAME');
			if (element.value.length > 150 | element.value.length == 0) {
				element.classList.add("wrongInput");
				go = false;
			}else if (element.classList.contains("wrongInput")){
				element.classList.remove("wrongInput");
			}

			var element = document.getElementById('CONTACT_FORM_MESSAGE');
			if (element.value.length == 0) {
				element.classList.add("wrongInput");
				go = false;
			}else if (element.classList.contains("wrongInput")){
				element.classList.remove("wrongInput");
			}

			if(go){
  				form.submit();
  			}
		});
	</script>

</body>
</html>