<?php 
	require_once 'DbOperation.php';
	$response = array(); 

	if($_SERVER['REQUEST_METHOD']=='POST'){

		$token = $_POST['token'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		$password = $_POST['password'];

		$db = new DbOperation(); 

		$result = $db->registerDevice($name, $email, $password, $token);

		if($result == 0){
			$response['error'] = false; 
			$response['message'] = 'Device registered successfully';
		}elseif($result == 2){
			$response['error'] = false; 
			$response['message'] = 'Device already registered';
		}elseif($result == 3){
			$response['error'] = false; 
			$response['message'] = 'Token updates of already registered Device.';
		}else{
			$response['error'] = true;
			$response['message']='Device not registered';
		}
	}else{
		$response['error']=true;
		$response['message']='Invalid Request...';
	}

	echo json_encode($response);
