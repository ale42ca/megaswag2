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
$mese=date("m");
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
	deleterow();	
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
if($querydata == "Prenota"){
    editMessageText($queryUserId,$querymsgid,"HEYLA!");
    exit();
}elseif($querydata == "Si"){
    editMessageText($queryUserId,$querymsgid,"HEYLA!");
    exit();
}elseif($querydata == "No"){
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
    $mesecalendario=date("m");/*
    $tastiera = '&reply_markup={"inline_keyboard":[[{"text":"1","callback_data":"Prenota"},{"text":"2","callback_data":"Prenota"},{"text":"3","callback_data":"Prenota"},{"text":"4","callback_data":"Prenota"},{"text":"5","callback_data":"Prenota"},{"text":"6","callback_data":"Prenota"},{"text":"7","callback_data":"Prenota"}],[{"text":"8","callback_data":"Prenota"},{"text":"9","callback_data":"Prenota"},{"text":"10","callback_data":"Prenota"},{"text":"11","callback_data":"Prenota"},{"text":"12","callback_data":"Prenota"},{"text":"13","callback_data":"Prenota"},{"text":"14","callback_data":"Prenota"}],[{"text":"15","callback_data":"Prenota"},{"text":"16","callback_data":"Prenota"},{"text":"17","callback_data":"Prenota"},{"text":"18","callback_data":"Prenota"},{"text":"19","callback_data":"Prenota"},{"text":"20","callback_data":"Prenota"},{"text":"21","callback_data":"Prenota"}],[{"text":"22","callback_data":"Prenota"},{"text":"23","callback_data":"Prenota"},{"text":"24","callback_data":"Prenota"},{"text":"25","callback_data":"Prenota"},{"text":"26","callback_data":"Prenota"},{"text":"27","callback_data":"Prenota"},{"text":"28","callback_data":"Prenota"}],[{"text":"29","callback_data":"Prenota"},{"text":"30","callback_data":"Prenota"},{"text":"31","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"}]]}';    $url = $GLOBALS[completo].'/sendMessage?chat_id='.$utente.'&parse_mod=HTML&text='.$message.$tastiera;
    $url = $GLOBALS[completo].'/sendMessage?chat_id='.$utente.'&parse_mod=HTML&text='.$message.$tastiera;
	file_get_contents($url);*/	
    if($mesecalendario == 11 or $mesecalendario == 9 or	$mesecalendario == 4 or $mesecalendario == 6){
    $tastiera = '&reply_markup={"inline_keyboard":[[{"text":"1","callback_data":"Prenota"},{"text":"2","callback_data":"Prenota"},{"text":"3","callback_data":"Prenota"},{"text":"4","callback_data":"Prenota"},{"text":"5","callback_data":"Prenota"},{"text":"6","callback_data":"Prenota"},{"text":"7","callback_data":"Prenota"}],[{"text":"8","callback_data":"Prenota"},{"text":"9","callback_data":"Prenota"},{"text":"10","callback_data":"Prenota"},{"text":"11","callback_data":"Prenota"},{"text":"12","callback_data":"Prenota"},{"text":"13","callback_data":"Prenota"},{"text":"14","callback_data":"Prenota"}],[{"text":"15","callback_data":"Prenota"},{"text":"16","callback_data":"Prenota"},{"text":"17","callback_data":"Prenota"},{"text":"18","callback_data":"Prenota"},{"text":"19","callback_data":"Prenota"},{"text":"20","callback_data":"Prenota"},{"text":"21","callback_data":"Prenota"}],[{"text":"22","callback_data":"Prenota"},{"text":"23","callback_data":"Prenota"},{"text":"24","callback_data":"Prenota"},{"text":"25","callback_data":"Prenota"},{"text":"26","callback_data":"Prenota"},{"text":"27","callback_data":"Prenota"},{"text":"28","callback_data":"Prenota"}],[{"text":"29","callback_data":"Prenota"},{"text":"30","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"}],[{"text":"<<","callback_data":"prima"},{"text":"esci","callback_data":"esci"},{"text":">>","callback_data":"dopo"}]]}';    $url = $GLOBALS[completo].'/sendMessage?chat_id='.$utente.'&parse_mod=HTML&text='.$message.$tastiera;
    $url = $GLOBALS[completo].'/sendMessage?chat_id='.$utente.'&parse_mod=HTML&text='.$message.$tastiera;
    file_get_contents($url);
    }elseif($mesecalendario == 2){
    $tastiera = '&reply_markup={"inline_keyboard":[[{"text":"1","callback_data":"Prenota"},{"text":"2","callback_data":"Prenota"},{"text":"3","callback_data":"Prenota"},{"text":"4","callback_data":"Prenota"},{"text":"5","callback_data":"Prenota"},{"text":"6","callback_data":"Prenota"},{"text":"7","callback_data":"Prenota"}],[{"text":"8","callback_data":"Prenota"},{"text":"9","callback_data":"Prenota"},{"text":"10","callback_data":"Prenota"},{"text":"11","callback_data":"Prenota"},{"text":"12","callback_data":"Prenota"},{"text":"13","callback_data":"Prenota"},{"text":"14","callback_data":"Prenota"}],[{"text":"15","callback_data":"Prenota"},{"text":"16","callback_data":"Prenota"},{"text":"17","callback_data":"Prenota"},{"text":"18","callback_data":"Prenota"},{"text":"19","callback_data":"Prenota"},{"text":"20","callback_data":"Prenota"},{"text":"21","callback_data":"Prenota"}],[{"text":"22","callback_data":"Prenota"},{"text":"23","callback_data":"Prenota"},{"text":"24","callback_data":"Prenota"},{"text":"25","callback_data":"Prenota"},{"text":"26","callback_data":"Prenota"},{"text":"27","callback_data":"Prenota"},{"text":"28","callback_data":"Prenota"}],[{"text":"29","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"}],[{"text":"<<","callback_data":"prima"},{"text":"esci","callback_data":"esci"},{"text":">>","callback_data":"dopo"}]]}';    $url = $GLOBALS[completo].'/sendMessage?chat_id='.$utente.'&parse_mod=HTML&text='.$message.$tastiera;
  		$url = $GLOBALS[completo].'/sendMessage?chat_id='.$utente.'&parse_mod=HTML&text='.$message.$tastiera;
    	file_get_contents($url);	    
    }else{
    $tastiera = '&reply_markup={"inline_keyboard":[[{"text":"1","callback_data":"Prenota"},{"text":"2","callback_data":"Prenota"},{"text":"3","callback_data":"Prenota"},{"text":"4","callback_data":"Prenota"},{"text":"5","callback_data":"Prenota"},{"text":"6","callback_data":"Prenota"},{"text":"7","callback_data":"Prenota"}],[{"text":"8","callback_data":"Prenota"},{"text":"9","callback_data":"Prenota"},{"text":"10","callback_data":"Prenota"},{"text":"11","callback_data":"Prenota"},{"text":"12","callback_data":"Prenota"},{"text":"13","callback_data":"Prenota"},{"text":"14","callback_data":"Prenota"}],[{"text":"15","callback_data":"Prenota"},{"text":"16","callback_data":"Prenota"},{"text":"17","callback_data":"Prenota"},{"text":"18","callback_data":"Prenota"},{"text":"19","callback_data":"Prenota"},{"text":"20","callback_data":"Prenota"},{"text":"21","callback_data":"Prenota"}],[{"text":"22","callback_data":"Prenota"},{"text":"23","callback_data":"Prenota"},{"text":"24","callback_data":"Prenota"},{"text":"25","callback_data":"Prenota"},{"text":"26","callback_data":"Prenota"},{"text":"27","callback_data":"Prenota"},{"text":"28","callback_data":"Prenota"}],[{"text":"29","callback_data":"Prenota"},{"text":"30","callback_data":"Prenota"},{"text":"31","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"},{"text":" ","callback_data":"Prenota"}],[{"text":"<<","callback_data":"prima"},{"text":"esci","callback_data":"esci"},{"text":">>","callback_data":"dopo"}]]}';    $url = $GLOBALS[completo].'/sendMessage?chat_id='.$utente.'&parse_mod=HTML&text='.$message.$tastiera;
  	$url = $GLOBALS[completo].'/sendMessage?chat_id='.$utente.'&parse_mod=HTML&text='.$message.$tastiera;
    	file_get_contents($url);    
    }	*/    
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
function comandiadmin($utente){
	$messaggio = "cosa vuole fare admin?";
    	$tastiera = '&reply_markup={"keyboard":[["crea evento"],["assemblea"],["manda notifica"],["esci"]]}';
	$url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
	file_get_contents($url);
	
	
}
//data
function getdataoggi($datamessaggio){
  $datazioneunix = gmdate("d.m.y", $datamessaggio);
  return $datazioneunix;
}
function deleteMessage($utente, $message_id){
	$url = $GLOBALS[completo]."/deleteMessage?chat_id=".$utente."&$message_id=".urlencode($message_id);
	file_get_contents($url);
}
function inserireneldatabase($utente,$dataoggi){
	$db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");
	$query = "INSERT INTO prenotazioni (nome, quando, ora) VALUES ('$utente', '099','$dataoggi')";
	$result = pg_query($query);
}
function prendidaldatabase($utente){
	$db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");
	$result = pg_query($db,"SELECT nome, quando, ora FROM prenotazioni  "); //WHERE quando = '099'
	
	while($row=pg_fetch_assoc($result)){
		$msg=$row['nome'].$row['quando'].$row['ora'] ;
		$url = $GLOBALS[completo]."/sendMessage?chat_id=".$utente."&text=".urlencode($msg);
		file_get_contents($url);	
	
	}
}

function deleterow(){
		$db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");
		$query = "DELETE FROM prenotazioni ";
		$result = pg_query($query);
	
}	
//header("Content-Type: application/json");
//$msg="vuoi fare altro?"; 
//$parameters = array('chat_id' => $utente, "text" => $msg);
//$parameters["method"] = "sendMessage";
//echo json_encode($parameters)
