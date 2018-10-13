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

	<!-- styles -->
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,600,700" rel="stylesheet">
	<link rel="stylesheet" href="assets/foundation.min.css" />
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

	<main class="row">
		<section class="column small-4 medium-2 medium-centered  twitter">
			<div class="content">
				<?php echo $twtr->draw() ?>
			</div>
		</section>

		<section class="column small-4 medium-4 youtube">
			<div class="content">
				<h3><a href='https://www.youtube.com/channel/UCovbh_iaO15tVE7Bf4LahDw'>YouTube</a></h3>
				<div class="orbit"  role="region" data-orbit data-auto-play="false" data-pause-on-hover="false" >
					<ul class="orbit-container" >
						<button class="orbit-previous">&#10094;&#xFE0E;</button>
						<button class="orbit-next">&#10095;&#xFE0E;</button>
					<?php
						$is_first_video = True;
						foreach ($yt_links as $key => $link) {
							if($link != '') {
								echo '<li class="orbit-slide';
								echo $is_first_video ? ' is-active' : '';
								echo '">';
								echo '<div class="responsive-embed widescreen">';
								echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $link . '?showinfo=0&modestbranding=1" allowfullscreen></iframe>';
								echo '</div>';
								echo '</li>';
								$is_first_video = False;
							}
						}
					?>
					</ul>
				</div>
			</div>
		</section>
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
			<a class="btn" data-open="impressum_reveal" >Impressum</a>
		</section>
		<div id="impressum_reveal" class="reveal" data-reveal >
			<?php echo $cnfg['impressum']; ?>
			<a href="https://github.com/mrtukkin/generation-energiewende">Code auf Git</a>
		</div>

		<section class="datenschutz">
			<a class="btn" data-open="datenschutz_reveal" >Datenschutzerklärung</a>
		</section>
		<div id="datenschutz_reveal" class="reveal" data-reveal >
<h2>Datenschutzerklärung</h2>
<p>Angaben gemäß § 5 TMG:</p>

<p>Wir bewegen was! e.V.<br>
Rheinstraße 2-6<br>
69126 Heidelberg</p>

<p>Registergericht: Amtsgericht Mannheim<br>
Registernummer: VR333328</p>

<p>E-Mail:<br>
info[at]generation-energiewende.de</p>

<p>(im Folgenden: „Wir“) ist als Betreiber der Website generation-energiewende.de Verantwortlicher für die personenbezogenen Daten der Nutzer*innen der Website (im Folgenden: „du“) im Sinne der Datenschutzgrundverordnung.</p>

<p>Der Schutz der personenbezogenen Daten unserer Besucher*innen ist uns wichtig. Du kannst von uns erwarten, dass wir mit deinen Daten sensibel und sorgfältig umgehen und für eine hohe Datensicherheit sorgen. Wir beachten die Vorschriften des Bundesdatenschutz- und des Telemediengesetzes sowie der Datenschutzgrundverordnung und werden personenbezogene Daten der Nutzer*innen nur in dem in dieser Datenschutzerklärung beschriebenen Umfang verarbeiten.</p>

<h3>1. Welche personenbezogenen Daten erfassen wir?</h3>

<p>Personenbezogene Daten sind solche Daten, die Rückschlüsse auf deine Identität zulassen. Wir erfassen personenbezogene Daten ausschließlich dann, wenn du diese freiwillig an uns preisgibst. Dies können, je nach deiner Nutzung unserer Seite, die nachfolgenden Daten sein:</p>

<p>Unsere Website enthält Links zu Websites von anderen, mit uns nicht verbundenen Anbietern. Nach Anklicken des Links haben wir keinen Einfluss mehr auf die Verarbeitung der übertragenen Daten (wie zum Beispiel der IP-Adresse oder der URL, auf der sich der Link befindet), da das Verhalten Dritter unserer Kontrolle entzogen ist. Für die Verarbeitung solcher Daten durch Dritte können wir daher keine Verantwortung übernehmen.</p>

<h3>2. Änderungen dieser Datenschutzerklärung</h3>

<p>Wir behalten uns das Recht vor, diese Datenschutzerklärung jederzeit mit Wirkung für die Zukunft zu ändern. Eine jeweils aktuelle Version ist an dieser Stelle verfügbar. Bitte suche die Website regelmäßig auf und informiere dich sich über die geltende Datenschutzerklärung.</p>
		</div>

		<section class="contact">
			<a class="btn" data-open="contact_reveal" >Kontakt</a>
		</section>
		<form id="contact_reveal" class="reveal" method="post" data-abide novalidate  data-reveal >
			<div data-abide-error class="alert callout" style="display: none;">
				<p><i class="fi-alert"></i>In deinem Formular sind Fehler.</p>
			</div>
			<h2>Schreib uns:</h2>
			<div class='name'>
				<input class='first' name='first_name' placeholder='Vorname' type='text' required >
				<span class="form-error">Bitten den Vornamen eintragen!</span>
				<input class='last' name='last_name' placeholder='Nachname' type='text' required >
				<span class="form-error">Bitten den Nachnamen eintragen!</span>
			</div>
			<div class='mailbox'>
				<input class='email' name='mail_adress' placeholder='E-mail' type='text' required pattern="email" >
				<span class="form-error">Bitten eine E-Mail Adresse eintragen!</span>
			</div>
			<div class='message'>
				<textarea name='message_text' placeholder='Deine Nachricht!' required ></textarea>
				<span class="form-error">Bitten eine Nachricht eintragen!</span>
			</div>
			<div class="btnbx">
				<button type="submit" value="Abschicken" >Abschicken!</button>
			</div>
		</form>


		<section class="login">
			<a class="btn" data-open="login_reveal">Login</a>
		</section>
		<form id="login_reveal" class="reveal" method="post" action="admin.php" data-abide novalidate  data-reveal >
			<input class='pass' name='password' placeholder='password' type='password' required>
			<div class="btnbx">
				<button type="submit">Enter</button>
			</div>
		</form>


		<a style="margin-left: 30px;" href="https://www.facebook.com/generationenergiewende"><img style="height:1em;" src="images/fb.png" alt="find us on facebook" /></a>
		</div>
	</footer>


	<!-- foot scripts -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/what-input/4.0.4/what-input.min.js"></script>
	<script src="assets/foundation.min.js"></script>
<script>
	$(document).foundation();
</script>

</body>
</html>
