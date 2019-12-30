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

 public function execute()
 {
     $message = $this->getMessage();
     $chat = $message->getChat();
     $user = $message->getFrom();
     $text = trim($message->getText(true));
     $chat_id = $chat->getId();
     $user_id = $user->getId();
     //Preparing Response
     $data = ['chat_id' => $chat_id];
     if ($chat->isGroupChat() || $chat->isSuperGroup()) {
         //reply to message id is applied by default
         //Force reply is applied by default so it can work with privacy on
         $data['reply_markup'] = Keyboard::forceReply(['selective' => true]);
     }
     //Conversation start
     $this->conversation = new Conversation($user_id, $chat_id, $this->getName());
     $notes =& $this->conversation->notes;
     !is_array($notes) && ($notes = []);
     //cache data from the tracking session if any
     $state = 0;
     if (isset($notes['state'])) {
         $state = $notes['state'];
     }
     $result = Request::emptyResponse();
     //State machine
     //Entrypoint of the machine state if given by the track
     //Every time a step is achieved the track is updated
     switch ($state) {
         case 0:
             if ($text === '') {
                 $notes['state'] = 0;
                 $this->conversation->update();
                 $data['text'] = 'Type your name:';
                 $data['reply_markup'] = Keyboard::remove(['selective' => true]);
                 $result = Request::sendMessage($data);
                 break;
             }
             $notes['name'] = $text;
             $text = '';
             // no break
         // no break
         case 1:
             if ($text === '') {
                 $notes['state'] = 1;
                 $this->conversation->update();
                 $data['text'] = 'Type your surname:';
                 $result = Request::sendMessage($data);
                 break;
             }
             $notes['surname'] = $text;
             $text = '';
             // no break
         // no break
         case 2:
             if ($text === '' || !is_numeric($text)) {
                 $notes['state'] = 2;
                 $this->conversation->update();
                 $data['text'] = 'Type your age:';
                 if ($text !== '') {
                     $data['text'] = 'Type your age, must be a number:';
                 }
                 $result = Request::sendMessage($data);
                 break;
             }
             $notes['age'] = $text;
             $text = '';
             // no break
         // no break
         case 3:
             if ($text === '' || !in_array($text, ['M', 'F'], true)) {
                 $notes['state'] = 3;
                 $this->conversation->update();
                 $data['reply_markup'] = (new Keyboard(['M', 'F']))->setResizeKeyboard(true)->setOneTimeKeyboard(true)->setSelective(true);
                 $data['text'] = 'Select your gender:';
                 if ($text !== '') {
                     $data['text'] = 'Select your gender, choose a keyboard option:';
                 }
                 $result = Request::sendMessage($data);
                 break;
             }
             $notes['gender'] = $text;
             // no break
         // no break
         case 4:
             if ($message->getLocation() === null) {
                 $notes['state'] = 4;
                 $this->conversation->update();
                 $data['reply_markup'] = (new Keyboard((new KeyboardButton('Share Location'))->setRequestLocation(true)))->setOneTimeKeyboard(true)->setResizeKeyboard(true)->setSelective(true);
                 $data['text'] = 'Share your location:';
                 $result = Request::sendMessage($data);
                 break;
             }
             $notes['longitude'] = $message->getLocation()->getLongitude();
             $notes['latitude'] = $message->getLocation()->getLatitude();
             // no break
         // no break
         case 5:
             if ($message->getPhoto() === null) {
                 $notes['state'] = 5;
                 $this->conversation->update();
                 $data['text'] = 'Insert your picture:';
                 $result = Request::sendMessage($data);
                 break;
             }
             /** @var PhotoSize $photo */
             $photo = $message->getPhoto()[0];
             $notes['photo_id'] = $photo->getFileId();
             // no break
         // no break
         case 6:
             if ($message->getContact() === null) {
                 $notes['state'] = 6;
                 $this->conversation->update();
                 $data['reply_markup'] = (new Keyboard((new KeyboardButton('Share Contact'))->setRequestContact(true)))->setOneTimeKeyboard(true)->setResizeKeyboard(true)->setSelective(true);
                 $data['text'] = 'Share your contact information:';
                 $result = Request::sendMessage($data);
                 break;
             }
             $notes['phone_number'] = $message->getContact()->getPhoneNumber();
             // no break
         // no break
         case 7:
             $this->conversation->update();
             $out_text = '/Survey result:' . PHP_EOL;
             unset($notes['state']);
             foreach ($notes as $k => $v) {
                 $out_text .= PHP_EOL . ucfirst($k) . ': ' . $v;
             }
             $data['photo'] = $notes['photo_id'];
             $data['reply_markup'] = Keyboard::remove(['selective' => true]);
             $data['caption'] = $out_text;
             $this->conversation->stop();
             $result = Request::sendPhoto($data);
             break;
     }
     return $result;
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
//header("Content-Type: application/json");
//$msg="vuoi fare altro?"; 
//$parameters = array('chat_id' => $utente, "text" => $msg);
//$parameters["method"] = "sendMessage";
//echo json_encode($parameters);
