#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();

function requestProcessor($request)
{
    echo "received request".PHP_EOL;
    echo "DoLogin Function".PHP_EOL;
    registerUser($request);
  var_dump($request);
}

function registerUser($request)
{
    echo "register user called".PHP_EOL;
    $mydb = new mysqli('127.0.0.1','testuser','12345','testdb');


    if ($mydb->errno != 0)
    {
        echo "failed to connect to database: ". $mydb->error . PHP_EOL;
        exit(0);
     }
    echo "are we here".PHP_EOL;
     if(!isset($request['type']))
     {
         return "ERROR: unsupported message type";
     }

     switch ($request['type']) 
     {
          case "validate":
                $username = $request['username'];
                $query = "SELECT * FROM users WHERE username = '$username';";
                $response = $mydb->query($query);
        echo "Here as well".PHP_EOL;
                if (mysqli_num_rows($response) > 0) //already present
                {
                    $password = $request['password'];
                    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password';";
                    if (mysqli_num_rows($response) > 0)
            {
            echo "We correctly worked".PHP_EOL;
                        return array("returnCode" => '0', 'message'=>"Valid Login");            
                    }
                }

                return array("returnCode" => '0', 'message'=>"Invalid Login");
     }
}

?>