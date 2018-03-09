<?php
class ActiveStatus {

	var $active_records;
	var $inactive_records;
	var $pending_records;


	/**
	 * Active Status Constructor Function
	 *
	 * @return ActiveStatus
	 */
	function __construct() {
		// In-active DB records are assigned 0
		$this->inactive_records = 0;

		// Active DB records are assigned 1
		$this->active_records = 1;

		// Pending records are assigned 2
		$this->pending_records = 2;
	}


} // End Active Status Class
?>
