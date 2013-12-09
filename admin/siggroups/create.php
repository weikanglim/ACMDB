<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/admin.php";

	
$table = 'siggroups_edit_view';
$insert_table ='siggroups_edit_view';
$edit = false;
$fields = array(
			'title' => 'Group Name',
			'description' => 'Description',
			'leader' => 'Leader',
			'meeting_day' => 'Meeting Day',
			'meeting_time' => 'Meeting Time'
		);


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
	) );
		
	if ($validation->passed ()) {
		$fields = explode(":", Input::get('fields'));
		$fieldAndValue = array();
		$db = DB::getInstance();
		foreach($fields as $field){
			if(Input::get($field)){
					$fieldAndValue["{$field}"] = Input::get("{$field}");
			}
		}
		
		if ($db->insert($insert_table, $fieldAndValue)) {
			Session::flash ( 'addSuccess', 'Record added succesfully.' );
			Redirect::to("index.php");
		} else {
			echo 'Error in insertion.';
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
	<title>Add New SIG Group</title>
	<link rel="stylesheet" media="all" type="text/css"
	href="/css/jquery-ui-1.10.3.custom.css" />
<link rel="stylesheet" type="text/css" href="/css/table.css">
<link rel="stylesheet" type="text/css" href="/css/base.css">
	<link rel="stylesheet" type="text/css" href="/css/base.css">
</head><body>
<h3>Add New SIG Group</h3>
			<?php
				 $create = new CreateForm($fields);
				 echo $create->render();
			?>

	
		<script type="text/javascript"
		src="/jquery-1.10.2.min.js"></script>
	<script type="text/javascript"
		src="/jquery-ui-1.10.3.custom.js"></script>
	<script type="text/javascript" src="/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="/jquery-ui-sliderAccess.js"></script>
	<script>
			$(function()
			{
				$('#meeting_time').timepicker();
			});
		</script>
</body>
</html>