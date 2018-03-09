<?php
class SF_JSON {

	var $jsonData = array();

	/**
	 * Constructor function
	 *
	 * @return JSON
	 */
	function __construct() {
		if (func_num_args() > 0) {
			$this->jsonData = func_get_arg(0);
		}
	}


	/**
	 * Build out the XML display and echo it to the page
	 *
	 */
	function displayJSON() {
		//header('Content-type: application/json');

		//$this->jsonData->action = '';
		$this->jsonData->password = '';

		$this_data = $this->jsonData;



		echo str_replace('\\\\','',json_encode($this->jsonData));
		exit;
	}


	function encodeJSON($iDataObj) {
		if (!$iDataObj) {
			return "";
		}

		//$iDataObj->action = '';

		return json_encode($iDataObj);
	}


	function dencodeJSON($iJSONData) {
		if (!$iJSONData) {
			return "";
		}

		return json_decode($iJSONData);
	}


} // End SF_JSON Class
?>
