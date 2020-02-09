<?php
//beecky

// webhook token e update che ci permettono di interagire con il bot
$web ="https://api.telegram.org/bot";
$token="872839539:AAGgmCXaX9zdSypFKiR4BHxoVK3U-riq3ao";
$completo="https://api.telegram.org/bot".$token;
$updates=file_get_contents("php://input");
$update=json_decode($updates, true);

if(!$update){
  exit;
}
//msg
$messaggio=$update['message'];
$message_id=$update['message']['message_id'];
$testo=$messaggio['text'];
//utente
$utente=$messaggio['chat']['id'];
$nomeutente=$messaggio['chat']['first_name'];
$username=$messaggio['from']['username'];

//query msg
$query = $update['callback_query'];
$queryid = $query['id'];
$queryUserId = $query['from']['id'];
$queryusername = $query['from']['username'];
$querydata = $query['data'];
$querymsgid = $query['message']['message_id'];
$querymsg = $query['message'];
//data
$datazioneunix=$messaggio['date'];
$dataoggi = getdataoggi($datazioneunix);
$mese=date("n");
$anno=date("Y");

$GLOBALS['utenterfl']=null;


function letturedatabase($query){
  $db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");

  $result = pg_query($db, $query );


	while($row=pg_fetch_assoc($result)){

			  $table[]=$row ;
	}
  return $table;
  }


$comando= explode(' ', $testo);
switch($comando[0]){
  case '/start':
    $msg = "Benevenuto sono Beecky assistente di frequenza libera";
				    mandamessaggiutente($utente, $username);


            mandamessaggiutente($utente, $msg);
            $tabella= letturedatabase("SELECT COUNT(*) FROM utenti WHERE utente='$username'");
		if($tabella[0]['count']){

		      mandamessaggiutente($utente, "Benvenuto amico mio ");
			    $tabula=letturedatabase("SELECT * FROM utenti WHERE utente='$username'");
			    $GLOBALS['utenterfl']= $tabula[0];
			    mandamessaggiutente($utente, $GLOBALS['utenterfl']['nomevero']);
  		 if(empty($GLOBALS['utenterfl']['password'])){
  				  mandamessaggiutente($utente, " dammi una passwpord inserendo /password latuapassword");
  				  exit;
  		}

		}else {
		  // code...
		  	mandamessaggiutente($utente, "vai via stronzo ");
			  exit;
		}
	
      tastierastart($utente);

    break;
  case '/password':
      $msg=$comando[1];
			$tabula=letturedatabase("UPDATE utenti SET password= '$msg'  WHERE utente = '$username'");
    	mandamessaggiutente($utente,"ok il prezzo è giusto" );

    break;
  case '1admin':
    $msg="Salve Admin";
    mandamessaggiutente($utente, $msg);

    comandiadmin($utente);
    break;
  case 'prenota':
    // code...
    $msg="prenotiamo lo studio";
    mandamessaggiutente($utente, $msg);
    tastieracalendario($utente,$dataoggi,$mese);
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
    tastierastart($utente);
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
// metodo per mandare un msg al canale
function mandamessaggicanale($msg)
{
  $utente = "@santacaterina2";
  $url = $GLOBALS[completo]."/sendMessage?chat_id=".$utente."&text=".urlencode($msg);
  file_get_contents($url);
}
//start comandi
function tastierastart($utente){
	mandamessaggiutente($utente, $GLOBALS['utenterfl']['livello']);
	$messaggio = "osserva la tastiera e usa i suoi comandi";

	if($GLOBALS['utenterfl']['livello']== 1){
		 $tastiera = '&reply_markup={"keyboard":[["calendario eventi"],["prenotazioni studio"],["Prendo una birra"],["Hey"]]}';

	}else if($GLOBALS['utenterfl']['livello']== 2 ){
		 $tastiera = '&reply_markup={"keyboard":[["prenota studio"],["crea prossimo evento"],["prenotazioni studio"],["calendario eventi"],["Prendo una birra"],["rifornimento di birra"],["new tesserato"],["Hey"]]}';

	}else {
		sendmessage($utente, "Non sei tesserato, entra a far parte di Radio Frequenza Libera, vieni a trovarci in studio!");
	 	exit;
	}
    	$url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
    	file_get_contents($url);
}
