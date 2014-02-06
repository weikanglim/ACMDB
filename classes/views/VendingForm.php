<?php
class VendingForm {
	private static $vendingOptions = array(
		'Pop' => 0.50,
		'Ramen Noodles' => 0.50,
		'Mac N\' Cheese' => 0.50, 
	);
	
	public static function render(){
		$form = '<form action="transaction.php" method="post">';
		$vendingOptionsWithValues = array();
		setlocale(LC_MONETARY, 'en_US.UTF-8');
		foreach(self::$vendingOptions as $option=>$value){
			$vendingOptionsWithValues[$option . "  ( " . money_format('%i',$value) . " )"] =  $option . ':' . $value; 
		}
		$selection = Selections::renderHTML(Selections::DropDown,  'vend' ,$vendingOptionsWithValues);
		$submit = "<input type='submit' value='Vend!'>";
		$formEnd = "</form>";
		return ($form . $selection . $submit . $formEnd);
	}
}