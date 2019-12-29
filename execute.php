<?php

$web="https://api.telegram.org/bot";
$token="872839539:AAGgmCXaX9zdSypFKiR4BHxoVK3U-riq3ao";
$completo="https://api.telegram.org/bot".$token;
$prendofile=file_get_contents("php://input");
$informazioni=json_decode($prendofile, true);
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
    return str_replace("200", $lastUpdateId, $getUpdates);
}

$updates = json_decode(file_get_contents(set_get_updates_parameters("https://api.telegram.org/bot872839539:AAGgmCXaX9zdSypFKiR4BHxoVK3U-riq3ao/getUpdates?offset=200")), true);

// Separate every update in $updates

$isNewRequest = is_new_request($update["update_id"]); // $update["update_id"] is update_id of one of your requests; e.g. 591019242
if ($isNewRequest === false || $isNewRequest === null)
	exit;	
elseif(!$informazioni){
  exit;
}
$messaggio=$informazioni['message'];
$testo=$messaggio['text'];
$utente=$messaggio['chat']['id'];
$datazioneunix=$messaggio['date'];
$dataoggi = getdataoggi($datazioneunix);
  $query = $informazioni['callback_query'];
  $queryid = $query['id'];
  $queryUserId = $query['from']['id'];
  $queryusername = $query['from']['username'];
  $querydata = $query['data'];
  $querymsgid = $query['message']['message_id'];
switch ($testo) {
    case "/start":
        $ms = "ciao";
	sendMessage($utente, $ms);
	tastierastart($utente);	
        break;
    case "prenota":
        $ms = "prenotiamo lo studio";
	sendMessage($utente, $ms);
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
	comandiadmin($utente);
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
   	
    $tastiera = '&reply_markup={"inline_keyboard":[[{"text":"1","callback_data":"Prenota"},{"text":"2","callback_data":"Prenota"},{"text":"3","callback_data":"Prenota"},{"text":"4","callback_data":"Prenota"},{"text":"5","callback_data":"Prenota"},{"text":"6","callback_data":"Prenota"},{"text":"7","callback_data":"Prenota"}],[{"text":"8","callback_data":"Prenota"},{"text":"9","callback_data":"Prenota"},{"text":"10","callback_data":"Prenota"},{"text":"11","callback_data":"Prenota"},{"text":"12","callback_data":"Prenota"},{"text":"13","callback_data":"Prenota"},{"text":"15","callback_data":"Prenota"}], [{"text":"16","callback_data":"Prenota"},{"text":"17","callback_data":"Prenota"},{"text":"18","callback_data":"Prenota"},{"text":"19","callback_data":"Prenota"},{"text":"20","callback_data":"Prenota"},{"text":"21","callback_data":"Prenota"},{"text":"22","callback_data":"Prenota"}] ,[{"text":"23","callback_data":"Prenota"},{"text":"24","callback_data":"Prenota"},{"text":"25","callback_data":"Prenota"},{"text":"26","callback_data":"Prenota"},{"text":"27","callback_data":"Prenota"},{"text":"28","callback_data":"Prenota"},{"text":"29","callback_data":"Prenota"}], [{"text":"30","callback_data":"Prenota"},{"text":"31","callback_data":"Prenota"}, {"text":"mese +","callback_data":"ModificaMessaggio"},{"text":"mese -","callback_data":"ModificaMessaggio"]]}';
    
    $url = $GLOBALS[completo].'/sendMessage?chat_id='.$utente.'&parse_mod=HTML&text='.$message.$tastiera;
    file_get_contents($url);
}
function sendMessage($utente, $msg){
		$url = $GLOBALS[completo]."/sendMessage?chat_id=".$utente."&text=".urlencode($msg);
		file_get_contents($url);
}
function getdataoggi($datamessaggio){
  $datazioneunix = gmdate("d.m.y", $datamessaggio);
  
  return $datazioneunix;
}
  function editMessageText($chatId,$message_id,$newText)
  {
    $url = $GLOBALS[completo]."/editMessageText?chat_id=$chatId&message_id=$message_id&parse_mode=HTML&text=".urlencode($newText);
    file_get_contents($url);
  }
function comandiadmin($utente){
	$messaggio = "cosa vuole fare admin?";
    	$tastiera = '&reply_markup={"keyboard":[["crea evento"],["assemblea"],["manda notifica"],["esci da admin"]]}';
    		
		
	
	$url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
	file_get_contents($url);
	
}
//header("Content-Type: application/json");
//$msg="vuoi fare altro?"; 
//$parameters = array('chat_id' => $utente, "text" => $msg);
//$parameters["method"] = "sendMessage";
//echo json_encode($parameters);
