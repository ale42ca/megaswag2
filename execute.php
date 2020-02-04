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
$mese = date[]
$nomeutente=$messaggio['chat']['first_name'];

  $query = $update['callback_query'];
  $queryid = $query['id'];
  $queryUserId = $query['from']['id'];
  $queryusername = $query['from']['username'];
  $querydata = $query['data'];
  $querymsgid = $query['message']['message_id'];
  
switch ($testo) {
    case "/start":
	$ms = "Ciao sono Beecky assistente virtuale di radio frequenza libera. Cosa posso fare per te?";
	sendMessage($utente, $ms);	
        tastierastart($utente);	
        break;
    case "prenota":
        $ms = "prenotiamo lo studio";
	sendMessage($utente, $ms);
	$ms = "Mi serve che tu mi dica quando vuoi prenotarlo";
	
	sendMessage($utente, $ms);
	//prenotazione();
	$ms = "per che ora?";
	sendMessage($utente, $ms);	
	$dataprenotata="oggi";
	//controllo conflitti
	inserireneldatabase($utente,$dataoggi);	
	//conferma e upload nel file	
	$ms = "Perfetto! ora invio una notifica nel gruppo";
	sendMessage($utente, $ms);
	$msgcanale= "lo studio è stato prenotato ".$dataprenotata." da ".$nomeutente;	
	inviamessaggiocanale($msgcanale);		
        break;
		
    	case "vedi prenotazioni":
        $ms = "chi ha prenotato lo studio in questa  settimana?";
	      sendMessage($utente, $ms);
	      //vediprenotazioni();	
	      prendidaldatabase($utente);
        break;
    case "calendario":
        $ms = "vediamoun po'.... se non ricordo male oggi è";
	      sendMessage($utente, $ms);
	      tastieracalendario($utente,$dataoggi);
        break;
		
    case "ciao":
        $ms = "ciao, come stai?";
	      sendMessage($utente, $ms);
	      $ms = "Sai sono sempre impegnata, ma visto che sei così gentile ti racconto una barzelletta";
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
	$testoadmin=$testo;
        break;
    case "esci":	
	tastierastart($utente);	
   	break;	
}
if($testo == "crea evento"){
		$ms = "creiamo evento";
		sendMessage($utente, $ms);
		$ms = "certamente";
		sendMessage($utente, $ms);
		exit();
}elseif($testo == "assemblea"){
		$ms = "quando vuole fare l' assemblea";
		sendMessage($admin, $ms);
		$msgcanale="prossima assemblea";		
		inviamessaggiocanale($msgcanale);	
}elseif($testo == "manda notifica"){
		$ms = "notifica inviata nel canale";
		$msgcanale="allert";
		sendMessage($admin, $ms);
		inviamessaggiocanale($msgcanale);
	
}
if($querydata == "ModificaMessaggio"){
    editMessageText($queryUserId,$querymsgid,"HEYLA!");
    exit();
}

function getdataoggi($datamessaggio){
  $datazioneunix = gmdate("d.m.y", $datamessaggio);
  return $datazioneunix;
}
	
function tastierastart($utente){
	$messaggio = "osserva la tastiera e usa i suoi comandi";
    	$tastiera = '&reply_markup={"keyboard":[["prenota"],["calendario"],["vedi prenotazioni"],["data"]]}';
    	$url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
    	file_get_contents($url);
}
function tastieracalendario($utente,$dataoggi,$mese){
    $message = $dataoggi;
   	
    if ($mese = 1 || $mese = 3 || $mese = 5 || $mese =  7 || $mese = 8 || $mese = 10 || $mese = 12 ){
      $tastiera = '&reply_markup={"inline_keyboard":[[{"text":"1","callback_data":"1"},{"text":"2","callback_data":"2"},{"text":"3","callback_data":"3"},{"text":"4","callback_data":"4"},{"text":"5","callback_data":"5"},{"text":"6","callback_data":"6"},{"text":"7","callback_data":"7"}],
      [{"text":"8","callback_data":"8"},{"text":"9","callback_data":"9"},{"text":"10","callback_data":"10"},{"text":"11","callback_data":"11"},{"text":"12","callback_data":"12"},{"text":"13","callback_data":"13"},{"text":"14","callback_data":"14"}],
      [{"text":"15","callback_data":"15"},{"text":"16","callback_data":"16"},{"text":"17","callback_data":"17"},{"text":"18","callback_data":"18"},{"text":"19","callback_data":"19"},{"text":"20","callback_data":"20"},{"text":"21","callback_data":"21"}],
      [{"text":"22","callback_data":"22"},{"text":"23","callback_data":"23"},{"text":"24","callback_data":"24"},{"text":"25","callback_data":"25"},{"text":"26","callback_data":"26"},{"text":"27","callback_data":"27"},{"text":"28","callback_data":"28"}],
      [{"text":"29","callback_data":"29"},{"text":"30","callback_data":"30"},{"text":"31","callback_data":"31"},{"text":" "},{"text":" "},{"text":" "},{"text":"14"}],[{"text":"prima","callback_data":"meseprima"},{"text":" x ","callback_data":"esci"},{"text":"dopo","callback_data":"mesedopo"}]]}';
      
    }else if ($mese = 2){
      $tastiera = '&reply_markup={"inline_keyboard":[[{"text":"1","callback_data":"1"},{"text":"2","callback_data":"2"},{"text":"3","callback_data":"3"},{"text":"4","callback_data":"4"},{"text":"5","callback_data":"5"},{"text":"6","callback_data":"6"},{"text":"7","callback_data":"7"}],
      [{"text":"8","callback_data":"8"},{"text":"9","callback_data":"9"},{"text":"10","callback_data":"10"},{"text":"11","callback_data":"11"},{"text":"12","callback_data":"12"},{"text":"13","callback_data":"13"},{"text":"14","callback_data":"14"}],
      [{"text":"15","callback_data":"15"},{"text":"16","callback_data":"16"},{"text":"17","callback_data":"17"},{"text":"18","callback_data":"18"},{"text":"19","callback_data":"19"},{"text":"20","callback_data":"20"},{"text":"21","callback_data":"21"}],
      [{"text":"22","callback_data":"22"},{"text":"23","callback_data":"23"},{"text":"24","callback_data":"24"},{"text":"25","callback_data":"25"},{"text":"26","callback_data":"26"},{"text":"27","callback_data":"27"},{"text":"28","callback_data":"28"}],
      [{"text":"29","callback_data":"29"},{"text":" "},{"text":" "},{"text":" "},{"text":" "},{"text":" "},{"text":"14"}],[{"text":"prima","callback_data":"meseprima"},{"text":" x ","callback_data":"esci"},{"text":"dopo","callback_data":"mesedopo"}]]}';
      
    }else {
      $tastiera = '&reply_markup={"inline_keyboard":[[{"text":"1","callback_data":"1"},{"text":"2","callback_data":"2"},{"text":"3","callback_data":"3"},{"text":"4","callback_data":"4"},{"text":"5","callback_data":"5"},{"text":"6","callback_data":"6"},{"text":"7","callback_data":"7"}],
      [{"text":"8","callback_data":"8"},{"text":"9","callback_data":"9"},{"text":"10","callback_data":"10"},{"text":"11","callback_data":"11"},{"text":"12","callback_data":"12"},{"text":"13","callback_data":"13"},{"text":"14","callback_data":"14"}],
      [{"text":"15","callback_data":"15"},{"text":"16","callback_data":"16"},{"text":"17","callback_data":"17"},{"text":"18","callback_data":"18"},{"text":"19","callback_data":"19"},{"text":"20","callback_data":"20"},{"text":"21","callback_data":"21"}],
      [{"text":"22","callback_data":"22"},{"text":"23","callback_data":"23"},{"text":"24","callback_data":"24"},{"text":"25","callback_data":"25"},{"text":"26","callback_data":"26"},{"text":"27","callback_data":"27"},{"text":"28","callback_data":"28"}],
      [{"text":"29","callback_data":"29"},{"text":"30","callback_data":"30"},{"text":" "},{"text":" "},{"text":" "},{"text":" "},{"text":"14"}],[{"text":"prima","callback_data":"meseprima"},{"text":" x ","callback_data":"esci"},{"text":"dopo","callback_data":"mesedopo"}]]}';
      
    }
    
    $url = $GLOBALS[completo].'/sendMessage?chat_id='.$utente.'&parse_mod=HTML&text='.$message.$tastiera;
    file_get_contents($url);
}


// database prenotazioni avrà ir, utente, data, orario
// database birre avrà ir, utente, aggiungi birre
// database fan ir utente
// database pulizie ir data
// i comandi necessari a utilizzare il database sono inserisci nel database, cancella ultima row,

//insericsci nel database

