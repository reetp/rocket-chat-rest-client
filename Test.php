<?php
/*
 * Real hacky bit of test code for messing about with
 * Have look through some of the files for other functions
 * I may type them up if I get time
 * I added a logout - clearly better if you can
*/

define('REST_API_ROOT', '/api/v1/');
define('ROCKET_CHAT_INSTANCE', 'https://you.server.com');
require __DIR__ . '/vendor/autoload.php';

//Finally, instance the classes you need:
$api = new \RocketChat\Client();
echo $api->version();
echo "\n";

//// login as the main admin user
//$admin = new \RocketChat\User('my-admin-name', 'my-admin-password');
//if( $admin->login() ) {
//  echo "admin user logged in\n";
//};
//$admin->info();
//echo "I'm {$admin->nickname} ({$admin->id}) "; echo "\n";


// login as the main admin user
//$bot = new \RocketChat\Model\User('rocket.cat', 'TvoMyEyq6iAFZHb5o');
$bot = new \RocketChat\Model\User('admin', '123abc');

// I chose to mod the login to return the autThjoken but I believe you can get it like this
// $token = Request::init()->headers["X-Auth-Token"];
// https://github.com/Fab1en/rocket-chat-rest-client/pull/10
if ($bot->login()) {
    echo "{$bot->name} logged in<br>";
};
$bot->info(); // Just get users info
echo "I'm {$bot->name} - ID ({$bot->id}) ";
echo "<br>";

$ChannelArray = $bot->getChannels(); //Public only
// Could set and use a room or id or other stuff
//$ID           = "4xqNsdnJHDCZxLCA7";
// Note on message if you use Emoji it replaces the AVATAR....
// Check the
// $message      = '{ "roomId": "4xqNsdnJHDCZxLCA7", "text": "Test message from PHP API", "emoji": ":smirk:" }';
// $message      = '{ "roomId": $ID, "text": "Test message from PHP API", "emoji": ":smirk:" }';
$message      = '{ "channel": "general", "text": "Test message from PHP API", "emoji": ":partying_face:" }';

// Validate the JSON to try and prevent failure
$jsonStatus   = json_validate($message);

// Error logging here
if ($jsonStatus == 'ok') {
    $channel      = new \RocketChat\Model\Room\Channel();
    $postMessage  = $channel->postInChannel($message);

    if ($postMessage) {
        // We're golden
        echo "message posted<br>";
    } else {
        //Something broke
        echo "message not posted<br>";
    }

    // error check here
    
} else {
    echo "JSON error $jsonStatus<br>";
}

//Logout regardless
$exit = $bot->logout();

//$success = $bot->logout();
echo "logged Out<br>";
if ($exit->status == 'success') {
    echo "Success: " . $exit->data->message;
} else {
    echo "Logout failed";
}

exit;
// And end
/*
 * function to check JSON
*/

function json_validate($string)
{
    // decode the JSON data
    $result = json_decode($string);

    // switch and check possible JSON errors
    switch (json_last_error()) {
    case JSON_ERROR_NONE:
        $error  = ''; // JSON is valid // No error has occurred
        break;


    case JSON_ERROR_DEPTH:
        $error = 'The maximum stack depth has been exceeded.';
        break;


    case JSON_ERROR_STATE_MISMATCH:
        $error = 'Invalid or malformed JSON.';
        break;


    case JSON_ERROR_CTRL_CHAR:
        $error = 'Control character error, possibly incorrectly encoded.';
        break;


    case JSON_ERROR_SYNTAX:
        $error = 'Syntax error, malformed JSON.';
        break;
        // PHP >= 5.3.3
        
    case JSON_ERROR_UTF8:
        $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
        break;
        // PHP >= 5.5.0
        
    case JSON_ERROR_RECURSION:
        $error = 'One or more recursive references in the value to be encoded.';
        break;
        // PHP >= 5.5.0
        
    case JSON_ERROR_INF_OR_NAN:
        $error = 'One or more NAN or INF values in the value to be encoded.';
        break;


    case JSON_ERROR_UNSUPPORTED_TYPE:
        $error = 'A value of a type that cannot be encoded was given.';
        break;


    default:
        $error = 'Unknown JSON error occured.';
        break;
    }

    if ($error !== '') {
        // throw the Exception or exit // or whatever :)
        return $error;
        //exit($error);
        
    }

    // everything is OK
    $result = "ok";
    return $result;
}
