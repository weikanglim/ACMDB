<?php
class AddEventForm extends CreateForm{
	private $uid;
	public function __construct($fieldsAndLabels, $uid){
		parent::__construct($fieldsAndLabels);
		$this->uid = $uid;
	}
	public function generateFooter(){
		$fields= implode(':' , array_keys ( ( array ) $this->_fieldsAndLabels ) ) ;
		$this->_footer = "<input type=\"hidden\" name=\"fields\" value=\"{$fields}\"> <div style='margin-top:5px'><input type=\"submit\" value=\"Create\">";
	}

	public function render(){
		$this->generateFooter();
		$output = '<form action="" method="post">';
		$output .='<div class="user-record";><table><thead><tr><th>Field</th><th>Value</th></tr></thead>';
		$fields = $this->_fieldsAndLabels;
		$field_str = implode(":", array_keys($fields));
		$dbo = DB::getInstance ()->query("Select * from organizers as o where o.oid in (Select oid from siggroups as g where g.leader_id = ?)",
			array($this->uid));
		$results = $dbo->results();
		$options = array();
		foreach ( $results as $result ) {
			$options [$result->title] = $result->oid;
		}
		
		foreach ( $fields as $field=>$label ) {
			$label = "<tr><td>{$label}</td>";
			if(strtolower($field == 'oid')){
				$info = "<td>" . Selections::renderHTML(Selections::DropDown, $field, $options) . "</td></tr>";
			} 
			else{
				$info = "<td><input class=\"input\" type=\"text\" name=\"{$field}\" id=\"{$field}\" value=\"\"></td></tr>";
			}
	
			$output = $output . $label . $info;
		}
		$output .= "</table></div>";
		$output .= $this->_footer . "<input type='reset' value='Reset'><a class='alink' href='index.php' style='margin-left:20px'>Back</a></form></div>";
	
		return $output;
	}
	
}