<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . '/core/init.php';
require_once $base . "/core/public.php";
require_once 'Mail.php';
require_once 'Mail/mime.php';


$error = "";
$errors = array();
if(Input::exists()){
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
			'username' => array(
					'required' => true,
					'exists' => array('users', 'username')
			),
			'email' => array(
					'required' => true,
			)
	));
	
	if($validation->passed()){ // user exist, now verify email
		$username = Input::get('username');
		$email = Input::get('email');
		$db = DB::getInstance(); 
		if($db->get('users', array('username' ,'=', $username))->count()){
			$user = $db->first();
			if($user->email == $email){
				// Generate reset password token
				$resetToken = md5(uniqid());
				if($db->update('users', array('uid', $user->uid), array("resettoken" => $resetToken))){
					$domain = $_SERVER['SERVER_NAME'];
					$subject = "ACMDB Password Reset";
					$body = "You have requested for your ACMDB password to be reset. If you did not make this request, please ignore this email.
				You may click on the following link to reset your password:
				<blockquote><a href={$domain}/passwordReset.php?username={$username}&token={$resetToken} target='_blank'>
					Password Reset</a></blockquote>
					Alternatively, you may copy and paste this link into your browser:<br><br>
					{$domain}/passwordReset.php?username={$username}&token={$resetToken}";
					
					$mailer = new Mailer("<{$email}>", $subject, $body);
					if ($mailer->success()) {
						Session::flash("reset", "An email has been sent to your account for instructions to reset your password.");
						Redirect::to('/login.php');
					} else {
						$error = "An error has occurred in sending your email. Please try again.";
					}
				} else {
				 	$error = "An error has occurred in updating your details. Please try again later.";	
				}
			} else {
				$error = "Email is incorrect.";
			}
		} else {
			$error = "User not found.";
		}
	} else {
		$errors = $validation->errors ();
		foreach ( $errors as $validate_error ) {
			$error .=  "$validate_error <br>";
		}
	}
} 
?>
<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" media="all" type="text/css"
		href="/css/jquery-ui-1.10.3.custom.css" />
		<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css">
		<link rel="stylesheet" type="text/css" href="/css/base.css">
		</head><body>
<?php  if($errors){ 
			echo formatErrors($errors);
	   }else if($error){
			echo formatError($error);
		}?>
		<div class=login>
		<form class="pure-form pure-form-aligned" action="" method="post">
		<fieldset>
		<legend>Password Assistance</legend>
	        <div class="pure-control-group">
				<label for="username">Username</label>
				<input type="text" name="username" id="username">
			</div>
	        <div class="pure-control-group">
			
				<label for="email">Email Address</label>
				<input type="text" name="email" id="email">
			</div>
			
	        <div class="pure-controls">
				<button type="submit" class="pure-button pure-button-primary">Request Password Reset</button>
			</div>			
		</fieldset>
	</form>
	</div>
</div></body>
</html>
