<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/admin.php";
require_once $base . "/core/private.php";

$table = 'siggroups_view'; 
$edit_table = 'siggroups_edit_view';
$primary_key = 'gid';
$dbo = DB::getInstance()->get($table);
$edit = false;
$error = "";
$validation = array (
			'title' => array (
					'required' => true,
					'min' => 2,
					'max' => 40,
					'unique' => array($edit_table, 'onUpdate')
			)
	);

$control = new AdminTableController($dbo, $table, $edit_table, $primary_key, $validation);
$control->runController();

?>

<html>
<head>
<title>SIG Groups</title>
<link rel="stylesheet" media="all" type="text/css"
	href="/css/jquery-ui-1.10.3.custom.css" />

<link rel="stylesheet" type="text/css" href="/css/records.css">
<link rel="stylesheet" type="text/css" href="/css/table.css">
<link rel="stylesheet" type="text/css" href="/css/base.css">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css">
<link type="text/css" rel="stylesheet" href="/css/jquery.qtip.css" />
</head><body>
<?php if(!$control->edit()){
	echo "<div class='admin'>";
} else {
	echo "<div class='long-record'>";
}?>
<div>
<h3>Special Interest Groups</h3>
<div>
<?php echo Session::flash ('editSuccess');
echo Session::flash ('addSuccess');

	echo $control->error();
?>
</div>
		<?php 
		if(!$control->edit()){
			$sigTable = new Table($control->records(), $control->headers(), 'index.php');
			echo $sigTable->render();			
		}else{
			$sigEdit = new EditForm($control->record(), $control->primary_key());
			echo $sigEdit->render();
		}
		?>

		<!--  loading jQuery -->
	<script type="text/javascript"
		src="/jquery-1.10.2.min.js"></script>
	<script type="text/javascript"
		src="/jquery-ui-1.10.3.custom.js"></script>
		<script type="text/javascript" src="/jquery.qtip.js"></script>
	<script type="text/javascript" src="/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="/jquery-ui-sliderAccess.js"></script>
	<script>
			$(function()
			{
				$('#meeting_time').timepicker();
			});
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
	<?php if(!$control->edit()) echo $sigTable->scripts(); ?>
</div>
</div></body>
</html>