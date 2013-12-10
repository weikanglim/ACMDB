<?php
$base = $_SERVER['DOCUMENT_ROOT'];
require_once $base . "/core/init.php";
require_once $base . "/core/leader.php";
require_once $base . "/core/private.php";

$error = "";
if(Input::exists('post')){
	if (Input::get ( 'memberName' )) {
		if (DB::getInstance ()->insert ( 'users_siggroups', array (
				'uid' => Input::get ( 'memberName' ),
				'gid' => Session::get ( 'gid' ) 
		) )) {
			Session::delete('gid');
			Session::flash ( 'addSuccess', 'The member has been added.' );
			Redirect::to('index.php');
		} else {
			$error = 'An error has occurred.';
		} 
	}
}

if(Input::get('gid')){
	$gid = Input::get('gid');
	Session::put('gid', $gid);
} else {
	Redirect::to('index.php');
}


?>

<html>
<head>
<title>Add New Member</title>
	<link rel="stylesheet" media="all" type="text/css"
	href="/css/jquery-ui-1.10.3.custom.css" />
<link rel="stylesheet" type="text/css" href="/css/table.css">
<link rel="stylesheet" type="text/css" href="/css/base.css">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css">
</head><body>
<div class='record'>
<h3>Add New Member</h3>

	<?php if($error) echo '<div class="ui-state-error ui-corner-all">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error:</strong> ' .$error. '</p> </div>';
		$addForm = new AddMemberForm(Session::get('gid'));
		  echo $addForm->render(); ?>
		  </div>
</body>
</html>

