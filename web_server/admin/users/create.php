<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/admin.php";

	
echo Session::flash ('addSuccess');
$table = 'users';
$id = 'uid';
$edit = false;
$headers = DB::getInstance()->get('information_schema.columns', array('table_name' , '=', "{$table}"), array('column_name'))->results();
$fields = array();
$exclude = array($id, "salt", 'accountcreated', 'accountexpires');

foreach($headers as $header){
	$column = $header->column_name;
	if(!in_array($column, $exclude)){
		$fields[] = $column;
	}
}


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
	<title>Registration</title>
<link rel="stylesheet" type="text/css" href="/records.css">
<link rel="stylesheet" type="text/css" href="/table.css">
</head>
<body>
	<div>
		<form action="" method="post">
			<?php
			echo Format::create ($fields, array(
				'username' => 'Username',
				'password' => 'Password',
				'firstname' => 'First Name',
				'lastname' => 'Last Name',
				'email' => 'Email',
				'phone' => 'Phone'
			));
			?>
			<input type="hidden" name="fields"
				value="<?php echo implode(":", $fields);?>">
				<input type="submit" value="Register">

		</form>
	</div>

	<div>
		<form action="">
			<input type="submit" value="Clear">
		</form>
	</div>
</body>
</html>