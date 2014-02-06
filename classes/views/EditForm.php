<?php
class EditForm extends Form {
	public $_data,
		   $_primary,
		   $_formatOptions = array(),
		   $_scripts,
		   $_footer;
	public function __construct($data, $primary){
		$this->_data = $data;
		$this->_primary = $primary;	
	}
	
	public function generateFooter(){
		$fields= implode(':' , array_keys ( ( array ) $this->_data ) );
		$this->_footer = "<input type=\"hidden\" name=\"fields\" value=\"{$fields}\"> 
						  <input type=\"submit\" value=\"Edit\">" .
						  "<input type='reset' value='Reset'> <a class='alink' href='index.php' style='margin-left:20px'>Back</a>";
	}
		
	public function render($showPassword = true, $back = false){
		$this->generateFooter();
		$output = '<form action="" method="post">';
		$output .='<div class="user-record";"><table><thead><tr><th>Field</th><th>Value</th></tr></thead>';
		$x = 0;
		$data = $this->_data;
		$primary = $this->_primary;
		$fields = implode ( ":", array_keys ( ( array ) $data ) );
		foreach ( $data as $field => $value ) {
			if ((substr_count ( $field, 'password' )  || $field == 'salt')&& !$showPassword) {
				$type = "password";
			} else {
				$type = "text";
			}
			$field_str = Format::nice($field);
			$label = "<tr><td>{$field_str}</td>";
			
			if ($field == $primary || $field == 'userlevel') {
				$info = "<td><strong>{$value}</strong></td>";
			} else if(in_array(strtolower($field), array_keys($this->_specials))){
				$info = "<td>" . Selections::renderHTML($this->_specials[strtolower($field)], $field, $this->getOptions(strtolower($field)), $value) . "</td></tr>";
			} else if(in_array(strtolower($field), $this->_long_input)){
				$info = "<td><input class=\"long-input\" type=\"{$type}\" name=\"{$field}\" id=\"{$field}\" value=\"{$value}\"></td></tr>";
			}
			else{
				$info = "<td><input class=\"input\" type=\"{$type}\" name=\"{$field}\" id=\"{$field}\" value=\"{$value}\"></td></tr>";
			}
		
			$output = $output . $label . $info;
			$x ++;
		}
		$output .= "</table></div>";
		$output .= $this->_footer . "</form></div>";
		
		return $output;
	}
}