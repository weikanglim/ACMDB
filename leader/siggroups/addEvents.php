<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/leader.php";

$uid = (new User())->data()->uid;
$table = 'events';
$id = 'eid';
$insert_table ='events';
$edit = false;
$headers = DB::getInstance()->get('information_schema.columns', array('table_name' , '=', "{$table}"), array('column_name'))->results();
$fields = array(
		'event_name' => 'Event Name',
		'location' => 'Location',
		'event_datetime' => 'Event Datetime',
		'oid' => 'Organizer');

if(Input::exists('post')){
	$validate = new Validate ();
	$validation = $validate->check ( $_POST, array (
			'event_name' => array(
					'required' => true
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
	<title>Add New Event</title>
	<link rel="stylesheet" media="all" type="text/css"
	href="/css/jquery-ui-1.10.3.custom.css" />
<link rel="stylesheet" type="text/css" href="/css/table.css">
<link rel="stylesheet" type="text/css" href="/css/base.css">
<link rel="stylesheet" type="text/css" href="/css/base.css">
</head><body>
<h3>Add New Event</h3>

			<?php
				 $create = new AddEventForm($fields ,$uid);
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
				$('#event_datetime').datetimepicker();
			});
		</script>
</body>
</html>

