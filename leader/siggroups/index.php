<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/leader.php";
require_once $base . "/core/private.php";

echo Session::flash ('editSuccess');
echo Session::flash ('addSuccess');
$user = new User();
$uid = $user->data()->uid;
$table = 'siggroups_view'; 
$edit_table = 'siggroups_edit_view';
$delete_table = 'users_siggroups';
$primary_key = 'gid';
$dbo = DB::getInstance()->query("SELECT * FROM {$table} AS SG_view WHERE SG_View.GID IN (SELECT G.GID FROM SIGGROUPS AS G WHERE G.LEADER_ID = ?)", array($uid));
$queried_groups = $dbo->results();
$owned_groups = array();

foreach($queried_groups as $group){
	$group = new Group($group->gid, $group->group_name);
	$query = DB::getInstance()->query("Select * from siggroups_users_view WHERE GID = ?", array($group->gid));
	$group->members = array_combine($query->getResults('member_id'), $query->getResults('member'));
	$owned_groups[] = $group;
}
$edit = false;
$error = "";

if(!$dbo->error() && $dbo->count()){
	$records = $dbo->results();
	$headers = array_keys((array) $records[0]);
} else if(!$dbo->count()){
	$column_names = $dbo->get('information_schema.columns', array('table_name' , '=', "{$table}"), array('column_name'))->results();
	$headers = array();
	$records = array(); // empty records
	foreach($column_names as $column_name){
		$headers[] = $column_name->column_name;
	}
	$headers = array_reverse($headers);
	$error = 'No records found.';
}else {
	$error =  'Error retrieving data.';	
}

if(Input::exists('get')){
	if (Input::get('edit')) {
		$primary_value = Input::get ( 'edit' );
		$record = $dbo->get ( $edit_table, array (
				"{$primary_key}",
				'=',
				"{$primary_value}" 
		) ,array('title', 'description','meeting_day','meeting_time'))->first ();
		$edit = true;
	}
	
	if(Input::get('delete') && Input::get('gid')){
		if(Token::check(Input::get('delete_token' .  Input::get('gid')), 'delete_token' .  Input::get('gid'))){
			$ids = '(' . str_replace(':', ',', Input::get('delete')) . ')';
			if(DB::getInstance()->query("Delete from {$delete_table} WHERE UID IN {$ids} AND GID = ?", array(Input::get('gid')))){
				Session::flash('editSuccess', 'Members have been deleted.');
				Redirect::to('index.php');
			}
		}
	}
}

if(Input::exists('post')){
	$validate = new Validate ();
	$validation = $validate->check ( $_POST, array (
			'title' => array (
					'required' => true,
					'min' => 2,
					'max' => 40,
					'unique' => array($table, 'onUpdate')
			),
			'description' => array (
					'max' => 40
			),
			'leader_id' => array (
					'max' => 50
			)
	) );
		
	if ($validation->passed ()) {
		$fields = explode(":", Input::get('fields'));
		$fieldAndValue = array();
		
		foreach($fields as $field){
			if($field !== $primary_key && Input::get("{$field}")){
				$fieldAndValue["{$field}"] = Input::get("{$field}");
			} 
		}

		if ($dbo->update($edit_table, array($primary_key, Input::get('primary')), $fieldAndValue)) {
			Session::flash ( 'editSuccess', 'Record updated succesfully.' );
			Redirect::to("index.php");
		} else {
			$error =  "An error occurred in updating.";
		}
	} else {
		$validate_errors = $validation->errors ();
		foreach ( $validate_errors as $validate_error ) {
			$error .=  "$validate_error <br>";
		}
	}
}

?>

<html>
<head>
<title>SIG Groups</title>
<link rel="stylesheet" type="text/css" href="/css/userTable.css">
<link rel="stylesheet" type="text/css" href="/css/table.css">
<link rel="stylesheet" type="text/css" href="/css/base.css">
</head><body>
	<div>
<?php 
	echo $error;
?>
</div>


		<?php 
		if(!$edit){
			foreach($owned_groups as $group){
				echo $group->renderHTML();
			}
		}else{
			$editSig = new EditForm($record, $primary_key);
			echo ($editSig->render());
		}
		?>
		
			<script type="text/javascript"
		src="/jquery-1.10.2.min.js"></script>
</body>
</html>