<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . '/core/init.php';
require_once $base . "/core/public.php";

echo Session::flash('error');
$errors = array();
if(!Input::exists('get') && !Input::exists('post')){
	Redirect::to('/login.php');
}

if(Input::exists('get')){
	$username = Input::get('username');
	$token = Input::get('token');
	$db = DB::getInstance();
	if(!$db->get('users', array('username','=',$username))->count()){
		Redirect::to(403);
	}
	$user = $db->first();
	if($token != $user->resettoken){
		Redirect::to(403);
	} else{
		Session::put('username', $username);
	}
} 

if (Input::exists('post')){
	$validation = new Validate();
	$validation->check($_POST, array(
	'password' => array(
			'required' => true,
			'matches' => 'newPasswordAgain',
			'min' => 6
	)));
	if($validation->passed()){
		$db = DB::getInstance();
		$username = Input::get('username');
		$password = Input::get('password');
		$hashInfo = Hash::create_hash($password);
		if($db->update('users', array('username', $username), array('salt' => $hashInfo['salt'], 'password' => $hashInfo['hash'], 'resetToken' => ""))){
			Session::flash('resetSuccess', 'Your password has been updated. Please use the new password to login.');
			Redirect::to('/login.php');
		} else {
			Session::flash('error', 'An error has occurred. Please try again later.');
			Redirect::to($_SERVER['PHP_SELF']);
		}
	} else {
		$errors = $validation->errors();
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
<?php  if($errors) echo formatErrors($errors);?>
		<div class=login>
		<form class="pure-form pure-form-aligned" action="" method="post">
		<fieldset>
		<legend>Password Reset</legend>
	        <div class="pure-control-group">
				<label for="password">New Password</label>
				<input type="password" name="password" id="password">
			</div>
	        <div class="pure-control-group">
			
				<label for="password">New Password Again</label>
				<input type="password" name="newPasswordAgain" id="newPasswordAgain">
			</div>
			
	        <div class="pure-controls">
				<button type="submit" class="pure-button pure-button-primary">Reset Password</button>
			</div>			
		</fieldset>
	</form>
	</div>
</div></body>
</html>
