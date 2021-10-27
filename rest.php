<?php
error_reporting(1);
   if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Headers: accept, content-type");
	header('Access-Control-Allow-Methods: POST');
	header('Access-Control-Allow-Origin: *');    // cache for 1 day
	header('Access-Control-Allow-Credentials: false'); 
    }
   // echo "<pre>";print_r($_REQUEST);die;
include("functions.php");
include("en_us.lang.php");
$app_list_strings;
$api_key = '';
$method = '';
$response = array();

if(isset($_REQUEST['api_key'])) {
	$api_key = $_REQUEST['api_key'];	
}

if(isset($_REQUEST['method'])) {
	$method = $_REQUEST['method'];	
}


$params = $_REQUEST;
//echo "<pre>";print_r($params);die;
$obj = new Functions();
$apicheck = $obj->validateKey($api_key);
$response = array();
	
	if($apicheck){
		$methodcheck = $obj->validateMethod($method);
		if($methodcheck){
			//die('Hi');
			$response = $obj->callMethod($method,$params);
			
		} else {
			$response = array(
						"ResponseCode"=> $app_list_strings['METHOD_FAILURE_CODE'],
						"Message" => $app_list_strings['METHOD_FAILURE_MSG'],
						"Success"=>false, 
						"Resonse"=>""
					);
		}
	} else {

			$response = array(
						"ResponseCode"=> $app_list_strings['APIKEY_FAILURE_CODE'],
						"Message" => $app_list_strings['APIKEY_FAILURE_MSG'],
						"Success"=>false, 
						"Resonse"=>""
					);
		
	}

	header('Content-type: application/json');
	$obj->echoResponse($response);
?>
