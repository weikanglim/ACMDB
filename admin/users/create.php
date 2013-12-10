<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/admin.php";
require_once $base . "/core/private.php";

	
$table = 'users';
$id = 'uid';
$edit = false;
$error = "";
$headers = DB::getInstance()->get('information_schema.columns', array('table_name' , '=', "{$table}"), array('column_name'))->results();
$fields = array(
				'username' => 'Username',
				'firstname' => 'First Name',
				'lastname' => 'Last Name',
				'email' => 'Email',
				'phone' => 'Phone'
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
			'lastname' => array (
					'required' => true
			),
			'password' => array(
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
			if($field === 'password'){
				$fieldAndValue["{$field}"] = $hashInfo['hash'];
			} else {
				$fieldAndValue["{$field}"] = Input::get("{$field}");
			}
		}
		
		if ($db->insert($table, $fieldAndValue)) {
			Session::flash ( 'addSuccess', 'Record added.' );
			Redirect::to("index.php");
		} else {
			echo 'Error in insertion.';
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
	<title>Create New User</title>
<link rel="stylesheet" type="text/css" href="/css/table.css">
<link rel="stylesheet" type="text/css" href="/css/base.css"><link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css">
	<link rel="stylesheet" type="text/css" href="/css/base.css"><link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css">
</head><body>
<div class='record'>
<h3>Add New User</h3>
<div>
<?php 
echo Session::flash ('addSuccess');
echo $error;?>
</div>
			<?php
				 $create = new CreateForm($fields);
				 echo $create->render();
			?>
</body>
</div>
</html>