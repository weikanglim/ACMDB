<?php
require_once 'Mail.php';
require_once 'Mail/mime.php';
class Mailer {
	private $_success,
			$_signature = "<br><br>Regards,<br>
							Wei Kang Lim<br>
							Treasurer & ACMDB Admin<br>
							NDSU ACM";
	
	public function __construct($to, $title, $body) {		
		
		$mime = new Mail_mime ();
		$mime->setHTMLBody ( $body . $this->_signature );
		
		$body = $mime->get ();
		$headers = $mime->headers ( array (
				'From' => Config::get('email/address'),
				'To' => $to,
				'Subject' => $title 
		) );
		
		$smtp = Mail::factory ( "smtp", array (
				'host' => Config::get('email/host'),
				'port' => Config::get('email/port'),
				'auth' => true,
				'username' => Config::get('email/username'),
				'password' => Config::get('email/password') 
		) );
		$mail = $smtp->send ( $to, $headers, $body );
		
		if (PEAR::isError ( $mail )) {
			error_log ( $mail->getMessage () );
			$this->_success = false;
		} else {
			$this->_success = true;
		}
	}
	
	public function success(){
		return $this->_success;
	}
}