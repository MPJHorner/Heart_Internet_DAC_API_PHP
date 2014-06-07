<?php

/* Configure */
$AccessToken = ''; //Required! DAC Access Token provided by Heart Internet.
$DomainName = 'domain.com'; //Required! Single domain (including extension) or Array of Domains such as = array('domain.com', 'domaintwo.com')
$Extensions = array('.com', '.net', '.co.uk'); //Not required. Array of additional extensions to check.
$Premium = false; //Not required. Checks for associated premium domains.
$Suggestion = false; //Not required. Checks for associated suggested domains.
$Incremental = false; //Not required. Returns results incrementally, won't work with PHP, leave as false.
/* Configure */

class DomainCheck{
	
	public $AccessToken;
	public $JSONData;
	
	function __contruct($AccessToken){
		$this->AccessToken = base64_encode($AccessToken)
	}
	
	function AddData($DomainName, $Extensions, $Premium, $Suggestion, $Incremental){
		$Data = array(
							'name' => $DomainName,
							'ext' => $Extensions,
							'includePremium' => $Premium,
							'includeSuggestion' => $Suggestion,
							);
		$this->JSONData = json_encode($Data);
	}
	
	function DoCheck(){
		$this->CreateJSON();
		$http_headers = array('Authorization: Bearer ' . $this->AccessToken, 'Content-Type: application/json');
		$ch = curl_init();                    
		curl_setopt($ch, CURLOPT_URL,'http://api.heartinternet.co.uk/cx/dac.cgi');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->JSONData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
		$output = curl_exec ($ch);
		curl_close ($ch);
		$this->JSONResponse = $output;
		$this->Response = json_decode($output);
	}
	
}

$DomainCheck = new DomainCheck($AccessToken);
$DomainCheck->AddData($DomainName, $Extensions, $Premium, $Suggestion, $Incremental);
$DomainCheck->DoCheck();

//$JSON_Output = $DomainCheck->JSONResponse;
//var_dump($JSON_Output);

$Output = $DomainCheck->Response; //Get response
var_dump($Output); //Dump response

?>