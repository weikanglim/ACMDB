<?php

require_once 'core/init.php';
require_once 'core/private.php';

echo Session::flash('home');

$user = new User();
?>
	<p> Hello <a href="#"><?php echo escape($user->data()->username) ?></a>!</p>
<?php 
?>
<style>
pre.cow {
	font-size: 18px;
	margin-left: 40px;
}
</style>

<pre class="cow">
  _____
&lt; Hello &gt;
  -----
         \   ^__^ 
          \  (oo)\_______
             (__)\       )\/\
                 ||----w |
                 ||     ||
    
</pre>