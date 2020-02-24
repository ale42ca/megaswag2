<?php
//beecky

// webhook token e update che ci permettono di interagire con il bot
$web ="https://api.telegram.org/bot";
$token="872839539:AAGgmCXaX9zdSypFKiR4BHxoVK3U-riq3ao";
$completo="https://api.telegram.org/bot".$token;
$updates=file_get_contents("php://input");
$update=json_decode($updates, true);

if(!$update){
  exit();
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
/*
$query = $update['callback_query'];
$queryid = $query['id'];
$queryUserId = $query['from']['id'];
$queryusername = $query['from']['username'];
$querydata = $query['data'];
$querymsgid = $query['message']['message_id'];
$querymsg = $query['message'];
*/
//data
$datazioneunix=$messaggio['date'];
$dataoggi = getdataoggi($datazioneunix);
$giorno=date("j");
$mese=date("n");
$anno=date("Y");
//utente lvl
$GLOBALS['utenterfl']=null;
//cerca lvl
try {
  $tabula=letturedatabase("SELECT * FROM utenti WHERE utente='$username'");
	$GLOBALS['utenterfl']= $tabula[0];
} catch (Exception $e) {
    $e="ok";
    $comando[0]='/start';
}


//
$tarta=$testo;
$coma= explode(' ', $tarta);
$testo=strtolower($testo);
$comando= explode(' ', $testo);
// database funzioni leggi e scrivi
function letturedatabase($query){
  $db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");
  $result = pg_query($db, $query );
	while($row=pg_fetch_assoc($result)){
			  $table[]=$row ;
	}
  return $table;
  }
  function inserireneldatabase($query){
  	$db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");

  	$result = pg_query($db,$query);
  }

////data
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
//switch case
switch($comando[0]){
  case '/start':
	mandamessaggiutente($utente, "Benevenuto sono Beecky assistente di frequenza libera");
  $tabella= letturedatabase("SELECT COUNT(*) FROM utenti WHERE utente='$username'");
  if($tabella[0]['count']){
      mandamessaggiutente($utente, "Benvenuto amico mio ");
			$tabula=letturedatabase("SELECT * FROM utenti WHERE utente='$username'");
			$GLOBALS['utenterfl']= $tabula[0];
			mandamessaggiutente($utente, $GLOBALS['utenterfl']['nomevero']);
      if(empty($GLOBALS['utenterfl']['password'])){
          mandamessaggiutente($utente, " dammi una password inserendo /password latuapassword");
          exit();
      }
	}else{
		  mandamessaggiutente($utente, "la password non è giusta ");
			exit();
	}
  if($GLOBALS['utenterfl']['livello']<1){
      exit();
  }else {
    tastierastart($utente);
    }
    break;
  case '/password':
  $msg=$comando[1];
	$tabula=letturedatabase("UPDATE utenti SET password= '$msg'  WHERE utente = '$username'");
        mandamessaggiutente($utente,"ok la password è aggiornata" );

	inserireneldatabase("UPDATE utenti SET livello= '1'  WHERE password = 'quack'");
	inserireneldatabase("UPDATE utenti SET livello= '2'  WHERE password = 'frequenza'");
	inserireneldatabase("UPDATE utenti SET livello= '3'  WHERE password = 'lib.era'");
	tastierastart($utente);
    break;
  case 'prenota':

    if($GLOBALS['utenterfl']['livello']<2){
 	 mandamessaggiutente($utente,"non hai i permessi" );
	 exit();
    }
    $prenotazione=$comando[1];
    $dataprenotazione= explode('.', $prenotazione);
    $meseprenotato=$dataprenotazione[1];
    $giornoprenotato=$dataprenotazione[0];
    if($meseprenotato== null or $meseprenotato== null){
	    mandamessaggiutente($utente, "prenotiamo lo studio. Per farlo digita:");
	    mandamessaggiutente($utente, "prenota giornoscelto.mesescelto");
	    exit();
    }

    if($meseprenotato> 12 or $meseprenotato<0){
      mandamessaggiutente($utente, "hai inserito un mese sbagliato");
      exit();
    }elseif ($giornoprenotato < 0 or $giornoprenotato>31 ) {
      mandamessaggiutente($utente, "hai inserito un giorno sbagliato");
      exit();
    }if($meseprenotato== 2 and $giornoprenotato > 29){
      mandamessaggiutente($utente, " 30 giorni ha novembre aprile maggio giugno e settembre di 28 c'è ne è uno tutti gli altri sono di 31");
      exit();
    }if(($meseprenotato== 11 or $meseprenotato== 4 or $meseprenotato== 5 or $meseprenotato== 6 or $meseprenotato== 9) and $giornoprenotato>30){
      mandamessaggiutente($utente, " 30 giorni ha novembre aprile maggio giugno e settembre di 28 c'è ne è uno tutti gli altri sono di 31");
      exit();
    }elseif ($meseprenotato<$mese  and $mese!='12' ) {
      mandamessaggiutente($utente, "Vuoi prenotare nel passato... il che non è molto fico");
      exit();
    }else {
      // confronto nel database della data
      $tabrutta= letturedatabase("SELECT * FROM prenotazioni WHERE giorno='$giornoprenotato' AND mese = '$meseprenotato' ");
	      if(!empty($tabrutta)){
	      $personachehaprenotato=$tabrutta[0]['utente'];
	      $msg="purtroppo lo studio è stato già prenotato da ".$personachehaprenotato;
	      mandamessaggiutente($utente, $msg);
	      exit();
	    }else {
	      mandamessaggiutente($utente, "Perfetto aggiorno le informazioni! ti ricordo che se devi eliminare la tua ultima prenotazione devi digitare cancella prenotazione");
	      inserireneldatabase("INSERT INTO prenotazioni ( utente, giorno, mese) VALUES ('$username', '$giornoprenotato', $meseprenotato)");
	      mandamessaggiutente($utente, "avvisiamo sul canale ");
	      $msg="studio prenotato da ".$nomeutente."per il giorno ".$giornoprenotato."/".$meseprenotato;
	      mandamessaggicanale($msg);
	    }
}

    break;
  case 'evento':
      // code...
    if($GLOBALS['utenterfl']['livello']<2){
 	 mandamessaggiutente($utente,"non hai i permessi" );
	 exit();
    }
    $msg="creiamo insieme il prossimo evento scrivi il tuo messaggio e invialo poi scrivi evento e la data";
    mandamessaggiutente($utente, $msg);
    $evento=$comando[1];
    $newevento= explode('.', $evento);
    $meseprenotato=$newevento[2];
    $giornoprenotato=$newevento[1];
    $cosaevento=$newevento[0];
    if($meseprenotato== null or $meseprenotato== null){
	    $msg="creiamo evento. Per farlo digita:";
	    mandamessaggiutente($utente, $msg);
	    $msg="prenota giornoscelto.mesescelto";
	    mandamessaggiutente($utente, $msg);
	    exit();
    }
	if($cosaevento==null){
		$msg="non hai specificato l' evento.";
	    mandamessaggiutente($utente, $msg);
	}

    if($meseprenotato> 12 or $meseprenotato<0){

      $msg="hai inserito un mese sbagliato";
      mandamessaggiutente($utente, $msg);
      exit();

    }elseif ($giornoprenotato < 0 or $giornoprenotato>31 ) {
        $msg="hai inserito un giorno sbagliato";
        mandamessaggiutente($utente, $msg);
        exit();
    }if($meseprenotato== 2 and $giornoprenotato > 29){

        $msg=" 30 giorni ha novembre aprile maggio giugno e settembre di 28 c'è ne è uno tutti gli altri sono di 31";
        mandamessaggiutente($utente, $msg);
        exit();
    }if(($meseprenotato== 11 or $meseprenotato== 4 or $meseprenotato== 5 or $meseprenotato== 6 or $meseprenotato== 9) and $giornoprenotato>30){
        $msg=" 30 giorni ha novembre aprile maggio giugno e settembre di 28 c'è ne è uno tutti gli altri sono di 31";
        mandamessaggiutente($utente, $msg);
        exit();
    }elseif ($meseprenotato<$mese  and $mese!='12' ) {
        $msg="Vuoi tornare nel passato... servirebbe una macchina del tempo";
        mandamessaggiutente($utente, $msg);
        exit();
    }else  {
        $msg="Perfetto aggiorno le informazioni! ti ricordo che se devi eliminare la tua ultima prenotazione devi digitare canc evento";
        mandamessaggiutente($utente, $msg);
        inserireneldatabase("INSERT INTO evento ( utente, giorno, mese, evento) VALUES ('$username', '$giornoprenotato', '$meseprenotato', '$cosaevento')");
        $msg="avvisiamo sul canale ";
        mandamessaggiutente($utente, $msg);
        $msg="nuovo evento:".$cosaevento."per il giorno".$giornoprenotato."/".$meseprenotato;
        mandamessaggicanale($msg);
      }
    break;

  case 'canc':
    // code...
    if($GLOBALS['utenterfl']['livello']<1){
 	 mandamessaggiutente($utente,"non hai i permessi" );
	 exit();
    }
    tastieracanc($utente);

    $cancella=$comando[1];
    if($cancella== null){
      exit();
    }
    if($cancella=="evento"){
      //cancella ultimo evento

      inserireneldatabase("DELETE FROM evento WHERE ir in ( SELECT ir FROM evento ORDER BY ir desc LIMIT 1 ) ");
      $msg=" ultimo evento cancellato";
      mandamessaggiutente($utente, $msg);
    }elseif ($cancella=="prenotazione") {

      inserireneldatabase("DELETE FROM prenotazioni WHERE utente='$username' AND ir in ( SELECT ir FROM prenotazioni ORDER BY ir desc LIMIT 1 ) ");
      $msg=" ultima tua prenotazione cancellata";
      mandamessaggiutente($utente, $msg);
    }

    break;
  case 'calendario':
    // code...
    if($GLOBALS['utenterfl']['livello']<1){
 	 mandamessaggiutente($utente,"non hai i permessi" );
	 exit();
    }
    tastieracalendario($utente);
    $calendario=$comando[1];
	if($calendario==eventi){
      $tabrutta= letturedatabase("SELECT utente, giorno, mese FROM eventi WHERE ir in ( SELECT ir FROM eventi ORDER BY ir desc LIMIT 10 )");
      $int=count($tabrutta);
	    if($int<1){
		 mandamessaggiutente($utente,"non ci sono eventi" );
		 exit();
	    }
      mandamessaggiutente($utente, "ecco a te le ultime 5 eventi");
      for ($i=0; $i<5 ; $i++) {
        $msg=$tabrutta[$i]["utente"]." il giorno".$tabrutta[$i]["giorno"]."/".$tabrutta[$i]["mese"];
        mandamessaggiutente($utente,$msg);

      }
      exit();
  }else if($calendario==prenotazioni){


      $tabrutta= letturedatabase("SELECT utente, giorno, mese FROM prenotazioni WHERE ir in ( SELECT ir FROM prenotazioni ORDER BY ir desc LIMIT 10 )");
      $int=count($tabrutta);
	    if($int<1){
		 mandamessaggiutente($utente,"non ci sono prenotazioni" );
		 exit();
	    }
      mandamessaggiutente($utente, "ecco a te le ultime 5 prenotazioni dello studio");
      for ($i=0; $i<5 ; $i++) {
        $msg=$tabrutta[$i]["utente"]." il giorno".$tabrutta[$i]["giorno"]."/".$tabrutta[$i]["mese"];
        mandamessaggiutente($utente,$msg);

      }
      exit();
	}

    break;
  case 'birra':
    // code...
    if($GLOBALS['utenterfl']['livello']<1){
 	 mandamessaggiutente($utente,"non hai i permessi" );
	 exit();
    }
	tastierabirre($utente);
	$birra=$comando[1];
	$tabirra= letturedatabase("SELECT birre FROM birra WHERE ir in ( SELECT ir FROM birra ORDER BY ir desc LIMIT 1 )");
	$msgbirra=$tabirra[0]["birre"];

	if($birra=="tot"){

	    mandamessaggiutente($utente, "le birre totali ".$msgbirra);
	    exit();

	}

	if($birra>0 or is_numeric($birra)){
	  mandamessaggiutente($utente, "ti ricordo che puoi indicare che se hai preso + birre puoi indicare quante ne hai prese");
	  $nbirra=$msgbirra+$birra;
	  inserireneldatabase("INSERT INTO birra (birre, utente, data) VALUES ( '$nbirra', '$username', '$dataoggi')");
	  exit();
	}
	if($birra<0){
	  mandamessaggiutente($utente, "Hai preso ".$birre." birra");
	  $nbirra=$msgbirra+$birra;
	  inserireneldatabase("INSERT INTO birra (birre, utente, data) VALUES ( '$nbirra', '$username', '$dataoggi')");
	  exit();

	}

    break;
  case 'new':
      // code...
    if($GLOBALS['utenterfl']['livello']<2){
 	 mandamessaggiutente($utente,"non hai i permessi" );
	 exit();
    }
    $msg="inseriamo new tesserato: indicami utenteidtelegram.nomevero.livello,";
    mandamessaggiutente($utente, $msg);
    $tesserato=$coma[1];
    $newtesserato= explode('.', $tesserato);
    ////////////////////////

    $nometesserato=$newtesserato[0];
    $livellotesserato=$newtesserato[2];
    $nomevero=$newtesserato[1];
    if($tesserato== null){
	    mandamessaggiutente($utente, "specifica le caratteristiche tesserato");
	    exit();
    }
    inserireneldatabase("INSERT INTO utenti ( utente, nomevero, livello, giorno, mese, anno) VALUES ('$nometesserato','$nomevero', '$livellotesserato', '$giorno', '$mese','$anno')");

    break;
  case 'esci':
    tastierastart($utente);

    break;
}
if($comando[0]=="aiuto"){
	tastieraaiuto($utente);
	if($comando[1]=="prenotazione"){
		mandamessaggiutente($utente, "Per prenotare scrivi sulla tastiera: (prenota).(giorno).(mese)");
}
	if($comando[1]=="calendario"){
		mandamessaggiutente($utente, "il calendario ti permette di vedere ultime  prenotazioni");
}
	if($comando[1]=="eventi"){
		mandamessaggiutente($utente, "Per creare un evento scrivi sulla tastiera: (evento) (nome dell' evento).(giorno).(mese)");
}
	if($comando[1]=="birra"){
		mandamessaggiutente($utente, "scrivi (birra)(+/-)(numero birre) per aggiungere/togliere birre");
}


}
if($comando[0]=="raccontami qualcosa"){
  $tabirra= letturedatabase("SELECT frasi FROM frasi ORDER BY RANDOM() LIMIT 1");
  $msg=$tabirra[0]["frasi"];
  mandamessaggiutente($utente, $msg);
}

function tastierastart($utente){
    $messaggio = "osserva la tastiera e usa i suoi comandi";
    if($GLOBALS['utenterfl']['livello']<3){
        $tastiera = '&reply_markup={"keyboard":[["calendario"],["birra"],["aiuto"]]}';

    }else if($GLOBALS['utenterfl']['livello']<4){
        $tastiera = '&reply_markup={"keyboard":[["prenota"],["evento"],["calendario"],["birra"],["canc"],["aiuto"]]}';
    }
	$url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
    	file_get_contents($url);
}
function tastieraaiuto($utente){
    $messaggio = "Ti aiuto io";
    $tastiera = '&reply_markup={"keyboard":[["raccontami qualcosa"],["aiuto prenotazione"],["aiuto calendario"],["aiuto eventi"],["aiuto birra"],["esci"]]}';
    $url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
    file_get_contents($url);

}
function tastieracanc($utente){
    $messaggio = "cancelliamo la tua ultima prenotazione";
    $tastiera = '&reply_markup={"keyboard":[["canc evento"],["canc prenotazione"],["esci"]]}';
    $url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
    file_get_contents($url);

}

function tastieracalendario($utente){
    $messaggio = "calendario";
    $tastiera = '&reply_markup={"keyboard":[["calendario eventi"],["calendario prenotazioni"],["esci"]]}';
    $url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
    file_get_contents($url);

}

function tastierabirre($utente){
    $messaggio = "birra";
    $tastiera = '&reply_markup={"keyboard":[["birra tot"],["esci"]]}';
    $url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
    file_get_contents($url);
}
