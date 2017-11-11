<?php 
	require_once 'DbOperation.php';
	$response = array(); 

	if($_SERVER['REQUEST_METHOD']=='POST'){
	
		$email = $_POST['email'];
		$newPassword = $_POST['password'];
		
		$db = new DbOperation(); 
		
		$password = $db->getPassword($email);
		$name = $db->getUser($email);
		
		if(!empty($password)){
		
			if($password[0] == $newPassword){
				$response['error'] = false; 
				$response['message'] = 'Authentication Success';
				$response['name'] = $name[0];
				$response['email'] = $email;
				
			}else{
				$response['error'] = true; 
				$response['message'] = 'Email or password incorrect.';
			}
		
		}else{
			$response['error'] = true; 
			$response['message'] = 'No login details found on Server';
		}
	}
	
	echo json_encode($response);
