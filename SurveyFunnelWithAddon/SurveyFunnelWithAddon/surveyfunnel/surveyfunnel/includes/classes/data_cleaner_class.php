<?php
class DataCleaner {

	var $error;
	var $dataErrors = array();
	
	
	/**
	 * Data Cleaner Constructor Function
	 *
	 * @return DataCleaner
	 */
	function __construct () {
        $this->error = false;
	}
	
	
	function validEmail($email, $iKey) {
		$email = trim($email);
		$vpemail = preg_match('/^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$/', $email);

		//if ($vpemail == 0) {
		if (($vpemail == 0) || ($email == "") || ($email == null)) {
			$this->dataErrors[$iKey] = true;
			$this->error = true;
		}
		
		return (!$this->error);
	}
	

	function validUrl($iUrl, $iKey) {
		$iUrl = trim($iUrl);
		$vurl = preg_matchi("/^((ht|f)tp://)((([a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))|(([0-9]{1,3}\.){3}([0-9]{1,3})))((/|\?)[a-z0-9~#%&'_\+=:\?\.-]*)*)$/", $iUrl);
		
		if (($vurl == 0) || ($iUrl == "") || ($iUrl == null)) {
			$this->dataErrors[$iKey] = true;
			$this->error = true;
		}
		
		return (!$this->error);
	}
	

	function validAscii($iData, $iKey) {
		$vascii = preg_match('/^([-\, \'.a-zA-Z]*$)/', $iData);
		
		if (($vascii == 0) || ($iData == "") || ($iData == null)) {
			$this->dataErrors[$iKey] = true;
			$this->error = true;
		}
		
		return (!$this->error);
	}

	
	function validData($iData, $iKey) {
		//if (($iData == "") || ($iData == null)) {
		if (($iData == "") || ($iData == null) || ($iData == '%')) {
			$this->dataErrors[$iKey] = true;
			$this->error = true;
		}
		
		return (!$this->error);
	}

	function validateDataForDefaultHeader($iData, $iKey) {
		if (($iData == '%')) {
			$this->dataErrors[$iKey] = true;
			$this->error = true;
		}
	
		return (!$this->error);
	}

	
	function validAddress($iData, $iKey) {
		//if (($iData == "") || ($iData == null)) {
		if (($iData == "") || ($iData == null) || ($iData == '%')) {
			$this->dataErrors[$iKey] = true;
			$this->error = true;
		}
		
		return (!$this->error);
	}

	
	function validNum($iData, $iKey) {
		$vnum = preg_match('/^([.0-9]*$)/', $iData);
		
		if (($vnum == 0) || ($iData == "") || ($iData == null)) {
			$this->dataErrors[$iKey] = true;
			$this->error = true;
		}
		
		return (!$this->error);
	}

	
	function validCurrency($iData, $iKey) {
		$iData = trim($iData);
		$vnum = preg_match('/^([0-9]{2})(.)([0-9]{2})?$/', $iData);
		
		if (($vnum == 0) || ($iData == "") || ($iData == null)) {
			$this->dataErrors[$iKey] = true;
			$this->error = true;
		}
		
		return (!$this->error);
	}
	

	function validZip($iData, $iKey) {
		$vzip = preg_match('/^[0-9]{5}$/', $iData);
		
		if (($vzip == 0) || ($iData == "") || ($iData == null)) {
			$this->dataErrors[$iKey] = true;
			$this->error = true;
		}
		
		return (!$this->error);
	}

	
	function validPhone($iData, $iKey) {
		
		$vphone = preg_match('/^[(]?[2-9]{1}[0-9]{2}[) -.]{0,2}[0-9]{3}[-. ]?[0-9]{4}[ ]?/', $iData);
		
		if (($vphone == 0) || ($iData == "") || ($iData == null)) {
			$this->dataErrors[$iKey] = true;
			$this->error = true;
		}
		
		return (!$this->error);
	}
	
	
	function validSSN($iData, $iKey) {
		$vphone = preg_match('/^[0-9]{3}(-?| ?)([0-9]{2})(-?| ?)([0-9]{4})?$/', $iData);
		
		if (($vphone == 0) || ($iData == "") || ($iData == null)) {
			$this->dataErrors[$iKey] = true;
			$this->error = true;
		}
		
		return (!$this->error);
	}
	
	
	function validDate($iData, $iKey) {
		$vdate = preg_match('/^[0-9]{1,2}(-?|/?)([0-9]{1,2})(-?|/?)([0-9]{4})?$/', $iData);
		
		if (($vdate == 0) || ($iData == "") || ($iData == null)) {
			$this->dataErrors[$iKey] = true;
			$this->error = true;
		}
		
		return (!$this->error);
	}
	
	
	function validTime($iData, $iKey) {
		$vtime = preg_match('/^[0-9]{1,2}:([0-9]{2})?$/', $iData);
		
		if (($vtime == 0) || ($iData == "") || ($iData == null)) {
			$this->dataErrors[$iKey] = true;
			$this->error = true;
		}
		
		return (!$this->error);
	}
		
	
	function validManual($iKey, $iMessage) {
		//$this->dataErrors[$iKey] = true;
		$this->dataErrors[$iKey] = "<span class=\"rtext12\">$iMessage</span>";
	   	$this->error = true;
	   
		return (!$this->error);
	}
	
	
	function validArray($iData, $iKey) {
		if ((!is_array($iData)) || (count($iData) == 0)) {
			$this->dataErrors[$iKey] = true;
			$this->error = true;
		}
		
		return (!$this->error);
	}	
	
	
	function validFile($iData, $iKey) {
		if (($iData == "") || ($iData == null) || (!file_exists($iData))) {
			$this->dataErrors[$iKey] = true;
			$this->error = true;
		}
		
		return (!$this->error);
	}
	
	
} // End DataCleaner Class
?>
