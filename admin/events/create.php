<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/admin.php";

	
$table = 'events_view';
$insert_table ='events';
$id = 'gid';
$edit = false;
$headers = DB::getInstance()->get('information_schema.columns', array('table_name' , '=', "{$table}"), array('column_name'))->results();
$fields = array();
foreach($headers as $header){
	$column = $header->column_name;
	if($column !== "gid" && $column !== "oid"){
		$fields[] = $header->column_name;
	}
}


if(Input::exists('post')){
	$validate = new Validate ();
	$validation = $validate->check ( $_POST, array (
				'leader' => array(
					'exists' => array('users', 'uid')
				),
				'title' => array(
					'required' => true,
					'unique' => array('organizers', 'onCreate')
				)
// 				'meeting_time' => 'Meeting Time'
	) );
		
	if ($validation->passed ()) {
		$fields = explode(":", Input::get('fields'));
		$fieldAndValue = array();
		$gid = Input::get($id);
		$db = DB::getInstance();
		foreach($fields as $field){
			if(Input::get($field)){
				switch($field){
					case 'title': break;
					case 'leader': $fieldAndValue["LEADER_ID"] = Input::get("{$field}"); break;
					default: $fieldAndValue["{$field}"] = Input::get("{$field}");break;
				}
			}
		}
		
		if($db->insert('organizers', array('title' => Input::get('title')), 'oid')){
			$oid = $db->first()->oid;
			$fieldAndValue['oid'] = $oid;
			 
			if ($db->insert($insert_table, $fieldAndValue)) {
				Session::flash ( 'addSuccess', 'Record added succesfully.' );
				Redirect::to("index.php");
			} else {
				$db->delete('organizers', array('oid', '=', $oid));
				echo 'Error in insertion.';
			}
		}
	} else {
		$errors = $validation->errors ();
		foreach ( $errors as $error ) {
			echo "$error <br>";
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
				'title' => 'Group Name',
				'description' => 'Description',
				'leader' => 'Leader',
				'meeting_time' => 'Meeting Time'
			));
			?>
			<input type="hidden" name="fields"
				value="<?php echo implode(":", $fields);?>"> <input type="hidden"
				name="uid" value="<?php echo "{$gid}";?>"> 
				<input type="submit" value="Create">

		</form>
	</div>

	<div>
		<form action="">
			<input type="submit" value="Clear">
		</form>
	</div>
</body>
</html>