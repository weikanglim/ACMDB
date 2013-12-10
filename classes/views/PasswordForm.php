<?php
class PasswordForm extends ProfileForm{
	public function __construct($data, $primary){
		parent::__construct($data, $primary);
	}
	
	public function generateFooter(){
		$tokenName = 'pwd_token';
		$token = Token::generate('pwd_token');
		$user = Input::get('user');
		$this->_footer = '<input type="hidden" name="'. $tokenName . '"
				value="' . $token .'"> <input
				type="hidden" name="user" value="'. $user .'">
				<button class="pure-button pure-button-primary" type="submit">Submit</button>' .
				"<button class='pure-button' type='reset'>Reset</button> <a class='alink' href='profile.php' style='margin-left:20px'>Back</a>";
	}
	
	public function render(){
		return parent::render(false, 'profile.php');
	}
}