<?php
class RegisterForm extends CreateForm{
	public function generateFooter(){
		$fields= implode(':' , array_keys ( ( array ) $this->_fieldsAndLabels ) ) ;
		$this->_footer = "<input type=\"hidden\" name=\"fields\" value=\"{$fields}\"> <div style='margin-top:5px'><input type=\"submit\" value=\"Register\">";
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
			if (substr_count ( $field, 'password' )) {
				$type = "password";
			} else {
				$type = "text";
			}
			$label = "<tr><td>{$label}</td>";
			$value = Input::get($field);
			$info = "<td><input class=\"input\" type=\"{$type}\" name=\"{$field}\" id=\"{$field}\" value=\"{$value}\"></td></tr>";
	
			$output = $output . $label . $info;
			$x ++;
		}
		$output .= "</table></div>";
		$output .= $this->_footer . "<input type='reset' value='Reset'><a href='index.php' style='margin-left:20px'>Back</a></form></div>";
	
		return $output;
	}
}