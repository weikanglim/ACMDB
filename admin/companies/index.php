<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/admin.php";
require_once $base . "/core/private.php";


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
<link rel="stylesheet" type="text/css" href="/css/base.css"><link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css">
<link type="text/css" rel="stylesheet" href="/css/jquery.qtip.css" />

</head><body>
</head><body>
<?php if(!$control->edit()){
	echo "<div class='admin'>";
} else {
	echo "<div class='long-record'>";
}?>
<div>
<h3>Companies</h3>

<div>
<?php 
	echo Session::flash ('editSuccess');
echo Session::flash ('addSuccess');
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
		<!-- load jquery -->
	<script type="text/javascript"
		src="/jquery-1.10.2.min.js"></script>
	<script type="text/javascript"
		src="/jquery-ui-1.10.3.custom.js"></script>
		<script type="text/javascript" src="/jquery.qtip.js"></script>
		<script>
					 $(document).ready(function()
					 {
					     $('[title]').qtip({
					         position: {
					             target: 'mouse', // Track the mouse as the positioning target
					             adjust: { x: 5, y: 5 } // Offset it slightly from under the mouse
					         }
					     });
					 });
	</script>
	<?php if(!$control->edit()) echo $companiesTable->scripts(); ?>
</div>
</body>
</html>