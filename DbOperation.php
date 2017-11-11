<?php

class DbOperation
{
    //Database connection link
    private $con;

    //Class constructor
    function __construct()
    {
        //Getting the DbConnect.php file
        require_once dirname(__FILE__) . '/DbConnect.php';

        //Creating a DbConnect object to connect to the database
        $db = new DbConnect();

        //Initializing our connection link of this class
        //by calling the method connect of DbConnect class
        $this->con = $db->connect();
    }

    //storing token in database 
    public function registerDevice($name, $email,$password,$newtoken){
        if(!$this->mailExist($email)){
            $stmt = $this->con->prepare("INSERT INTO registration (name, email, password, token) VALUES (?,?,?,?) ");
            $stmt->bind_param("ssss",$name,$email,$password,$newtoken);
            if($stmt->execute())
                return 0; //return 0 means success
            return 1; //return 1 means failure
        }
        elseif ($this->getTokenByMail($email)[0] == $newtoken) { //email exists 
            return 2; //returning 2 means email already exist with same token
        }else{
            $stmt = $this->con->prepare("UPDATE registration SET token=?, name=?  WHERE email=?");
                $stmt->bind_param("sss",$newtoken, $name, $email);
                if($stmt->execute())
                    return 3; //returning 3 means Token updated of already registered device
                return 1; //return 1 means failure
        }
    }

    //the method will check if email already exist 
    private function mailExist($email){
        $stmt = $this->con->prepare("SELECT srNo FROM registration WHERE email = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    //getting all tokens to send push to all registration
    public function getAllTokens(){
        $stmt = $this->con->prepare("SELECT token FROM registration");
        $stmt->execute(); 
        $result = $stmt->get_result();
        $tokens = array(); 
        while($token = $result->fetch_assoc()){
            array_push($tokens, $token['token']);
        }
        return $tokens; 
    }

    //getting a specified token to send push to selected device
    public function getTokenByMail($email){
        $stmt = $this->con->prepare("SELECT token FROM registration WHERE email = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute(); 
        $result = $stmt->get_result()->fetch_assoc();
        return array($result['token']);        
    }

	//getting a password from email
    public function getPassword($email){
        $stmt = $this->con->prepare("SELECT password FROM registration WHERE email = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute(); 
        $result = $stmt->get_result()->fetch_assoc();
        return array($result['password']);        
    }
    
    //getting a username from email
    public function getUser($email){
        $stmt = $this->con->prepare("SELECT name FROM registration WHERE email = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute(); 
        $result = $stmt->get_result()->fetch_assoc();
        return array($result['name']);        
    }

    //getting all the registered devices from database 
    public function getAllDevices(){
        $stmt = $this->con->prepare("SELECT * FROM registration");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result; 
    }
}
