<?php
class ProfileForm extends EditForm{
	public function __construct($data, $primary){
		parent::__construct($data, $primary);
	}
	
	public function generateFooter(){
		$user = new User();
		$token = Token::generate('update_token');
		$this->_footer = '<button type="submit" class="pure-button pure-button-primary">Update Profile</button> 
			<input type="hidden" id="update_token" name="update_token" value="' . $token . '">
			<button type="reset" class="pure-button pure-button-secondary">Reset</button>';
	}
	
	public function render(){
		$output = '<form class="pure-form pure-form-aligned" action="" method="post">';
		$output .= '<legend>Update Profile</legend>';
		$fields = $this->_data;
		$field_str = implode(":", array_keys($fields));
		$type = "";
		$output .= "<fieldset>";
		
		foreach ( $fields as $field=>$value ) {
			if (substr_count ( $field, 'password' )) {
				$type = "password";
			} else {
				$type = "text";
			}
			
			$label = Format::nice($field);
			$htmlLabel = "<div class='pure-control-group'><label for='{$label}'>{$label}</label>";
			$info = "<input class=\"input\" type=\"{$type}\" name=\"{$field}\" id=\"{$field}\" placeholder=\"{$label}\" value=\"{$value}\"></div>";	
			$element = $htmlLabel . $info;
			$output = $output  . $element;
		}
		$this->generateFooter();
		$output .= "</fieldset><fieldset><div class='pure-controls' style='margin-top:1em'>" .
				$this->_footer .
				"</div></fieldset></form>";
		
		return $output;
	}
}