<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once 'core/init.php';
require_once $base . "/core/private.php";

$user = new User ();
$primary_key = 'uid';
$chgpwd = false;
$error = "";

if (! $user->isLoggedIn ()) {
	Redirect::to ( 'login.php' );
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
						Session::flash('chg_pwd', 'Password changed successfully.');
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
		
		
		if (Token::check ( Input::get ( Config::get('session/token_name' ) ) )) {
			$validate = new Validate ();
			$validation = $validate->check ( $_POST, array (
					'username' => array (
							'required' => true,
							'min' => 4,
							'max' => 20,
							'unique' => array('users', 'onUpdate')
					),
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
				if ($user->update ( array (
						'username' => Input::get ( 'username' ),
						'firstname' => Input::get ( 'firstname' ),
						'lastname' => Input::get ( 'lastname' ),
						'email' => Input::get ( 'email' ),
						'phone' => Input::get ( 'phone' )
				) )) {
					Session::flash ( 'home', 'User details updated succesfully.' );
					Redirect::to('index.php');
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
<link rel="stylesheet" type="text/css" href="table.css">
</head>

<body>
	<h2>Update Profile</h2>
	<div><?php 
	echo Session::flash('pwd_chg');
	echo $error; ?></div>
	<?php if(Input::exists('get') && Input::get('chgpwd') && Input::get('user')){
			if($user->data()->uid != Input::get('user')){
				Redirect::to(403);
			} 
			$fields = array('password' => '', 'new_password' => '', 'new_password_again' => '');
			$format = array('password' => 'Old Password', 'new_password' => 'New Password', 'new_password_again' => 'Confirm New Password');
			?>
			<form action="" method="post">
				<?php echo Format::record($fields, $format, Input::get('user'), false); ?>
				<input type="hidden" name="pwd_token"
			value="<?php echo Token::generate('pwd_token'); ?>"> <input
			type="hidden" name="user" value="<?php echo Input::get('user'); ?>">
		<input type="submit" value="Submit">
	</form>

	<form action="">
		<input type="submit" value="Back">
	</form>
		<?php 
	}else{
		?>
	<form action="" method="post">
			<?php
			echo Format::record ( $user->data (), array (
					'username' => 'Username',
					'firstname' => 'First Name',
					'lastname' => 'Last Name',
					'email' => 'Email',
					'phone' => 'Phone' 
			) ,$primary_key);
			?>
			
			<input type="submit" value="Update profile"> <input type="hidden"
			name="token" value="<?php echo Token::generate(); ?>">
	</form>

	<form action="" method="get">
		<input type="hidden" name="chgpwd" value="1"> <input type="hidden"
			name="user" value="<?php echo $user->data()->uid ?>"> <input
			type="submit" value="Change Password">
	</form>
	
<?php }?>
</body>
</html>
