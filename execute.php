<?php
//url
$web="https://api.telegram.org/bot";
$token="872839539:AAGgmCXaX9zdSypFKiR4BHxoVK3U-riq3ao";
$completo="https://api.telegram.org/bot".$token;
$updates=file_get_contents("php://input");
$update=json_decode($updates, true);

$updates = json_decode(file_get_contents(set_get_updates_parameters("https://api.telegram.org/bot872839539:AAGgmCXaX9zdSypFKiR4BHxoVK3U-riq3ao/getUpdates?offset=100")), true);
// Separate every update in $updates

$messaggio=$update['message'];
$message_id=$update['message']['message_id'];
$testo=$messaggio['text'];
$utente=$messaggio['chat']['id'];
$utente=$messaggio['chat']['id'];
$datazioneunix=$messaggio['date'];
$dataoggi = getdataoggi($datazioneunix);
$mese=date("m");
$nomeutente=$messaggio['chat']['first_name'];

// collegamento al database 
$db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");
//start
switch ($testo) {
    case "/start":
	$comando = '1';
	$ms = "benvenuto admin";
	prendidaldatabase($comando,$db);
	sendMessage($utente, $ms);	

        break;
   
    case "esci":
	$ms = "benvenuto admin";
	sendMessage($utente, $ms);
   	break;	
}


//manda messaggio
function sendMessage($utente, $msg){
		$url = $GLOBALS[completo]."/sendMessage?chat_id=".$utente."&text=".urlencode($msg);
		file_get_contents($url);
}


// prendi text
function prendidaldatabase($comando,$db){

	$result = pg_query($db,"SELECT comando FROM comandi WHERE numero = $comando "); 
	
	while($row=pg_fetch_assoc($result)){
		$msg=$row['comando'] ;
		$url = $GLOBALS[completo]."/sendMessage?chat_id=".$utente."&text=".urlencode($msg);
		file_get_contents($url);	
	}
}

