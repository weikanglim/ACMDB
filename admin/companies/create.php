<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/admin.php";
require_once $base . "/core/private.php";


	
$table = 'companies_view';
$insert_table ='companies_view';
$id = 'cid';
$edit = false;
$error = "";
$headers = DB::getInstance()->get('information_schema.columns', array('table_name' , '=', "{$table}"), array('column_name'))->results();
$fields = array(
	'company_name' => 'Company Name',
	'contact_person' => 'Contact Person',
	'contact_phone' => 'Contact Phone Number',
	'contact_email' => 'Contact Email'
);

if(Input::exists('post')){
	$validate = new Validate ();
	$validation = $validate->check ( $_POST, array (
				'company_name' => array(
					'required' => true,
					'unique' => array('organizers', 'oncreate')
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
			foreach ( $errors as $validate_error ) {
			$error .=  "$validate_error <br>";
		}
	}
}

?>
<html>
<head>
	<title>Add New Company</title>
		<link rel="stylesheet" media="all" type="text/css"
	href="/css/jquery-ui-1.10.3.custom.css" />
<link rel="stylesheet" type="text/css" href="/css/records.css">
<link rel="stylesheet" type="text/css" href="/css/table.css">
<link rel="stylesheet" type="text/css" href="/css/base.css"><link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css">
</head><body>
<div class='record'>
<h3>Add New Company</h3>
<?php if($error) echo '<div class="ui-state-error ui-corner-all">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error:</strong> ' .$error. '</p> </div>';?>

			<?php
				 $create = new CreateForm($fields);
				 echo $create->render();
			?>

	</div>
</div></body>
</html>