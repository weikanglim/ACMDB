<?php
class RegisterForm extends CreateForm{
	public function generateFooter(){
		$fields= implode(':' , array_keys ( ( array ) $this->_fieldsAndLabels ) ) ;
		$this->_footer = "<input type=\"hidden\" name=\"fields\" value=\"{$fields}\">
		<button class='pure-button pure-button-primary' type=\"submit\">Register</button>";
	}
	
	public function render(){
		$this->generateFooter();
		$output = '<form class="pure-form" action="" method="post">';
		$output .= '<legend>Registration</legend>';
		$fields = $this->_fieldsAndLabels;
		$field_str = implode(":", array_keys($fields));
		$x = 0;
		$type = "";
		
		foreach ( $fields as $field=>$label ) {
			switch($field){
				case 'firstname': $output .= "</fieldset>";
				case 'username':
					$output .= "<fieldset class='pure-group'>"; break;
			} 
			if (substr_count ( $field, 'password' )) {
				$type = "password";
			} else {
				$type = "text";
			}
// 			$htmlLabel = "<label for=\"{$label}\">{$label}</label>";
			$value = Input::get($field);
			$info = "<input class=\"input\" type=\"{$type}\" name=\"{$field}\" id=\"{$field}\" placeholder=\"{$label}\" value=\"{$value}\">";
			if($field == 'phone') $output .= "</fieldset>"; 
				
			$output = $output  . $info;
			$x ++;
		}
		$output .= "<fieldset><div class='pure-controls' style='margin-top:1em'>" . 
					$this->_footer . 
					"<button type='reset' class='pure-button pure-button-secondary'>Reset</button></div>
					<a class='alink' href='index.php' style='margin-left:20px'>Back</a></form></div>
							</fieldset></form>";
	
		return $output;
	}
}