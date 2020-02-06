<?php
//beecky

// webhook token e update che ci permettono di interagire con il bot
$web ="https://api.telegram.org/bot";
$token="872839539:AAGgmCXaX9zdSypFKiR4BHxoVK3U-riq3ao";
$completo="https://api.telegram.org/bot".$token;
$updates=file_get_contents("php://input");
$update=json_decode($updates, true);

$updates = json_decode(file_get_contents(set_get_updates_parameters("https://api.telegram.org/bot872839539:AAGgmCXaX9zdSypFKiR4BHxoVK3U-riq3ao/getUpdates?offset=100")), true);


//utente
$utente=$messaggio['chat']['id'];
$utente=$messaggio['chat']['id'];
$nomeutente=$messaggio['chat']['first_name'];
//msg
$messaggio=$update['message'];
$message_id=$update['message']['message_id'];
$testo=$messaggio['text'];
//query msg
$query = $update['callback_query'];
$queryid = $query['id'];
$queryUserId = $query['from']['id'];
$queryusername = $query['from']['username'];
$querydata = $query['data'];
$querymsgid = $query['message']['message_id'];
//data
$datazioneunix=$messaggio['date'];
$dataoggi = getdataoggi($datazioneunix);


switch($testo){
  case '/start':
    // code...
    $msg = "Benevenuto sono Beecky assistente di frequenza libera";
    mandamessaggiutente($utente, $msg);

    break;
  case '1admin':
  // code...
    $msg="Salve Admin";
    mandamessaggiutente($utente, $msg);

    break;
  case 'prenota':
    // code...
    $msg="prenotiamo lo studio";
    mandamessaggiutente($utente, $msg);
    break;
  case 'calendario':
      // code...
    $msg="Calendario";
    mandamessaggiutente($utente, $msg);
    break;
  case 'birre':
    // code...
    $msg="";
    mandamessaggiutente($utente, $msg);
    break;
  case 'prenotazioni':
    // code...
    $msg="prenotazione radio";
    mandamessaggiutente($utente, $msg);
    break;
  case 'hey':
    // code...
    $msg="hey ecco a te una barzeletta";
    mandamessaggiutente($utente, $msg);
    break;
  case 'esci':
    // qui mettere tastiera start
    $msg="hey ecco a te una barzeletta";
    mandamessaggiutente($utente, $msg);
    break;

}

//data
function getdataoggi($datamessaggio){
  $datazioneunix = gmdate("d.m.y", $datamessaggio);
  return $datazioneunix;
}

// metodo per mandare $messaggio
function mandamessaggiutente($utente, $msg)
{
  $url = $GLOBALS[completo]."/sendMessage?chat_id=".$utente."&text=".urlencode($msg);
  file_get_contents($url);
}
