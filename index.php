<?php
	setlocale(LC_TIME, "de_DE");
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	include './assets/TwitterController.php';
	include './assets/config';

	$twtr = new TwitterController(TWITTER_USER, TWITTER_KEY, TWITTER_SECRET);
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
					<iframe class="carousel_item" width="560" height="315" src="https://www.youtube.com/embed/D_6p6IeBYSA?rel=0&showinfo=0&modestbranding=1" frameborder="0" allowfullscreen></iframe>
					<iframe class="carousel_item" width="560" height="315" src="https://www.youtube.com/embed/O72SR6V9CdU?rel=0&showinfo=0&modestbranding=1" frameborder="0" allowfullscreen></iframe>
					<iframe class="carousel_item" width="560" height="315" src="https://www.youtube.com/embed/scJReCDgNeo?rel=0&showinfo=0&modestbranding=1" frameborder="0" allowfullscreen></iframe>

				</div>
			</div>
		</section>

		<section class="twitter">
			<div class="content">
				<h3>Twitter</h3>
				<?php echo $twtr->draw() ?>
			</div>
		</section>
	</main>

	<div class="clearer"></div>
	<footer>
		<small> &copy; 2016 </small>
		<section class="impressum">
			<a class="btn" >Impressum</a>
			<div class="content">
				Musterfirmenname Gesellschaft mit beschränkter Haftung(1)<br>
				Musterstraße 1<br>
				D-12345 Musterstadt<br>
				<br>
				Vertretungsberechtiger Geschäftsführer: Maximilian Mustermann<br>
				<br>
				Telefon: 123/123456(2)<br>
				Fax: 123/123457<br>
				<br>
				E-Mail: info@musternamegmbh.de<br>
				<br>
				Registergericht: Amtsgericht Musterstadt<br>
				Registernummer: HRB 12345<br>
				Stammkapital: 25.000 Euro(3)<br>
				<br>
				Umsatzsteuer-Identifikationsnummer gem. § 27a UStG: DE 123456789<br>
				Inhaltlich Verantwortlicher: Maximilian Mustermann (Anschrift s.o.)(4)
			</div>
		</section>

		<section class="contact">
			<a class="btn" >Kontakt</a>
			<div class="content">
				<h2>Schreib uns:</h2>
				<div class='name'>
					<input class='first' placeholder='Vorname' type='text'>
					<input class='last' placeholder='Nachname' type='text'>
				</div>
				<div class='mailbox'>
					<input class='email' placeholder='E-mail' type='text'>
				</div>
				<div class='message'>
					<textarea placeholder='Deine Nachricht!'></textarea>
				</div>
				<div class="btnbx">
					<button>Abschicken!</button>
				</div>
			</div>
		</section>
	</footer>


	<!-- foot scripts -->
	<script src="assets/main.js"></script>

</body>
</html>