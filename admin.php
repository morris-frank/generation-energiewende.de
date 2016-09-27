<?php
	function show_return() {
		echo 'Something went wrong or your being naughty!'.
			 '<hr>'.
			 '<a href=http://'.$_SERVER['HTTP_HOST'].'>go back!</a>';
		exit(0);
	}

	$cnfg_file = file_get_contents('./secret/config.json');
	$cnfg_json = json_decode($cnfg_file, True);

	if (!isset($_POST['password'])) { show_return(); }

	$given_hash = hash('sha256', $cnfg_json['password_salt'].strip_tags($_POST['password']));
	if (strcmp($given_hash, $cnfg_json['password_hash']) != 0) { show_return(); }

	$cnfg = [
		'hub_mail' => '',
		'twtr_user' => '',
		'twtr_key' => '',
		'twtr_secret' => '',
		'yt_list' => '',
		'password_hash' => '',
		'password_salt' => '',
		'guest_message' => '',
		'admin_message' => '',
		'contact_subject' => '',
		];

	foreach ($cnfg as $key => $value) {
		if(isset($cnfg_json[$key])) {
			$cnfg[$key] = $cnfg_json[$key];
		}

		if(isset($_POST[$key])) {
			$cnfg[$key] = $_POST[$key];
		}
	}

	$encoded_cnfg = array_map(utf8_encode, $cnfg);
	file_put_contents('./secret/config.json', json_encode($encoded_cnfg));
?>

<!DOCTYPE html>
<html>
<head>
	<title>Adminpanel - Generation Energiewende</title>

	<!-- styles -->
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/2.1.0/normalize.css" />
	<link rel="stylesheet" href="assets/main.css" />
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,700" rel="stylesheet">

	<!-- icons -->
	<link rel="shortcut icon" href="assets/favicon.ico" />
</head>
<body id="admin_panel">
	<h1>Admin panel</h1>
	<form method="post" action="admin.php">
		<fieldset>
			<legend>YouTube</legend>
			<label>Youtube link list:</label>
				<textarea name="yt_list" ><?php echo $cnfg['yt_list']; ?></textarea>
		</fieldset>

		<fieldset>
			<legend>Twitter</legend>
			<label>Username:</label>
				<input name="twtr_user" type="text" value="<?php echo $cnfg['twtr_user']; ?>">
			<label>Key:</label>
				<input name="twtr_key" type="text" value="<?php echo $cnfg['twtr_key']; ?>">
			<label>Secret:</label>
				<input name="twtr_secret" type="password" value="<?php echo $cnfg['twtr_secret']; ?>">
		</fieldset>

		<fieldset>
			<legend>Mail</legend>
			<p>Akzeptierte Tags: [vorname], [name], [message], [mail]</p>
			<label>Contact Mail adress:</label>
				<input name="hub_mail" type="email" value="<?php echo $cnfg['hub_mail']; ?>">
			<label>Contact Mail subject:</label>
				<input name="contact_subject" type="text" value="<?php echo $cnfg['contact_subject']; ?>">
			<label>Guest message:</label>
				<textarea name="guest_message" ><?php echo $cnfg['guest_message']; ?></textarea>
			<label>Admin message:</label>
				<textarea name="admin_message"><?php echo $cnfg['admin_message']; ?></textarea>
		</fieldset>

		<fieldset>
			<legend>Admin</legend>
			<label>Password hash:</label>
				<input name="password_hash" type="text" value="<?php echo $cnfg['password_hash']; ?>">
			<label>Salt:</label>
				<input name="password_salt" type="text" value="<?php echo $cnfg['password_salt']; ?>" readonly>
		</fieldset>

		<input name="password" type="hidden" value="<?php echo  $_POST['password']; ?>">

		<div class="btnbx">
			<button type="submit">Abschicken!</button>
		</div>
	</form>
</body>
</html>