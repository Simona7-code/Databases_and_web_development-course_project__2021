<?php
//questo file contiene le funzioni che controllano i due campi password, il nickname, la presenza di caratteri di spaziatura o il vuoto dei campi, la mail, il numero di telefono e il documento di identità. Contiene inoltre la funzione save_image (riga 155 per spiegazione).
$resmex;

// funzione che controlla se il valore passato in input (proposta di nickname)sia disponibile o no e se sia valido
function validate_nik_server($nickn_){
	global $resmex;
	global $conn;
	//se la stringa passata ha meno di 4 caratteri oppure più di 11, restiuisce false e assegna a resmex il messaggio di errore che verrà stampato
	if ((iconv_strlen($nickn_)<4)||(iconv_strlen($nickn_)>11)){
		$resmex = "Il nickname può essere conposto da un massimo di 10 caratteri e un minimo di 4!";
		return false;
	}
	//altrimenti
	else {
		//la query seleziona le righe che hanno il nickname passato in input
		$checknick = $conn->prepare("SELECT Nickn FROM registrato WHERE Nickn=?");
		$checknick->bind_param('s',$nickn_);
	    $checknick->execute();
	    $result = $checknick->get_result();
		$checknick->close();
		//conta i risultati della query
		$rowcount = $result-> num_rows;
		//se esiste una riga nel risultato restituisce false e assegna a resmex il messaggio di errore che verrà stampato
		if($rowcount == 1){
	    	$resmex="Spiacente,questo nickname è già in uso, ti preghiamo di sceglierne un altro";
	    	return false;
	    } 
	}// altrimenti restituisce true
	return true;
}

//funzione che passa il valore inserito in input per il nickname alla funzione precedente e permette le azioni determinate dal valore di ritorno (t,f) della precedente funzione (per il bottone di pagina SignUp.php).
function validate_nik(){
	global $resmex;
	$nickn= $_POST['nik'];
	if (!validate_nik_server($nickn)){

		$ris = array("response" => $resmex);
		echo json_encode($ris);
	}
	else {$ris = array("response" => "OK");
		echo json_encode($ris);
	}
}

//funzione che cerca nei valori passati come argomento  caratteri di spaziatura o valori indesiderati. Se li contiene restituisce true, altrimenti false
function Space($string){
	if (preg_match("/\s/", $string)) { //se matcha un carattere di spaziatura
		return true;
	}	
	else return false;
}

//funzione che sanifica la mail passata in input, controlla la leggittimità del formato e controlla se sul database è già presente.Se la proposta è valida restiuisco true.
function checkemail($email_){
global $conn;
global $resmex;
$hashed;
// filter_var con filter_sanitize email rimuove tutti i caratteri illegali da una mail. Assegno questo valore alla variabile
$email = filter_var($email_, FILTER_SANITIZE_EMAIL); 
	//validate email è necessaria per controllare che il formato della mail inserita sia valido. Se non è valido assegno a resmex il  messaggio di errore che sarà stampato e restituisce false
 	if (filter_var($email_, FILTER_VALIDATE_EMAIL)==false){ 
 		$resmex="Siamo spiacenti ma l'email fornita non è valida, provare a reinserirla correttamente o inserirne un'altra.";
 	 	return false; 
 	 	} 
 	//altrimenti cerco la mail passata in input nel database e se il risulato del conto delle righe è uguale a 1 assegno a resmex il  messaggio di errore che sarà stampato e restituisce false
 	else {

		$checkemail = $conn->prepare("SELECT Email FROM registrato WHERE Email=?");
		$checkemail->bind_param('s',$email_);
	    $checkemail->execute();
	    $result = $checkemail->get_result();
		$checkemail->close();
		$rowcount = $result-> num_rows;
		if($rowcount == 1){
			$resmex="Spiacente,questa email è già in uso, ti preghiamo di sceglierne un altro o accedere al tuo account!";
			return false;
 		}
 	}
 	//restituisce true
 	return true;	
}

//funzione che controlla la legittimità e adeguatezza della password; inoltre controlla se l'input passato a "reinserisci la password" corrisponde a quello passato in password. Se la proposta è valida restituisco true.
function psw_check($psw_,$pswcheck_){
	global $resmex;
	//se la password proposta è più corta di 6 caratteri o più lunga di 11
    if ((iconv_strlen($psw_) < 6) || (iconv_strlen($psw_) > 11)) { 
    	//assegno a resmex il  messaggio di errore che sarà stampato e restituisce false
	    $resmex = "La password deve contenere un minimo di 6 caratteri e un massimo di 10.";
	    return false;
	}
	//altrimenti se non matcha almeno una cifra
	else if (!preg_match("/\d/", $psw_)) { 
	    $resmex = " La password deve contenere almeno una cifra";
	    //assegno a resmex il  messaggio di errore che sarà stampato e restituisce false
	    return false;
	}
	//altrimenti se non matcha almeno una lettera maiusola nell'intervallo alfabetico A-Z
	else if (!preg_match("/[A-Z]/", $psw_)) { 
		//assegno a resmex il  messaggio di errore che sarà stampato e restituisce false
	    $resmex= "La password deve contenere almeno una lettera maiuscola";
	    return false;
	}
	//altrimenti se non matcha almeno una lettera maiuscola nell'intervallo alfabetico a-z
	else if (!preg_match("/[a-z]/", $psw_)) { 
		//assegno a resmex il  messaggio di errore che sarà stampato e restituisce false
	    $resmex= "La password deve contenere almeno una lettera minuscola";
	    return false;
	}
	//altrimenti se la password inserita e quella reinserita non coincidono
	else if ($psw_!= $pswcheck_){ 
		//assegno a resmex il  messaggio di errore che sarà stampato e restituisce false
		$resmex= "Le due password inserite non combaciano, riprova!";
		return false;
	}
	//altrimenti restituisco true
	else return true;
}

//controllo validità numero di telefono. Se il numero è valido restituisco true
function validate_mobile($telefono){	
	global $conn;
	global $resmex;
	//se è un numero di telefono non è valido (accetto solo numeri di linea mobile) (nella relazione la spiegazione della reg. exp.)
    if (preg_match('/^(([+]|00)39)?((3[1-6][0-9]))(\d{7})$/', $telefono)==false){
    	//assegno a resmex il  messaggio di errore che sarà stampato e restituisce false
    	$resmex="Numero di telefono non valido,riprovare. Sono accettati solo numeri di linea mobile.";
    	return false;
    }
    //altrimenti controllo se esiste già una corrispondenze nel db per quel numero. 
    else {
		$checknum = $conn->prepare("SELECT Tel FROM registrato WHERE Tel=?");
		$checknum->bind_param('i',$telefono);
	    $checknum->execute();
	    $uniquenum = $checknum->get_result();
		$checknum->close();
		//conto le righe di risultati
		$rowcount = $uniquenum-> num_rows;
		//Se la conta delle righe corrisponde a 1
		if($rowcount == 1){
			//assegno a resmex il  messaggio di errore che sarà stampato e restituisce false
			$resmex="Spiacente,questo numero di telefono cellulare è già in uso, ti preghiamo di sceglierne un altro o accedere al tuo account!";
			return false;
 		}
 	}
 	//restituisce true
    return true;
}

//controllo la validità dei formati accettati del numero di documento di identità (passaporto, patente italiana , carta d'identità italiana) (spiegazione reg. exp. nella relazione). Se è valido restituisce true, false altrimenti
function valid_num_docu($documento){
	if ((preg_match("/^\b[A-Z]{2}\d{5}[A-Z]{2}\b$/", $documento))||(preg_match("/^\b[A-Z]{2}\d{7}\b$/", $documento)) || (preg_match("/^\bU1[A-Z0-9]{7}[A-Z]\b$/", $documento))){
		return true;
	}
	return false;
}

//controllo che il documento passato in input sia diverso da tutti quelli già inseriti nel db.
function unique_docum($documento){
	global $conn;
	//query che seleziona la riga in cui dentro la colonna DocuIde compare quello passato in input
	$checkdoc = $conn->prepare("SELECT DocuIde FROM registrato WHERE DocuIde=?");
	$checkdoc->bind_param('s',$documento);
    $checkdoc->execute();
    $resultd = $checkdoc->get_result();
    //conta le righe del risulato
	$countd = $resultd-> num_rows;
	//se esiste già una riga restituisce false
	if($countd == 1){
		return false;
	} 
	//altrimenti restituise true
	return true;
}

//funzione che controlla le immagini inserite nei post, le carica nella cartella upload se l'immagine è valida e restituisce la costruzione del futuro path dell'immagine.
function save_image($image){	
	//se all'interno dell'array superglobale files viene passato l'argomento della funzione (l'immagine)
	if(isset($_FILES[$image])){	
		//salvo all'interno della variabile target_dir la stringa "upload/", che è la cartella in cui verranno salvate le immagini
		$target_dir = "upload/";
		//salvo all'interno della variabile target_file la variabile precedentemente assegnata, concatenandola al risulato della funzione php basename (che va a prendere il valore del "name"(una delle chiavi dell'array superglobale $_files) dell'immagine corrente(dell'argomento passato alla funzione) che gli viene uploadata)
		$target_file = $target_dir . basename($_FILES[$image]["name"]);
		//salvo all'interno della variabile imageFileType  l'estensione del file immagine passato (tramite funzione pathinfo che permette di estrarre specifiche informazioni dal path), di cui prendo l'ultima estensione nel caso ce ne fossero di più (grazie a PATHINFO_EXTENSION)in lettere minuscole (nel caso ci fossero lettere maiuscole(grazie a strtolower))
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		//riassegno alla variabile target_file il valore di $target_dir (riga 9),concatenato al valore del Unix Timestamp corrente al momento (con microsecondi, cosi che le immagini caricate nello stesso secondo non si sovrascrivano (perchè avrebbero lo stesso name)) , concatenato a un punto, concatenato all'estensione ricavata a riga 13
		$target_file = $target_dir . microtime(true) .".". $imageFileType;
		//controllo che mi sia stata passata una reale immagine e non un immagine "falsa" controllando il valore del campo size all'interno dell'array files per la presunta immagine passata come argomento; l'immagine la vado a prendere dal campo di $_files "tmp_name", che contiene il path assoulto dell immagine (che è temporanamente salvata nella cartella di file temporanei di xampp). Se il campo size non avrà valore vuol dire che non è stata passata una reale immagine ma un file che la emula.
		$check = getimagesize($_FILES[$image]["tmp_name"]);
		//se getimagesize restituisce valore false (ovvero non ha un size concreto)
		if($check == false) {	
			//la funzione restituisce null	    
			return null;
		}
		//move_upload_file è una funzione php che sposta un file caricato (primo argomento) nella destinazione target_file, ovvero all'url precedentemente costruita a riga 15, all'interno della cartella upload con un nuovo nome costrutio tramite il microtime. Questa funzione rstituisce false se il file passato non è valido oppure se per qualche motivo il trasferimento non avviene; quindi...(riga 26)
		if (!move_uploaded_file($_FILES[$image]["tmp_name"], $target_file)) {	
			//...la funzione save_image restituisce null
			return null;
		}
		//ritarda l'esecuzione del programma di 1 microsecondo (aiuta a non far sovrascrivere le immagini) dato che il loro nome viene assegnato dal microtime (riga 15)
		sleep(0.1);
		//restituisce il path dell'immagine appena caricata nella cartella
		return $target_file;
	}
	//altrimenti restituisci null
	return null;
}
?>