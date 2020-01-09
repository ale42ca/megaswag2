<?php
//url
$web="https://api.telegram.org/bot";
$token="872839539:AAGgmCXaX9zdSypFKiR4BHxoVK3U-riq3ao";
$completo="https://api.telegram.org/bot".$token;
$updates=file_get_contents("php://input");
$update=json_decode($updates, true);


$updates = json_decode(file_get_contents(set_get_updates_parameters("https://api.telegram.org/bot872839539:AAGgmCXaX9zdSypFKiR4BHxoVK3U-riq3ao/getUpdates?offset=100")), true);
// Separate every update in $updates
$isNewRequest = is_new_request($update["update_id"]); 
if ($isNewRequest === false || $isNewRequest === null){
	exit;	
}
elseif(!$update){
  exit;
}
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
$dataoggi = getdataoggi($datazioneunix);
$mese=date("m");
//switch
//programma invia i messaggi
if($testo == "bene"){
	$messagio='&reply_markup={"keyboard":[["crea evento"],["assemblea"],["manda notifica"],["esci"]]}';
	inviamessaggio($utente,$messaggio);
}elseif($testo == "/start"){
	
	$messagio= "boi";
	inviamessaggio($utente,$messaggio);
}
function inviamessaggio($utente,$messaggio){
	 
	$parameters = array('chat_id' => $utente, "text" => $messaggio);
	$parameters["method"] = "sendMessage";
	echo json_encode($parameters);
}
	




?>
