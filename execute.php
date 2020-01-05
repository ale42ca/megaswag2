<?php
$web="https://api.telegram.org/bot";
$token="872839539:AAGgmCXaX9zdSypFKiR4BHxoVK3U-riq3ao";
$completo="https://api.telegram.org/bot".$token;
$updates=file_get_contents("php://input");
$update=json_decode($updates, true);
function is_new_request($requestUpdateId)
{
    $filename = "./last_update_id.txt";
    if (filesize($filename)) {
        $file = fopen($filename, "w");
        if ($file) {
            fwrite($file, $requestUpdateId);
            fclose($file);
            return true;
        } else
            return null;
    } else {
        $file = fopen($filename, "w");
        fwrite($file, 1);
        fclose($file);
        return false;
    }
}
function set_get_updates_parameters($getUpdates)
{
    $filename = "./last_update_id.txt";
    if (file_exists($filename)) {
        $file = fopen($filename, "r");
        $lastUpdateId = fgets($file);
        fclose($file);
    } else {
        $file = fopen($filename, "w");
        $lastUpdateId = fwrite($file, 1);
        fclose($file);
    }
    return str_replace("100", $lastUpdateId, $getUpdates);
}
$updates = json_decode(file_get_contents(set_get_updates_parameters("https://api.telegram.org/bot872839539:AAGgmCXaX9zdSypFKiR4BHxoVK3U-riq3ao/getUpdates?offset=100")), true);
// Separate every update in $updates
$isNewRequest = is_new_request($update["update_id"]); // $update["update_id"] is update_id of one of your requests; e.g. 591019242
if ($isNewRequest === false || $isNewRequest === null){
	exit;	
	}
elseif(!$update){
  exit;
}

$messaggio=$update['message'];
$message_id=$update['message']['message_id'];
$testo=$messaggio['text'];
$utente=$messaggio['chat']['id'];
$utente=$messaggio['chat']['id'];
$datazioneunix=$messaggio['date'];
$dataoggi = getdataoggi($datazioneunix);
$nomeutente=$messaggio['chat']['first_name'];
  $query = $update['callback_query'];
  $queryid = $query['id'];
  $queryUserId = $query['from']['id'];
  $queryusername = $query['from']['username'];
  $querydata = $query['data'];
  $querymsgid = $query['message']['message_id'];



if($testo == "a"){
		$ms = "creiamo evento";
		sendMessage($utente, $ms);
		$ms = "certamente";
		sendMessage($utente, $ms);
		databasez($nomeutente,$dataoggi);
		

}

function sendMessage($utente, $msg){
		$url = $GLOBALS[completo]."/sendMessage?chat_id=".$utente."&text=".urlencode($msg);
		file_get_contents($url);
}



//data
function getdataoggi($datamessaggio){
  $datazioneunix = gmdate("d.m.y", $datamessaggio);
  return $datazioneunix;
}


function inseriscidatabase($nomeutente,$dataoggi){
	$ora= "10:21";
	$quack="santa paperella";
	$db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");
	$query = "INSERT INTO prenotazioni (nome, quando, ora) VALUES ('$nomeutente', '08','$dataoggi')";
	$result = pg_query($query);
	
if (!$result) {
  $msg = "Ho sbaglaito qualcosa, dammi un po' di tempo";			
  sendMessage($utente, $msg); 
  
}
}	
/*
function getlastprenotationdate(){
}

function setevent(){
}

function allarm(){
}
*/
