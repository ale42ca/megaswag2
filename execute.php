<?php
$web="https://api.telegram.org/bot";
$token="872839539:AAGgmCXaX9zdSypFKiR4BHxoVK3U-riq3ao";
$completo="https://api.telegram.org/bot".$token;
$updates=file_get_contents("php://input");
$update=json_decode($updates, true);


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




if($testo == "/start"){
		$ms = "creiamo evento";
		sendMessage($utente, $ms);
		$ms = "certamente";
		sendMessage($utente, $ms);
		databasez($nomeutente,$dataoggi);
		exit();

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

$db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");

function inseriscidatabase($nomeutente,$dataoggi){
	$ora= "10:21";
	$quack="santa paperella";
	//$query = "INSERT INTO prenotazionistudi(data, username, ora)  VALUES (':$quack',':$quack',':$quack')";
	$query = "INSERT INTO prenotazioni (nome, quando, ora) VALUES ('$nomeutente', '08','$dataoggi')";
	$result = pg_query($query);
	
if (!$result) {
  $msg = "Ho sbaglaito qualcosa, dammi un po' di tempo";			
  sendMessage($utente, $msg); 
  
}
}	

function getlastprenotationdate(){
}

function setevent(){
}

function allarm(){
}
