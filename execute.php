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
	$messaggio = "osserva la tastiera e usa i suoi comandi";
    	$tastiera = '&reply_markup={"keyboard":[["prenota"],["calendario"],["prenotazioni"],["hey"]]}';
    	$url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
    	file_get_contents($url);
}
//admin comandi
function comandiadmin($utente)
{
  // code...
  	$messaggio = "cosa vuoi fare?";
  	$tastiera = '&reply_markup={"keyboard":[["prossimo evento"],["rifornimento di birra"],["new tesserato"],["esci"]]}';
	$url = "$GLOBALS[completo]"."/sendMessage?chat_id=".$utente."&parse_mode=HTML&text=".$messaggio.$tastiera;
	file_get_contents($url);
}/*
if($testo=="prossimo evento"){
  $msg="settare la prossima assemblea";
  mandamessaggiutente($utente, $msg);
}else if($testo=="rifornimento di birra"){
  $msg = "quanta birra abbiamo";
  mandamessaggiutente($utente, $msg);
  $qualedatabase="2";
  inserireneldatabase($utente, $dataoggi, $ora, $qualedatabase);
}else if($testo=="new tesserato fan"){
  $msg= "aggiungi nuovo fan";
  mandamessaggiutente($utente, $msg);
  $qualedatabase="3";
  inserireneldatabase($utente, $dataoggi, $ora, $qualedatabase);
}
// database prenotazioni avrà ir, utente, data, orario
// database birre avrà ir, utente, aggiungi birre
// database fan ir utente
// i comandi necessari a utilizzare il database sono inserisci nel database, cancella ultima row,
//insericsci nel database
function inserireneldatabase($utente, $dataoggi, $ora, $qualedatabase){
	$db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");
	if ($qualedatabase == "1"){
		$query = "INSERT INTO prenotazioni ( nome, quando, ora) VALUES ('$utente', '$dataoggi', $ora)";
	}($qualedatabase == "2"){
		$query = "INSERT INTO birre (nome, quando) VALUES ('$utente', '$dataoggi')";
	}($qualedatabase == "3"){
		$query = "INSERT INTO fan (nome ,quando) VALUES ('$utente', '$dataoggi')";
	}
	$result = pg_query($query);
}
// cancella last $row
function deletelastrow($utente, $dataoggi,$qualedatabase){
  $db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");
	if ($qualedatabase== "1"){
		$query = "DELETE FROM prenotazioni WHERE ir in ( SELECT ir FROM users ORDER BY ir desc LIMIT 1 ) ";
	}($qualedatabase== "2"){
		$query = "DELETE FROM birre WHERE ir in ( SELECT ir FROM users ORDER BY ir desc LIMIT 1 ) ";
	}($qualedatabase== "3"){
		$query = "DELETE FROM fan WHERE ir in ( SELECT ir FROM users ORDER BY ir desc LIMIT 1 ) ";
	}
	$result = pg_query($query);
}
// prendi dal database
function prendidaldatabase($utente,$cosa){
	$db =pg_connect("host= ec2-54-247-96-169.eu-west-1.compute.amazonaws.com port=5432 dbname=d2hsht934ovhs9 user=maghsyclqxkpyw password=50ac10525450c60de9157e57e0ab6432f320f5ef3d8ee1650818e491644f51bc");
  if ($qualedatabase== "1"){
    $result = pg_query($db,"SELECT utente, data, ora FROM prenotazioni WHERE ir in ( SELECT ir FROM users ORDER BY ir desc LIMIT 5 ) ";
  }($qualedatabase== "2"){
    $result = pg_query($db,"SELECT  numero FROM birre ";
  }($qualedatabase== "3"){
    $result = pg_query($db,"SELECT nome, quando FROM fan";
  }
	while($row=pg_fetch_assoc($result)){
			    if ($qualedatabase== "1"){
			      $msg="lo studio è stato prenotato da".$row['utente']."il giorno".$row['data']."per quest'ora".$row['ora'] ;
			    }($qualedatabase== "2"){
			      $msg="Birre presenti ".$row['numero'] ;
			    }($qualedatabase== "3"){
			      $msg=$row['utente']."tessarato il giorno".$row['data'] ;
			    }
		$url = $GLOBALS[completo]."/sendMessage?chat_id=".$utente."&text=".urlencode($msg);
		file_get_contents($url);
	}
}
*/
function tastieracalendario($utente,$dataoggi,$mese){
    $message = $dataoggi;
    $mesecalendario=$mese;

		    if($mesecalendario == "11" or $mesecalendario == "9" or $mesecalendario == "4" or $mesecalendario == "6"){
   				$tastiera = '&reply_markup={"inline_keyboard":[[{"text":"1","callback_data":"1"},{"text":"2","callback_data":"2"},{"text":"3","callback_data":"3"},{"text":"4","callback_data":"4"},{"text":"5","callback_data":"5"},{"text":"6","callback_data":"6"},{"text":"7","callback_data":"7"}],[{"text":"8","callback_data":"8"},{"text":"9","callback_data":"9"},{"text":"10","callback_data":"10"},{"text":"11","callback_data":"11"},{"text":"12","callback_data":"12"},{"text":"13","callback_data":"13"},{"text":"14","callback_data":"14"}],[{"text":"15","callback_data":"15"},{"text":"16","callback_data":"16"},{"text":"17","callback_data":"17"},{"text":"18","callback_data":"18"},{"text":"19","callback_data":"19"},{"text":"20","callback_data":"20"},{"text":"21","callback_data":"21"}],[{"text":"22","callback_data":"22"},{"text":"23","callback_data":"23"},{"text":"24","callback_data":"24"},{"text":"25","callback_data":"25"},{"text":"26","callback_data":"26"},{"text":"27","callback_data":"27"},{"text":"28","callback_data":"28"}],[{"text":"29","callback_data":"29"},{"text":"30","callback_data":"30"},{"text":"31","callback_data":"31"},{"text":" ","callback_data":" "},{"text":" ","callback_data":" "},{"text":" ","callback_data":" "},{"text":" ","callback_data":" "}],[{"text":"<<","callback_data":"prima"},{"text":"esci","callback_data":"esci"},{"text":">>","callback_data":"dopo"}]] , "force_reply": true, "selective": true}';
    			}elseif($mesecalendario == "2"){
   				$tastiera = '&reply_markup={"inline_keyboard":[[{"text":"1","callback_data":"1"},{"text":"2","callback_data":"2"},{"text":"3","callback_data":"3"},{"text":"4","callback_data":"4"},{"text":"5","callback_data":"5"},{"text":"6","callback_data":"6"},{"text":"7","callback_data":"7"}],[{"text":"8","callback_data":"8"},{"text":"9","callback_data":"9"},{"text":"10","callback_data":"10"},{"text":"11","callback_data":"11"},{"text":"12","callback_data":"12"},{"text":"13","callback_data":"13"},{"text":"14","callback_data":"14"}],[{"text":"15","callback_data":"15"},{"text":"16","callback_data":"16"},{"text":"17","callback_data":"17"},{"text":"18","callback_data":"18"},{"text":"19","callback_data":"19"},{"text":"20","callback_data":"20"},{"text":"21","callback_data":"21"}],[{"text":"22","callback_data":"22"},{"text":"23","callback_data":"23"},{"text":"24","callback_data":"24"},{"text":"25","callback_data":"25"},{"text":"26","callback_data":"26"},{"text":"27","callback_data":"27"},{"text":"28","callback_data":"28"}],[{"text":"29","callback_data":"29"},{"text":" ","callback_data":" "},{"text":" ","callback_data":" "},{"text":" ","callback_data":" "},{"text":" ","callback_data":" "},{"text":" ","callback_data":" "},{"text":" ","callback_data":" "}],[{"text":"<<","callback_data":"prima"},{"text":"esci","callback_data":"esci"},{"text":">>","callback_data":"dopo"}]] , "force_reply": true, "selective": true}';
   			}else{
   				$tastiera = '&reply_markup={"inline_keyboard":[[{"text":"1","callback_data":"1"},{"text":"2","callback_data":"2"},{"text":"3","callback_data":"3"},{"text":"4","callback_data":"4"},{"text":"5","callback_data":"5"},{"text":"6","callback_data":"6"},{"text":"7","callback_data":"7"}],[{"text":"8","callback_data":"8"},{"text":"9","callback_data":"9"},{"text":"10","callback_data":"10"},{"text":"11","callback_data":"11"},{"text":"12","callback_data":"12"},{"text":"13","callback_data":"13"},{"text":"14","callback_data":"14"}],[{"text":"15","callback_data":"15"},{"text":"16","callback_data":"16"},{"text":"17","callback_data":"17"},{"text":"18","callback_data":"18"},{"text":"19","callback_data":"19"},{"text":"20","callback_data":"20"},{"text":"21","callback_data":"21"}],[{"text":"22","callback_data":"22"},{"text":"23","callback_data":"23"},{"text":"24","callback_data":"24"},{"text":"25","callback_data":"25"},{"text":"26","callback_data":"26"},{"text":"27","callback_data":"27"},{"text":"28","callback_data":"28"}],[{"text":"29","callback_data":"29"},{"text":"30","callback_data":"30"},{"text":" ","callback_data":" "},{"text":" ","callback_data":" "},{"text":" ","callback_data":" "},{"text":" ","callback_data":" "},{"text":" ","callback_data":" "}],[{"text":"<<","callback_data":"prima"},{"text":"esci","callback_data":"esci"},{"text":">>","callback_data":"dopo"}]] , "force_reply": true, "selective": true}';
    			}
    $url = $GLOBALS[completo].'/sendMessage?chat_id='.$utente.'&parse_mod=HTML&text='.$message.$tastiera;
    file_get_contents($url);
}
if($query < "32"){
  $data=$querydata.".".$mese.".".$anno;
  $ora="12";
  $qualedatabase="1";
  //inserireneldatabase($utente, $data, $ora, $qualedatabase);
}elseif ($query == "prima") {
      // code...
      $mese= $mese - "1";
      if($mese>"0"){
        tastieracalendario($utente,$dataoggi,$mese);
      }else {
        // code...
        $mese="1";
      }
}elseif ($query== "dopo") {
    // code...
    $mese = $mese + "1";
    if($mese<"13"){
      tastieracalendario($utente,$dataoggi,$mese);
    }else {
      // code...
      $mese="1";
      $anno=$anno + "1";
    }
}
if ($query== "esci") {
    tastierastart($utente);
}
