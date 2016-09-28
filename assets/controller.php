<?php

	function mail_pending()
	{
		if ($_SERVER["REQUEST_METHOD"] != "POST") { return false; }
		if (!isset($_POST["first_name"],
				   $_POST["last_name"],
				   $_POST["mail_adress"],
				   $_POST["message_text"]
				  )) { return false; }
		if (!filter_var($_POST["mail_adress"], FILTER_VALIDATE_EMAIL)) {
    		echo "Invalid E-Mail processed.";
    		return false;
		}

  		return true;
	}

	function test_input($data) {
  		$data = trim($data);
  		$data = stripslashes($data);
  		$data = htmlspecialchars($data);
  		return $data;
	}

	function process_mail($mailCntrl)
	{
  		$firstname = test_input($_POST["first_name"]);
  		$lastname = test_input($_POST["last_name"]);
  		$mail = test_input($_POST["mail_adress"]);
  		$message = stripslashes($_POST["message_text"]);

  		$mailCntrl->SendMail($mail, $lastname, $firstname, $message);
		return false;
	}

	class MailController
	{
		private $subject;
		private $admin_mail;
		private $guest_message;
		private $admin_message;
		private $to;
		private $name;
		private $forename;
		private $message;

		function __construct($cnfg)
		{
			$this->admin_mail = $cnfg['hub_mail'];
			$this->subject = $cnfg['contact_subject'];
			$this->guest_message = $cnfg['guest_message'];
			$this->admin_message = $cnfg['admin_message'];
		}

		private function DecodeString($string)
		{
			$string = str_replace('[vorname]', $this->forename, $string);
			$string = str_replace('[name]', $this->name, $string);
			$string = str_replace('[mail]', $this->to, $string);
			$string = str_replace('[message]', $this->message, $string);
			return $string;
		}

		public function SendMail($to, $name, $forename, $message)
		{
			if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
    			echo "Invalid E-Mail processed.";
    			return 0;
			}

			$this->to = $to;
			$this->name = $name;
			$this->forename = $forename;
			$this->message = $message;

			$header = 'From: ' . $this->admin_mail . "\r\n" .
    				   'Reply-To: ' . $this->admin_mail . "\r\n" .
    				   'X-Mailer: PHP/' . phpversion();

			$subject = $this->DecodeString($this->subject);
    		$admin_message = $this->DecodeString($this->admin_message);
    		$guest_message = $this->DecodeString($this->guest_message);

			// Mail to the info mail address
			mail($this->admin_mail, $subject, $admin_message, $header);

			// Mail to the guest
			mail($to, $subject, $guest_message, $header);
		}
	}

?>