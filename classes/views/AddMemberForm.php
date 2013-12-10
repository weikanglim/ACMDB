<?php
class AddMemberForm extends Form {
	private $_footer, $_options = array();
	
	public function __construct($group){
		$dbo = DB::getInstance ()->query('Select * from users_view where uid NOT IN (Select uid from users_siggroups where gid = ?)', 
				array ($group));
		$results = $dbo->results ();
		foreach ( $results as $result ) {
			$name = $result->name;
			$this->_options [$name] = $result->uid;
		}
	}
	
	public function generateFooter(){
		$this->_footer = "<div style='margin-top:5px'><input type=\"submit\" value=\"Add New\">";
	}
	
	public function render(){
		$this->generateFooter();
		$output = '<form action="" method="post">';
		$output .='<div class="user-record";><table><thead><tr><th>Field</th><th>Value</th></tr></thead>';
		$label = "<tr><td>Member Name</td>";
		$info = "<td>" . Selections::renderHTML(Selections::DropDown, 'memberName', $this->_options) . "</td></tr>";
		$output .=  ($label . $info);
		$output .= "</table></div>";
		$output .= $this->_footer . "<a class='alink' href='index.php' style='margin-left:20px'>Back</a></form></div>";
		
		return $output;
	}
}