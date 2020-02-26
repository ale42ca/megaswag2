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
$option=['CI SONO', 'NON CI SONO'];
$option= json_encode($option);
function sendpool($msg, $option){
  $utente = "@santacaterina2";
  $url = $GLOBALS[completo]."/sendPoll?chat_id=".$utente."&question=".$msg."&options=".$option;
  file_get_contents($url);
}

function poolorario($msg){
  $option=['1:00 p.m.','2:00 p.m.', '3:00 p.m.','4:00 p.m.', '5:00 p.m.','4:00 p.m.', '5:00 p.m.','6:00 p.m.', '7:00 p.m.','8:00 p.m.'];	
  $option= json_encode($option);
  $utente = "@santacaterina2";
  $url = $GLOBALS[completo]."/sendPoll?chat_id=".$utente."&question=".$msg."&options=".$option;
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
  if($GLOBALS['utenterfl']['livello']<1 or $GLOBALS['utenterfl']['livello']==null){
      exit();
  }else {
    tastierastart($utente);
    }
    break;
  case '/password':
  $msg=$comando[1];
	$tabula=letturedatabase("UPDATE utenti SET password= '$msg'  WHERE utente = '$username'");
        mandamessaggiutente($utente,"ok la password è aggiornata" );

	inserireneldatabase("UPDATE utenti SET livello= '1'  WHERE password = 'frequenza'");
	inserireneldatabase("UPDATE utenti SET livello= '2'  WHERE password = 'lib.era'");
	inserireneldatabase("UPDATE utenti SET livello= '3'  WHERE password = '1admin'");
	$tabula=letturedatabase("SELECT * FROM utenti WHERE utente='$username'");
	$GLOBALS['utenterfl']= $tabula[0];
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
	    mandamessaggiutente($utente, "Prenotiamo lo studio, per farlo digita:");
	    mandamessaggiutente($utente, "prenota (giorno scelto).(mese scelto)");
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
	      mandamessaggiutente($utente, "Perfetto aggiorno le informazioni! ti ricordo che se devi eliminare la tua ultima prenotazione usa funzione canc");
	      inserireneldatabase("INSERT INTO prenotazioni ( utente, giorno, mese) VALUES ('$username', '$giornoprenotato', $meseprenotato)");
	      mandamessaggiutente($utente, "avvisiamo sul canale ");
	      $msg="studio prenotato da ".$nomeutente." per il giorno ".$giornoprenotato."/".$meseprenotato;
	      mandamessaggicanale($msg);
	    }
}

    break;
  case 'evento':
      // code...
    if($GLOBALS['utenterfl']['livello']<2){
 	 mandamessaggiutente($utente,"non hai i permessi");
	 exit();
    }
    $msg="Creiamo evento";
    mandamessaggiutente($utente, $msg);
    $evento=$comando[1];
    $newevento= explode('.', $evento);
    $meseprenotato=$newevento[2];
    $giornoprenotato=$newevento[1];
    $cosaevento=$newevento[0];
    if($meseprenotato== null or $meseprenotato== null){
	    mandamessaggiutente($utente, "per farlo digita:");
	    mandamessaggiutente($utente, "evento (nome evento).(giorno scelto).(mese scelto)");
	    exit();
    }
	if($cosaevento==null){
		$msg="non hai specificato l'evento.";
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
	     $tabrutta= letturedatabase("SELECT * FROM eventi WHERE giorno='$giornoprenotato' AND mese = '$meseprenotato' ");
	      if(!empty($tabrutta)){
	      $personachehaprenotato=$tabrutta[0]['utente'];
	      $msg="è stato già creato un evento da ".$personachehaprenotato." per quel giorno";
	      mandamessaggiutente($utente, $msg);
	      exit();
	    }else {
		$msg="Perfetto aggiorno le informazioni! ti ricordo che se devi eliminare la tua ultima prenotazione usa la funzione canc";
		mandamessaggiutente($utente, $msg);
		inserireneldatabase("INSERT INTO eventi ( utente, giorno, mese, evento) VALUES ('$username', '$giornoprenotato', '$meseprenotato', '$cosaevento')");
		$msg="avvisiamo sul canale";
		mandamessaggiutente($utente, $msg);
		$msg="nuovo evento: ".$cosaevento." per il giorno ".$giornoprenotato."/".$meseprenotato;
		mandamessaggicanale($msg);
		sendpool($msg, $option);
		tastieraevento($utente);      
	    }    
		
      }
    break;

  case 'canc':
    // code...
    if($GLOBALS['utenterfl']['livello']<2){
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

      inserireneldatabase("DELETE FROM eventi WHERE ir in ( SELECT ir FROM eventi ORDER BY ir desc LIMIT 1 ) ");
      $tabrutta= letturedatabase("SELECT ir FROM eventi  ");
      $int=count($tabrutta);
	    if($int<1){
		 mandamessaggiutente($utente,"non ci sono più eventi");
		 exit();
	    }
      $msg="ultimo evento cancellato";
      mandamessaggiutente($utente, $msg);
    }elseif ($cancella=="prenotazione") {

      inserireneldatabase("DELETE FROM prenotazioni WHERE utente='$username' AND ir in ( SELECT ir FROM prenotazioni ORDER BY ir desc LIMIT 1 ) ");
      $tabrutta= letturedatabase("SELECT ir FROM prenotazioni WHERE utente='$username' ");
      $int=count($tabrutta);
	    if($int<1){
		 mandamessaggiutente($utente,"non ci sono più prenotazioni");
		 exit();
	    }
      $msg="ultima tua prenotazione cancellata";
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
		 mandamessaggiutente($utente,"non ci sono eventi");
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
		 mandamessaggiutente($utente,"non ci sono prenotazioni");
		 exit();
	    }
     	 mandamessaggiutente($utente, "ecco a te le ultime 5 prenotazioni dello studio");
	      for ($i=0; $i<5 ; $i++) {
		$msg=$tabrutta[$i]["utente"]." il giorno".$tabrutta[$i]["giorno"]."/".$tabrutta[$i]["mese"];
		mandamessaggiutente($utente,$msg);
	      }
      		exit();
  }else if($calendario==tue){
	      $tabrutta= letturedatabase("SELECT utente, giorno, mese FROM prenotazioni WHERE utente='$username' AND ir in ( SELECT ir FROM prenotazioni ORDER BY ir desc LIMIT 10 )");
      	      $int=count($tabrutta);
	    if($int<1){
		 mandamessaggiutente($utente,"non ci sono prenotazioni");
		 exit();
	    }
     	 	mandamessaggiutente($utente, "ecco a te le ultime tue 5 prenotazioni dello studio");
	      for ($i=0; $i<5 ; $i++) {
		$msg=$tabrutta[$i]["utente"]." il giorno".$tabrutta[$i]["giorno"]."/".$tabrutta[$i]["mese"];
		mandamessaggiutente($utente,$msg);
	      }


	}

    break;
  case 'birra':
    // code...
    if($GLOBALS['utenterfl']['livello']<1){
 	 mandamessaggiutente($utente,"non hai i permessi");
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
	  mandamessaggiutente($utente, "Digita: birra +/-(numero birre)");
	  $nbirra=$msgbirra+$birra;
	  inserireneldatabase("INSERT INTO birra (birre, utente, data) VALUES ( '$nbirra', '$username', '$dataoggi')");
	  exit();
	}
	if($birra<0){
	  mandamessaggiutente($utente, "birre diminuite");
	  $nbirra=$msgbirra+$birra;
	  inserireneldatabase("INSERT INTO birra (birre, utente, data) VALUES ( '$nbirra', '$username', '$dataoggi')");
	  exit();

	}

    break;
  case 'new':
      // code...
    if($GLOBALS['utenterfl']['livello']<3){
 	 mandamessaggiutente($utente,"non hai i permessi");
	 exit();
    }
    $msg="inseriamo new tesserato: indicami utenteidtelegram.nomevero.livello";
    mandamessaggiutente($utente, $msg);
    $tesserato=$coma[1];
    $newtesserato= explode('.', $tesserato);
    ////////////////////////

    $nometesserato=$newtesserato[0];
    $livellotesserato=$newtesserato[2];
    $nomevero=$newtesserato[1];
    if($tesserato== null){
	    mandamessaggiutente($utente, "specifica meglio le caratteristiche tesserato");
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
		mandamessaggiutente($utente, "Per prenotare scrivi sulla tastiera: (prenota)( )(giorno).(mese)");
}
	if($comando[1]=="calendario"){
		mandamessaggiutente($utente, "il calendario ti permette di vedere ultime 5 prenotazioni o eventi, controlla prenotazioni anche sul canale");
}
	if($comando[1]=="eventi"){
		mandamessaggiutente($utente, "Per creare un evento scrivi sulla tastiera: (evento)( )(nome dell'evento).(giorno).(mese)");
}
	if($comando[1]=="birra"){
		mandamessaggiutente($utente, "scrivi (birra)( )(+/-)(numero birre) per aggiungere/togliere birre");
}


}

if($comando[0]=="orario"){
      $tabrutta= letturedatabase("SELECT * FROM eventi ");
     
	if(empty($tabrutta)){
		 mandamessaggiutente($utente,"Errore non ci sono eventi");
		 exit();
	    }
  poolorario("orario evento?");
  tastierastart($utente);	
}



if($comando[0]=="raccontami"){
  $tabirra= letturedatabase("SELECT frasi FROM frasi ORDER BY RANDOM() LIMIT 1");
  $msg=$tabirra[0]["frasi"];
  mandamessaggiutente($utente, $msg);
}


if($comando[0]=="tesserati"){
  $tabrutta= letturedatabase("SELECT nomevero, livello, giorno, mese, anno FROM utenti");
  $int=count($tabrutta);
	      for ($i=0; $i<$int; $i++) {
		      if($tabrutta[$i]["anno"]==null){
		      	$tabrutta[$i]["anno"]=="2020";
		      }
		$msg=$tabrutta[$i]["nomevero"]." di lvl ".$tabrutta[$i]["livello"]." tesserato il ".$tabrutta[$i]["giorno"]."/".$tabrutta[$i]["mese"]."/".$tabrutta[$i]["anno"];
		mandamessaggiutente($utente,$msg);
	      }
}

function tastierastart($utente){
    $messaggio = "osserva la tastiera e usa i suoi comandi";
    if($GLOBALS['utenterfl']['livello']==1){
        $tastiera = '&reply_markup={"keyboard":[["calendario"],["birra"],["aiuto"]]}';

    }else if($GLOBALS['utenterfl']['livello']>1){
        $tastiera = '&reply_markup={"keyboard":[["prenota"],["evento"],["calendario"],["birra"],["canc"],["aiuto"],["tesserati"]]}';
    }
	$url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
    	file_get_contents($url);
}

function tastieraevento($utente){
    $messaggio = "chiedo per che ora organizzarsi?";
    $tastiera = '&reply_markup={"keyboard":[["orario evento?"],["esci"]]}';
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
    if($GLOBALS['utenterfl']['livello']==1){
    $tastiera = '&reply_markup={"keyboard":[["calendario eventi"],["calendario prenotazioni"],["esci"]]}';

    }else if($GLOBALS['utenterfl']['livello']>1){
    $tastiera = '&reply_markup={"keyboard":[["calendario eventi"],["calendario prenotazioni"],["calendario tue prenotazioni"],["esci"]]}';
    }
    $url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
    file_get_contents($url);

}

function tastierabirre($utente){
    $messaggio = "birra";
    $tastiera = '&reply_markup={"keyboard":[["birra tot"],["esci"]]}';
    $url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
    file_get_contents($url);
}
