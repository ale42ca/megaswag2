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
$mese = date('n');
$nomeutente=$messaggio['chat']['first_name'];
  $query = $update['callback_query'];
  $queryid = $query['id'];
  $queryUserId = $query['from']['id'];
  $queryusername = $query['from']['username'];
  $querydata = $query['data'];
  $querymsgid = $query['message']['message_id'];
if ($testo == "/start"){
	$ms = "Ciao sono Beecky assistente virtuale di radio frequenza libera. Cosa posso fare per te?";
	sendMessage($utente, $ms);	
        tastierastart($utente);	
        
}	
function tastierastart($utente){
	$messaggio = "osserva la tastiera e usa i suoi comandi";
    	$tastiera = '&reply_markup={"keyboard":[["prenota"],["calendario"],["vedi prenotazioni"],["data"]]}';
    	$url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
    	file_get_contents($url);

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
	$query = "INSERT INTO prenotazioni (nome, quando, ora) VALUES ('$utente', '08','$dataoggi')";
	$result = pg_query($query);
}
function prendidaldatabase($utente){
	$db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");
	$result = pg_query($db,"SELECT nome, quando, ora FROM prenotazioni");
	
	while($row=pg_fetch_assoc($result)){
		$msg=$row['nome'].$row['quando'].$row['ora'] ;
		$url = $GLOBALS[completo]."/sendMessage?chat_id=".$utente."&text=".urlencode($msg);
		file_get_contents($url);	
	
	}
}		
//header("Content-Type: application/json");
//$msg="vuoi fare altro?"; 
//$parameters = array('chat_id' => $utente, "text" => $msg);
//$parameters["method"] = "sendMessage";
//echo json_encode($parameters)
