<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . '/core/init.php';
require_once $base . "/core/public.php";

echo Session::flash('registered');
$user = new User();
$errors = array();
if($user->isLoggedIn()){
	Redirect::to('index.php');
}

if(Input::exists()){
	if(Token::check(Input::get(Config::get('session/token_name')))){
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
		'username' => array(
			'required' => true,
		),
		'password' => array(
			'required' => true,
		)	
		));
		
		if($validation->passed()){
			$user = new User();
			$remember = (Input::get('remember') === 'on');
			$login = $user->login(Input::get('username'), Input::get('password'), $remember);
			
			if($login){
				Redirect::to('index.php');
			} else {
				$errors[] = $user->error();
			}
		} else {
			$errors = $validation->errors();
		}
	}
}

?>

<!DOCTYPE html>
<html>

<head>
<title>ACM Member Login</title>
<link rel="stylesheet" type="text/css" href="/css/base.css">
</head><body>
<?php if($errors){foreach($errors as $error){ echo $error . '<br>';};}?>
		<form action="" method="post">
		<table>
			<tr>
				<td><label for="username">Username</label></td>
				<td><input type="text" name="username" id="username"></td>
			</tr>
			<tr>
				<td><label for="password">Password</label></td>
				<td><input type="password" name="password" id="password"></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="remember" id="remember"> Remember me</td>
			</tr>
			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			<tr>
				<td><input type="submit" value="Login"></td>
			</tr>
		</table>
	</form>
</body>
</html>
