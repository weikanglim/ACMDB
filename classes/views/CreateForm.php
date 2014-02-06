<?php
class CreateForm extends Form {
	public $_fieldsAndLabels,
		   $_form,
		   $_footer,
		   $_scripts;
	
	public function __construct($fieldsAndLabels, $back = null){
		$this->_fieldsAndLabels = $fieldsAndLabels;
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
		$x = 0;
		$type = "";
		foreach ( $fields as $field=>$label ) {
			if($field !== 'userlevel'){
				if (substr_count ( $field, 'password' )) {
					$type = "password";
				} else {
					$type = "text";
				}
				$label = "<tr><td>{$label}</td>";
				if(in_array(strtolower($field), array_keys($this->_specials))){
					$info = "<td>" . Selections::renderHTML($this->_specials[strtolower($field)], $field, $this->getOptions(strtolower($field))) . "</td></tr>";
				} else if(in_array(strtolower($field), $this->_long_input)){
					$info = "<td><input class=\"long-input\" type=\"{$type}\" name=\"{$field}\" id=\"{$field}\" value=\"\"></td></tr>";
				} else{
					$info = "<td><input class=\"input\" type=\"{$type}\" name=\"{$field}\" id=\"{$field}\" value=\"\"></td></tr>";
				}
			}
	
				$output = $output . $label . $info;
				$x ++;
			}
		$output .= "</table></div>";
		$output .= $this->_footer . "<input type='reset' value='Reset'><a class='alink' href='index.php' style='margin-left:20px'>Back</a></form></div>";
		
		return $output;
	}
}