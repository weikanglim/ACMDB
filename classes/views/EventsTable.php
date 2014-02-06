<?php
class EventsTable extends UserTable {
	private $joinedEvents;
	public function __construct($records, $headers, $link, $joinedEvents){
		parent::__construct($records, $headers, $link);
		$this->_joinedEvents = $joinedEvents;
		$this->_headers[] = "RSVP";
	}
	
	protected function generateTableTop(){
		
	}
	
	protected function generateTableBody(){
		$generated = "";
		$records = $this->_records;
		$token = Token::generate ('participate_token');
		$token_name = 'ptoken';
		if (count ( $records )) {
			$generated .= "<tbody>";
			$x = 0;
			foreach ( $records as $record ) {
				// Start of row
				$generated .= "<tr>";
				foreach ( $record as $field => $value ) {
					if ($field === 'eid') { // assume index in first column
						$eid = $value;
						if (! in_array ( $eid, $this->_joinedEvents )) {
							$endOfRow = "<td>
								<form action=\"\" method=\"post\" align=\"center\" onsubmit=\"return confirmJoin();\">
								<input type=\"submit\" value=\"RSVP\">
								<input type=\"hidden\" name=\"join\" value=\"{$eid}\">
								<input type=\"hidden\" name=\"{$token_name}\" value=\"{$token}\">
								</form></td>";
						} else {
							$endOfRow = "<td>
								<form action=\"\" method=\"post\" align=\"center\" onsubmit=\"return confirmLeave();\">
								<input type=\"submit\" value=\"Cancel\">
								<input type=\"hidden\" name=\"leave\" value=\"{$eid}\">
								<input type=\"hidden\" name=\"{$token_name}\" value=\"{$token}\">
								</form></td>";
						}
					}
					$generated .= "<td>{$value}</td>";
				}
				$generated .= $endOfRow . "</tr>";
				$x ++;
				// End of row
			}
			$generated .= "</tbody>";
		}
		$this->_tableBody = $generated;
	}
	

	
}