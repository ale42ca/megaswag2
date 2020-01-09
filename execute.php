<?php
//url
$web="https://api.telegram.org/bot";
$token="872839539:AAGgmCXaX9zdSypFKiR4BHxoVK3U-riq3ao";
$completo="https://api.telegram.org/bot".$token;
$updates=file_get_contents("php://input");
$update=json_decode($updates, true);



//messaggio
$messaggio=$update['message'];
$message_id=$update['message']['message_id'];
$testo=$messaggio['text'];
$utente=$messaggio['chat']['id'];
$utente=$messaggio['chat']['id'];
$nomeutente=$messaggio['chat']['first_name'];
//query
$query = $update['callback_query'];
$queryid = $query['id'];
$queryUserId = $query['from']['id'];
$queryusername = $query['from']['username'];
$querydata = $query['data'];
$querymsgid = $query['message']['message_id'];
//datazione
$datazioneunix=$messaggio['date'];
//$dataoggi = getdataoggi($datazioneunix);
$mese=date("m");
//switch
//programma invia i messaggi
if($testo == "bene"){
	$messagio='&reply_markup={"keyboard":[["crea evento"],["assemblea"],["manda notifica"],["esci"]]}';
	inviamessaggio($utente,$messaggio);
}elseif($testo == "/start"){
	
	$messagio= "boi";
	inviamessaggio($utente,$messaggio);
	inviamessaggio($utente,$messaggio);
}
function inviamessaggio($utente,$messaggio){
	header("Content-Type: application/json");
	$parameters = array('chat_id' => $utente, "text" => $testo);
	$parameters["method"] = "sendMessage";
	$parameters["reply_markup"] = '{ "keyboard": [["uno"], ["due"], ["tre"], ["quattro"]], "one_time_keyboard": false}';
	echo json_encode($parameters);
}
	





