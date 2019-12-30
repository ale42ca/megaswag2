<?php

$web="https://api.telegram.org/bot";
$token="872839539:AAGgmCXaX9zdSypFKiR4BHxoVK3U-riq3ao";
$completo="https://api.telegram.org/bot".$token;

$update=file_get_contents("php://input");
$update=json_decode($updates, true);
$upquack=$update['update_id'];
$updot=$upquack + 3;
echo json_encode($upquack);

if ( $upquack > $updot || $upquack === null)
	exit;	
elseif(!$update){
  exit;
}

$messaggio=$update['message'];
$testo=$messaggio['text'];
$utente=$messaggio['chat']['id'];
$datazioneunix=$messaggio['date'];
$dataoggi = getdataoggi($datazioneunix);
$ultimomsg=$messaggio['message_id'];

$query = $update['callback_query'];
$queryid = $query['id'];
$queryUserId = $query['from']['id'];
$queryusername = $query['from']['username'];
$querydata = $query['data'];
$querymsgid = $query['message']['message_id'];



$msgcanale="fico";
switch ($testo) {
    case "/start":
        $ms = "ciao";
	sendMessage($utente, $ms);
	tastierastart($utente);	
        break;
    case "prenota":
        $ms = "prenotiamo lo studio";
	sendMessage($utente, $ms);
	inviamessaggiocanale($msgcanale);	

        break;
    case "vedi prenotazioni":
        $ms = "chi ha prenotato lo studio nell' ultima settimana?";
	sendMessage($utente, $ms);
	
        break;
    case "calendario":
        $ms = "vediamoun po'.... se non ricordo male oggi è";
	sendMessage($utente, $ms);
	tastieracalendario($utente,$dataoggi);
        break;		
    case "ciao":
        $ms = "ciao, come stai?";
	sendMessage($utente, $ms);
        break;
    case "data":
	$ms = "Oggi è";
	sendMessage($utente, $ms);
        sendMessage($utente, $dataoggi);
        break;
    case "1admin":
	$ms = "benvenuto admin";
	sendMessage($utente, $ms);
		
	comandiadmin($utente,$testo);
        break;
    case "esci":	
	tastierastart($utente);	
   	break;	
    default:
        $ms = "non ho capito";
	sendMessage($utente, $ms);
}
if($querydata == "ModificaMessaggio"){
    editMessageText($queryUserId,$querymsgid,"HEYLA!");
    exit();
}

	
function tastierastart($utente){
	$messaggio = "osserva la tastiera e usa i suoi comandi";
    	$tastiera = '&reply_markup={"keyboard":[["prenota"],["calendario"],["vedi prenotazioni"],["data"]]}';
    	$url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
    	file_get_contents($url);
}
function tastieracalendario($utente,$dataoggi){
    $message = $dataoggi;
   	
    $tastiera = '&reply_markup={"inline_keyboard":[[{"text":"1","callback_data":"Prenota"},{"text":"2","callback_data":"Prenota"},{"text":"3","callback_data":"Prenota"},{"text":"4","callback_data":"Prenota"},{"text":"5","callback_data":"Prenota"},{"text":"6","callback_data":"Prenota"},{"text":"7","callback_data":"Prenota"}]]}';
    $tastiera2 = '&reply_markup={"inline_keyboard":[[{"text":"8","callback_data":"Prenota"},{"text":"9","callback_data":"Prenota"},{"text":"10","callback_data":"Prenota"},{"text":"11","callback_data":"Prenota"},{"text":"12","callback_data":"Prenota"},{"text":"13","callback_data":"Prenota"},{"text":"14","callback_data":"Prenota"}]]}';

    $url = $GLOBALS[completo].'/sendMessage?chat_id='.$utente.'&parse_mod=HTML&text='.$message.$tastiera;
    $url2 = $GLOBALS[completo].'/sendMessage?chat_id='.$utente.'&parse_mod=HTML'.$message.$tastiera2;	
    file_get_contents($url);

}
function sendMessage($utente, $msg){
		$url = $GLOBALS[completo]."/sendMessage?chat_id=".$utente."&text=".urlencode($msg);
		file_get_contents($url);
}

function editMessageText($chatId,$message_id,$newText){
    $url = $GLOBALS[completo]."/editMessageText?chat_id=$chatId&message_id=$message_id&parse_mode=HTML&text=".urlencode($newText);
    file_get_contents($url);
  }

function inviamessaggiocanale($msg){
	$utente = "@santacaterina2";
	$url = $GLOBALS[completo]."/sendMessage?chat_id=".$utente."&text=".urlencode($msg);
	file_get_contents($url);
}

function comandiadmin($utente,$testoadmin,$prendofile){
	$messaggio = "cosa vuole fare admin?";
    	$tastiera = '&reply_markup={"keyboard":[["crea evento"],["assemblea"],["manda notifica"],["esci"]]}';
	$url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
	file_get_contents($url);
		
	if($testoadmin == "crea evento"){
		$ms = "certamente";
		sendMessage($utente, $ms);
		exit();
	}
/*
	switch ($testoadmin) {
    		case "crea evento":
        	$ms = "certamente";
		sendMessage($admin, $ms);
		
        	break;
		case "assemblea":
		$ms = "quando vuole fare l' assemblea";
		$msgcanale="prossima assemblea";
		sendMessage($admin, $ms);
		inviamessaggiocanale($msgcanale);	

		break;
		case "manda notifica":
		$ms = "notifica inviata";
		$msgcanale="allert";
		sendMessage($admin, $ms);
		inviamessaggiocanale($msgcanale);	

		break;			
    		case "esci":	
		tastierastart($utente);	
   		break;
	}
*/	
	
}
//data
function getdataoggi($datamessaggio){
  $datazioneunix = gmdate("d.m.y", $datamessaggio);
  return $datazioneunix;
}


//header("Content-Type: application/json");
//$msg="vuoi fare altro?"; 
//$parameters = array('chat_id' => $utente, "text" => $msg);
//$parameters["method"] = "sendMessage";
//echo json_encode($parameters);
