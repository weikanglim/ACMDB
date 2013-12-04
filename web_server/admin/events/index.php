<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/admin.php";

echo Session::flash ('editSuccess');
$table = 'events'; 
$primary_key = 'eid';
$dbo = DB::getInstance()->get($table);
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
	if(Input::get('search_field') && Input::get('search_value')){
		$search_field = Input::get('search_field');
		$search_value = Input::get('search_value');
		$search = $dbo->get ( $table, array (
				"{$search_field}",
				'=',
				"{$search_value}" 
		) );
		if (! $search->error ()) {
			$records = $search->results ();
			if (! $search->count ()) {
				$error =  'No results.';
			}
		} else {
			$error =  "\"$search_value\" is not a valid type for $search_field.";
		}
	}
	
	if(Input::get('order')){
		$order = $dbo->order( $table, Input::get('order'));
		$records = $order->results();
	}
	
	if (Token::check ( Input::get ( Config::get ( 'session/token_name' ) ) )) {
		// Edit mode
		if (Input::get ( 'edit' )) {
			$primary_value = Input::get ( 'edit' );
			$record = $dbo->get ( $table, array (
					"{$primary_key}",
					'=',
					"{$primary_value}" 
			) )->first ();
			$fields = array_keys((array) $record);
			$mappedFields = array();
			foreach($fields as $field){
				$mappedFields[$field] = $field;
			}
			$edit = true;
		}
	}
} 

if(Input::exists('post')){
	$validate = new Validate ();
	$validation = $validate->check ( $_POST, array (
			'username' => array (
					'required' => true,
					'min' => 4,
					'max' => 20,
					'unique' => array($table, 'onUpdate')
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
		$fields = explode(":", Input::get('fields'));
		$fieldAndValue = array();
		foreach($fields as $field){
			if($field !== $primary_key){
				$fieldAndValue["{$field}"] = Input::get("{$field}");
			}
		}
		
		if ($dbo->update($table, array($primary_key, Input::get('primary')), $fieldAndValue)) {
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
<title>Events</title>
<link rel="stylesheet" type="text/css" href="/records.css">
<link rel="stylesheet" type="text/css" href="/table.css">
</head>
<body>
<div>
<?php 
	echo $error;
?>
</div>

<div style='float:left'>
	<form action="" method="get">
		<label for="Search">Search</label> 
		<input type="text" name="search_value" id="search_value"> 
			<select name="search_field">
				<?php echo Format::options($headers); ?>
			</select>
			<input type="submit" value="Search">
	</form>
</div>
<div>
	<form action="">
		<input type="submit" value="Clear">	
	</form>
	</div>
		
		<?php 
		if(!$edit){
			echo Format::adminView($records, $headers);
		}else{
			echo Format::record($record, null, $primary_key , true);
		}
		?>
</body>
</html>