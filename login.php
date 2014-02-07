<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . '/core/init.php';
require_once $base . "/core/public.php";

echo Session::flash('registered');
echo Session::flash('reset');
echo Session::flash('resetSuccess');
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
	<link rel="stylesheet" media="all" type="text/css"
	href="/css/jquery-ui-1.10.3.custom.css" />
<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css">
<link rel="stylesheet" type="text/css" href="/css/base.css">
</head><body>
<?php if($errors) echo formatErrors($errors);?>
		<div class=login>
		<form class="pure-form pure-form-aligned" action="" method="post">
		<fieldset>
		<legend>Login</legend>
	        <div class="pure-control-group">
				<label for="username">Username</label>
				<input type="text" name="username" id="username" placeholder="Username">
			</div>
	        <div class="pure-control-group">
			
				<label for="password">Password</label>
				<input type="password" name="password" id="password" placeholder="Password">
			</div>
			
	        <div class="pure-controls">
				<label for="remember" class="pure-checkbox">
					<input type="checkbox" name="remember" id="remember"> Remember me
				</label>
				<a class="alink" style="font-size:12px;color:0E5CE3" href="forgotPassword.php">Forgot your password?</a><br>
				<button type="submit" class="pure-button pure-button-primary">Sign in</button>
			</div>			
		</fieldset>
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	</form>
	</div>
</div></body>
</html>
