<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/admin.php";
require_once $base . "/core/private.php";


echo Session::flash ('editSuccess');
echo Session::flash ('addSuccess');
$table = 'companies_view'; 
$edit_table = 'companies_view';
$primary_key = 'cid';
$dbo = DB::getInstance()->get($table);
$edit = false;
$error = "";

$validation = array (
	'company_name' => array('required'=>true),
	'contact_phone' => array (
							'required' => true,
							'email' => true
					),
	'contact_email' => array (
							'phone' => true,
					)
);

$control = new AdminTableController($dbo, $table, $edit_table, $primary_key, $validation);
$control->runController();

?>

<html>
<head>
<title>Companies</title>
<link rel="stylesheet" type="text/css" href="/css/records.css">
<link rel="stylesheet" type="text/css" href="/css/table.css">
<link rel="stylesheet" type="text/css" href="/css/base.css">
</head><body>
<h3>Companies</h3>

<div>
<?php 
	echo $control->error();
?>
</div>
		<?php 
		if(!$control->edit()){
			$companiesTable = new Table($control->records(), $control->headers(), 'index.php');
			echo $companiesTable->render();			
		}else{
			$companiesEdit = new EditForm($control->record(), $control->primary_key());
			echo $companiesEdit->render();
		}
		?>

</body>
</html>