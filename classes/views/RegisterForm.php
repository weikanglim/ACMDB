<?php
class RegisterForm extends CreateForm{
	public function generateFooter(){
		$fields= implode(':' , array_keys ( ( array ) $this->_fieldsAndLabels ) ) ;
		$this->_footer = "<input type=\"hidden\" name=\"fields\" value=\"{$fields}\"> <div style='margin-top:5px'><input type=\"submit\" value=\"Register\">";
	}
}