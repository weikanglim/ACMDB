<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . '/core/init.php';
require_once $base . "/core/private.php";

$user = new User ();
$primary_key = 'uid';
$chgpwd = false;
$error = "";
$profileFields = array('firstname' => $user->data()->firstname, 
					   'lastname' => $user->data()->lastname,
					   'email' =>$user->data()->email,
					   'phone' => $user->data()->phone);

if (! $user->isLoggedIn ()) {
	Redirect::to ( '\login.php' );
} else {
	if (Input::exists ('post')) {
		if(Input::get('pwd_token')){
			if(Token::check(Input::get('pwd_token'), 'pwd_token')){
				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'password' => array(
							'required' => true,
							'different' => 'new_password',
							'password' => Input::get('user')),
					'new_password' => array(
							'required' => true,
							'matches' => 'new_password_again'),
				));
				if($validate->passed()){
					$password = Input::get('new_password');
					$hashInfo = Hash::create_hash($password);
					if(DB::getInstance()->update('users',
							array('uid', Input::get('user')), 
							array('password' => $hashInfo['hash'],
								  'salt' => $hashInfo['salt']))){
						Session::flash('home', 'Password changed successfully.');
						Redirect::to('/index.php');
					} else {
						$error = "Error in changing password.";
					}
				} else{
					$validation_errors = $validate->errors();
					foreach($validation_errors as $validation_error){
						$error = $error . $validation_error . "<br>";
					}
				}
			}
		}
		
		
		if (Token::check (Input::get('update_token'), 'update_token' )) {
			$validate = new Validate ();
			$validation = $validate->check ( $_POST, array(
					'firstname' => array (
							'required' => true 
					),
					'lastname' => array (
							'required' => true 
					),
					'email' => array (
							'required' => true,
							'email' => true
					),
					'phone' => array (
							'phone' => true,
					)
			) );
			
			if ($validation->passed ()) {
				// Check for changes in email
				$oldEmail = $user->data()->email;
				$newEmail = Input::get('email');
				if($oldEmail != $newEmail){
					// Remove old emails and add new emails in mailing list
					$uid = $user->data()->uid;
					$gids = DB::getInstance()->get('users_siggroups',array('uid','=',$uid))->getResults('gid'); // All joined groups
					foreach($gids as $gid){	
						$group = DB::getInstance()->get('siggroups_edit_view',array('gid','=' ,$gid))->first();
						$list = strtolower($group->title);
						rmMember($oldEmail, $list, false); // don't notify user
						addMember($newEmail, $list, false);
					}
				}
				if ($user->update ( array (
						'firstname' => Input::get ( 'firstname' ),
						'lastname' => Input::get ( 'lastname' ),
						'email' => Input::get ( 'email' ),
						'phone' => Input::get ( 'phone' )
				) )) {
					Session::flash ( 'home', 'User details updated succesfully.' );
					Redirect::to('\index.php');
				} else {
					$error = $user->errors ();
				}
			} else {
				$validation_errors = $validation->errors ();
				foreach ( $validation_errors as $validation_error ) {
					$error =  $error . $validation_error . "<br>";
				}
			}
		}
	}
}


?>

<html>
<head>
<title>Update Profile</title>
	<link rel="stylesheet" media="all" type="text/css"
	href="/css/jquery-ui-1.10.3.custom.css" />
<link rel="stylesheet" type="text/css" href="/css/table.css">
<link rel="stylesheet" type="text/css" href="/css/base.css">
<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css">

</head>
<body>
<div class="profile">

	<?php 
	echo Session::flash('pwd_chg');
	if($error) echo '<div class="ui-state-error ui-corner-all">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error:</strong> ' .$error. '</p> </div>'; ?>
	<?php if(Input::exists('get') && Input::get('chgpwd') && Input::get('user')){
			$fields = array('password' => '', 'new_password' => '', 'new_password_again' => '');
			$format = array('password' => 'Old Password', 'new_password' => 'New Password', 'new_password_again' => 'Confirm New Password');
			$pwdForm = new PasswordForm($fields, $primary_key);
			echo $pwdForm->render(); 
	}else{
			$profileForm = new ProfileForm($profileFields, $primary_key);
			echo $profileForm->render(false);
			echo '<form class="pure-form" style="" action="" method="get">
		<input type="hidden" name="chgpwd" value="1">
		<input type="hidden" name="user" value="' . $user->data()->uid . '"> 
		<button class="pure-button" type="submit" style="margin:0 35%">Change Password</button>';
	}
	?>
</div>
</div></body>
</html>
