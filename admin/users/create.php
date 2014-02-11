<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/admin.php";
require_once $base . "/core/private.php";

$table = 'users';
$id = 'uid';
$error = "";
$fields = array();
$fields = array(
				'username' => 'Username',
				'password' => 'Password',
				'password_again' => 'Confirm Password',
				'firstname' => 'First Name',
				'lastname' => 'Last Name',
				'email' => 'Email',
				'phone' => 'Phone',
			);

if(Input::exists('post')){
	$validate = new Validate ();
	$validation = $validate->check ( $_POST, array (
			'username' => array (
					'required' => true,
					'min' => 4,
					'max' => 20,
					'unique' => array($table, 'onCreate')
			),
			'firstname' => array (
					'required' => true
			),
			'password' => array(
				'required' => true,
				'min' => 6,
				'matches' => 'password_again'
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
		$fields = explode(":", Input::get('fields'));
		$fieldAndValue = array();
		$id = Input::get($id);
		$db = DB::getInstance();
		$hashInfo = Hash::create_hash(Input::get('password'));
		$fieldAndValue["salt"] = $hashInfo['salt'];
		foreach($fields as $field){
			switch($field){
			case 'password': $fieldAndValue["{$field}"] = $hashInfo['hash']; break;
			case 'password_again': break;
			case 'phone' : 
				if(!Input::get('phone')){ break;}
				else{ $fieldAndValue["phone"] = str_replace('-','',Input::get("phone")); }break;
			case 'userlevel' : break;
			default : $fieldAndValue["{$field}"] = Input::get("{$field}"); break;	
			}
		}
		if ($db->insert($table, $fieldAndValue)) {
			Session::flash ("registered", 'Registration succesful.' );
			Redirect::to($_SERVER['PHP_SELF']);
		} else {
			$error = 'Error with registration.';
		}
	} else{
		$validate_errors = $validation->errors ();
		foreach ( $validate_errors as $validate_error ) {
			$error .=  "$validate_error <br>";
		}
	}
}

?>
<html>
<head>
	<title>Registration</title>
		<link rel="stylesheet" media="all" type="text/css"
	href="/css/jquery-ui-1.10.3.custom.css" />
<link rel="stylesheet" type="text/css" href="/css/records.css">
<link rel="stylesheet" type="text/css" href="/css/table.css">
<link rel="stylesheet" type="text/css" href="/css/base.css">
<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css">
</head><body>
<div class='register'>
			<?php 
			echo Session::flash ("registered");
			if($error) echo '<div class="ui-state-error ui-corner-all">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error:</strong> ' .$error. '</p> </div>'; ?>
			
			<?php
				$register = new RegisterForm($fields);
				echo $register->render();
			?>
</div>			
</div></body>
</html>