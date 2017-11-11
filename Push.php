<?php 

class Push {
    //title
    private $title;

    //message 
    private $message;

    //device token 
    private $token;

    //initializing values in this constructor
    function __construct($title, $message, $token) {
         $this->title = $title;
         $this->message = $message;
	 $this->token = $token;
    }
    
    //getting the push notification
    public function getPush() {
        $res = array();
        $res['data']['title'] = $this->title;
        $res['data']['message'] = $this->message;
		$res['data']['token'] = $this->token;
        return $res;
    }
 
}
