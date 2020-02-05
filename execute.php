<?php
//beecky

// webhook token e update che ci permettono di interagire con il bot
$web="https://api.telegram.org/bot";
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
    $msg="Benevenuto sono Beecky assistente di frequenza libera";
    mandamessaggiutente($utente, $msg);

    break;
  case '1admin':
  // code...
    $msg="Salve Admin";
    mandamessaggiutente($utente, $msg);
    comandiadmin();
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
    break;

}

//data
function getdataoggi($datamessaggio){
  $datazioneunix = gmdate("d.m.y", $datamessaggio);
  return $datazioneunix;
}

// metodo per mandare $messaggio
public function mandamessaggiutente($utente, $msg)
{
  $url = $GLOBALS[completo]."/sendMessage?chat_id=".$utente."&text=".urlencode($msg);
	file_get_contents($url);
}
// metodo per mandare un msg al canale
public function mandamessaggicanale($msg)
{
  $utente = "@santacaterina2";
  $url = $GLOBALS[completo]."/sendMessage?chat_id=".$utente."&text=".urlencode($msg);
  file_get_contents($url);
}

//admin comandi
public function comandiadmin($utente)
{
  // code...
  $messaggio = "cosa vuoi fare?";
  $tastiera = '&reply_markup={"keyboard":[["prossimo evento"],["rifornimento di birra"],["new tesserato"],["esci"]]}';
	$url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
	file_get_contents($url);
}
/*
if($testo=="prossimo evento"){
  $msg="settare la prossima assemblea";
  mandamessaggiutente($utente, $msg);

}else if($testo=="rifornimento di birra"){
  $msg = "quanta birra abbiamo";
  mandamessaggiutente($utente, $msg);
  $qualedatabase=2;
  inserireneldatabase($utente, $dataoggi, $ora, $qualedatabase);
}else if($testo=="new tesserato fan"){
  $msg= "aggiungi nuovo fan";
  mandamessaggiutente($utente, $msg);
  $qualedatabase=2;
  inserireneldatabase($utente, $dataoggi, $ora, $qualedatabase);
}


// database prenotazioni avrà ir, utente, data, orario
// database birre avrà ir, utente, aggiungi birre
// database fan ir utente


// i comandi necessari a utilizzare il database sono inserisci nel database, cancella ultima row,

//insericsci nel database
function inserireneldatabase($utente, $dataoggi, $ora, $qualedatabase){

	$db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");
	if ($qualedatabase==1){
		$query = "INSERT INTO prenotazioni ( nome, quando, ora) VALUES ('$utente', '$dataoggi', $ora)";

	}($qualedatabase==2){
		$query = "INSERT INTO birre (nome, quando) VALUES ('$utente', '$dataoggi')";

	}($qualedatabase==3){
		$query = "INSERT INTO fan (nome ,quando) VALUES ('$utente', '$dataoggi')";

	}

	$result = pg_query($query);
}
// cancella last $row

function deletelastrow($utente, $dataoggi,$qualedatabase){
  $db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");

	if ($qualedatabase==1){
		$query = "DELETE FROM prenotazioni WHERE ir in ( SELECT ir FROM users ORDER BY ir desc LIMIT 1 ) ";


	}($qualedatabase==2){
		$query = "DELETE FROM birre WHERE ir in ( SELECT ir FROM users ORDER BY ir desc LIMIT 1 ) ";

	}($qualedatabase==3){
		$query = "DELETE FROM fan WHERE ir in ( SELECT ir FROM users ORDER BY ir desc LIMIT 1 ) ";

	}
	$result = pg_query($query);
}
// prendi dal database
function prendidaldatabase($utente,$cosa){
	$db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");
  if ($qualedatabase==1){
    $result = pg_query($db,"SELECT utente, data, ora FROM prenotazioni WHERE ir in ( SELECT ir FROM users ORDER BY ir desc LIMIT 5 ) ";


  }($qualedatabase==2){
    $result = pg_query($db,"SELECT  numero FROM birre ";

  }($qualedatabase==3){
    $result = pg_query($db,"SELECT nome, quando FROM ";

  }

	while($row=pg_fetch_assoc($result)){
    if ($qualedatabase==1){
      $msg="lo studio è stato prenotato da".$row['utente']."il giorno".$row['data']."per quest'ora".$row['ora'] ;
    }($qualedatabase==2){
      $msg="Birre presenti ".$row['numero'] ;
    }($qualedatabase==3){
      $msg=k78 4$row['utente']."tessarato il giorno".$row['data'] ;
    }
		$url = $GLOBALS[completo]."/sendMessage?chat_id=".$utente."&text=".urlencode($msg);
		file_get_contents($url);

	}
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
if($querydata<31){
  $data=$querydata;
  inserireneldatabase();
}
*/
