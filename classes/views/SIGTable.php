<?php
class SIGTable extends UserTable {
	private $joinedGroups;
	public function __construct($records, $headers, $link, $joinedGroups){
		parent::__construct($records, $headers, $link);
		$this->_joinedGroups = $joinedGroups;
		$this->_headers[] = "Membership";
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
					if ($field === 'gid') { // assume index in first column
						$gid = $value;
						if (! in_array ( $gid, $this->_joinedGroups )) {
							$status = "Join";
							$endOfRow = "<td>
							<form action=\"\" method=\"post\" align=\"center\" onsubmit=\"return confirmJoin();\">
							<input type=\"submit\" value=\"Join\">
							<input type=\"hidden\" name=\"join\" value=\"{$gid}\">
							<input type=\"hidden\" name=\"{$token_name}\" value=\"{$token}\">
							</form></td>";
						} else {
							$endOfRow = "<td>
						<form action=\"\" method=\"post\" align=\"center\" onsubmit=\"return confirmLeave();\">
						<input type=\"submit\" value=\"Leave\">
						<input type=\"hidden\" name=\"leave\" value=\"{$gid}\">
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