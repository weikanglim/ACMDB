<?php
class Selections {
	const DateTime = 0;
	const Time = 1;
	const DropDown = 2;
	
	public static function renderHTML($selectionType, $name, $options = null, $default = null){
		switch($selectionType){
			case Selections::DateTime:
			case Selections::Time:
				return '<div class="ui-timepicker-div"><input class="long-input" type="text" name="' . 
				$name . 
				'" id="' . 
				$name . "\" value=\"{$default}\"></div>"; 
			case Selections::DropDown:
				$html = "<select name = $name>";
				if(!$options){
					return false;
				}
				
				if(Selections::isAssoc($options)){
					foreach($options as $option => $value){
						if($default && $value == $default){
							$html .= self::defaultOption($option, $value);
						} else {
							$html .= self::option($option, $value);
						}
					}
				} else {
					foreach($options as $option){
						if($default && $option == $default){
							$html .= self::defaultOption($option);
						}else {
							$html .= self::option($option);
						}
					}
				}
				
				$html .= "</select>";
				return $html;
		}
	}
	
	public static function dropdownOnChange($name, $options = null, $default = null){
		$html = "<select name = $name onchange=\"this.form.submit()\">";
		if(!$options){
			return false;
		}
		
		if(Selections::isAssoc($options)){
			foreach($options as $option => $value){
				if($default && $value == $default){
					$html .= self::defaultOption($option, $value);
				} else {
					$html .= self::option($option, $value);
				}
			}
		} else {
			foreach($options as $option){
				if($default && $option == $default){
					$html .= self::defaultOption($option);
				}else {
					$html .= self::option($option);
				}
			}
		}
		
		$html .= "</select>";
		return $html;
	}
	
	public static function defaultOption($option, $value = null) {
		if($value){
			return "<option value =\"{$value}\"  selected=\"selected\"> {$option}</option>";
		}
		return "<option value =\"{$option}\" selected=\"selected\"> {$option}</option>";
	}
	

	public static function option($option, $value = null) {
		if($value){
			return "<option value =\"{$value}\"> {$option}</option>";
		}
		return "<option value =\"{$option}\"> {$option}</option>";
	}
	
	private static function isAssoc($array)
	{
		return ($array !== array_values($array));
	}
}