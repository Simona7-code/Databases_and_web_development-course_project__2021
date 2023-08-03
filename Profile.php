<?php
//includo la connessione (più altre cose contenute in include) e le funzioni utili su questa pagina
include'include.php';
include 'funzioni.php';
//se non è settata una funzione tramite post richiamo la funzione index (riga 38)
if(!isset($_POST["function"])){
	index();
}
//altrimenti richiamo la funzione passata tramite post
else{
	$function = $_POST["function"];

	switch ($function) {	

		case "delete_user":
			delete_user();
			break;	
		case "modify_data":
			modify_data();
			break;
		case "modify_pass":
			modify_pass();
			break;
		case "create_blog":
			create_blog();
			break;
		case "get_sottoargomento":
			get_sottoargomento();
			break;
		default:
			$ris = array("response" => "Metodo non valido $function");
			echo json_encode($ris);
			break;
	}
}
//funzione che "assembla" la pagina
function index(){
	$page_title = "Profile";
	$body = "views/ProfileView.php";
	include ("Page.php");
}

//funzione per creare un blog
function create_blog(){
	global $conn;
	$newIdB;
	//ricavo i dati dalla richiesta ajax
	$titolo = $_POST["title"];
	$tema = $_POST['tema'];
	$sfondo = $_POST['sfondo'];
	$font = $_POST['font'];
	//i sottotemi sono opzionali, quindi uso if isset
	if (isset($_POST['sottotemi'])){
		$sottotemi=$_POST['sottotemi'];
	}
	//rimuovo eventuali backslash
	$titolo= stripslashes($titolo);
    $IdUs= $_SESSION['IdU'];
    //controllo che nessuno degli input abbia valori riconducibili a false(o sia vuoto); se anche solo uno lo è, assegno a res la stringa di errore
	if (empty($titolo) || empty($sfondo) || empty($font)|| empty($tema)){
		$res = "Errore, tutti i campi (tranne sottoargomenti, in quanto opzionale) devono essere compilati";
	}
	//controllo lunghezza titolo, se la condizione è vera assegno a res la stringa di errore
	else if (iconv_strlen($titolo)>100){
		$res = "Il titolo può essere conposto da un massimo di 100 caratteri!";
	}
	//se i controlli vanno a buon fine
	else{
		//query che crea riga dentro tabella blog e inserisce il nuovo blog
		$create_blog = $conn->prepare("INSERT INTO blog (Titolo, IdU, IdSf, IdFo) VALUES(?,?,?,?)");
		$create_blog->bind_param('ssss',$titolo, $IdUs, $sfondo, $font);
	    $create_blog->execute();
	    //ricavo id del blog appena inserito e lo assegno alla variabile dichiarata a riga 46
	    $newIdB= $conn->insert_id;
		$create_blog->close();
		//se sono settati dei sottotemi, per ognuno di loro viene fatta una query d'inserimento
		if (isset($sottotemi)){
			foreach ($sottotemi as $value){
			$insert_tem= $conn->prepare("INSERT INTO `argblog`(`IdT`,`IdSt`,`IdB`) VALUES (?,?,?)");
			$insert_tem->bind_param('iii',$tema, $value, $newIdB);
		    $insert_tem->execute();
			$res= "OK";
			}
		}
		//se non sono stati settati dei sottotemi per il blog appena creato
		else {
			//viene inserita una singola riga che indica l'argomento scelto (obbligatorio) per il blog appena creato
			$insert_arg= $conn->prepare("INSERT INTO `argblog`(`IdT`,`IdB`) VALUES (?,?)");
			$insert_arg->bind_param('ii',$tema, $newIdB);
		    $insert_arg->execute();
			$res= "OK";
		}
	}
	//associo alla chiave response (dell'array ris) il valore di res che gli sarà stato assegnato in base all errore o alla riuscita dell'operazione e associo alla chiave idBlog l'id del blog appena creato se è stato creato, altrimenti se è entrato in una casistica di errore precedente alle query d'inseriemnto, viene assegnato null.
	$ris = array("response" => $res,
		"idBlog" => isset($newIdB) ? $newIdB : null);
	//rappresento l'array ris come un JSON
	echo json_encode($ris);
}

//funzione prende argomento scelto e restituisce la costruzione html del selectpicker relativo all'argomento selezionato
function get_sottoargomento(){
	global $conn; 
	$html = "";
	//ricavo i dati dalla richiesta ajax
	$mainarg= $_POST['argomento'];
	//query che ricava i sottoargomenti di uno specifico argomento
	$take_sott = $conn->prepare("SELECT `IdSt`, `sottoargomento` FROM `sottotema` WHERE `IdTema`=?");
    $take_sott->bind_param('i', $mainarg);
    $take_sott->execute();
    $result_sott = $take_sott->get_result();
	//tramite concatenazione di stringhe, assegnazione dopo assegnazione, genero la select(html)per i sottoargomenti
    $html = "<select class='selectpicker' id='sottargomento' name='sottargomento' multiple>";
    $html .= "<option value='' disabled selected>Seleziona uno o più sottoargomenti</option>";
    //per ogni sottoargomento creo una riga di option value come si vede a riga 120, una dopo l'altro saranno concatenati alla variabile html
    while ($row = $result_sott->fetch_assoc()) 
    {
      $sott = $row;
      $IdSt = $sott['IdSt'];
      $sottoargomento = $sott['sottoargomento'];
      $html .= "<option value='$IdSt'>$sottoargomento</option>";
    } 
    $html .= "</select>";
    //assegno a res il valore che indica la buona riuscita dell'operazione
    $res="OK";
    //associo alla chiave "response" (dell'array ris) il valore di res che gli sarà stato assegnato in base all errore o alla riuscita dell'operazione e associo alla chiave "html" la costruzione dell'html appena generata,
	$ris = array("response" => $res,
				"html"=>$html);
	//rappresento l'array ris come un JSON
	echo json_encode($ris);
}

//FUNZIONE PER MODIFICARE I DATI PERSONALI DELL'UTENTE
function modify_data(){
	global $conn;
	global $resmex;
	$nocheckneed=0;
	//variabile res inizializzata a null
	$res=null;
	//ricavo il dato dall'array session
	$IdUs= $_SESSION['IdU'];
	//ricavo i dati dalla richiesta ajax
	$nickn= $_POST['nickn'];
	$name=$_POST['name'];
	$lastn=$_POST['lastn'];
	$age=$_POST['age'];
	$gender=$_POST['gender'];
	$tel=$_POST['tel'];
	$docum=$_POST['docum'];
	//rimuovo eventuali backslash
	$nickn= stripslashes($nickn);
	$name=stripslashes($name);
	$lastn=stripslashes($lastn);
	$age=stripslashes($age);
	$tel= stripslashes($tel);
	$docum= stripslashes($docum);
	//prendo i dati dell'utente in sessione
	$take_data = $conn->prepare("SELECT * FROM registrato WHERE IdU=?");
    $take_data->bind_param('i', $IdUs);
    $take_data->execute();
    $result_data = $take_data->get_result();
    //assegno i dati estratti alla variabile user
    while ($row = $result_data->fetch_assoc()) {
      $user = $row;
    }
 	//controllo che alcuni campi non contengano caratteri di spaziatura all'interno della stringa; se anche solo uno lo ha, assegno a res la stringa di errore
	if ((Space($nickn)) || (Space($age))){
			$res= "Attento, hai digitato un carattere di spaziatura, rimuovilo e riprova";
	}
	//se il valore del nickname non corrisponde a quello presente nel db
	if ($nickn!= $user['Nickn']){
		//incremento la variabile contatore
		++$nocheckneed;
		//se il nuovo input è vuoto o riconducibile a valori false, assegno a res la stringa di errore
		if (empty($nickn)){
			$res= "Devi compilare tutti i campi";
		}
		//controllo che il nickname scelto sia disponibile e valido (contenuta in funzioni.php);se non lo è assegno a res la stringa di errore relativa all'errore specifico (definiti nella funzione, che si trova in funzioni.php)
		if (!validate_nik_server($nickn)){
			$res=$resmex;
		}	
	}
	//se il valore del nome non corrisponde a quello presente nel db
	if ($name!= $user['Nome']){
		//incremento la variabile contatore
		++$nocheckneed;
		//se il nuovo input è vuoto o riconducibile a valori false, assegno a res la stringa di errore
		if (empty($name)){
			$res= "Devi compilare tutti i campi";
		}
		//controllo lunghezza nome, se anche solo una delle due condizioni è vera assegno a res la stringa di errore
		if ((iconv_strlen($name)<3)||(iconv_strlen($name)>30)){
			$res = "Il nome può essere conposto da un massimo di 30 caratteri e un minimo di 3!"; 
		}	
	}
	//se il valore del cognome non corrisponde a quello presente nel db
	if ($lastn!= $user['Cognome']){
		//incremento la variabile contatore
		++$nocheckneed;
		//se il nuovo input è vuoto o riconducibile a valori false, assegno a res la stringa di errore
		if (empty($lastn)){
			$res= "Devi compilare tutti i campi";
		}
		//controllo lunghezza cognome, se anche solo una delle due condizioni è vera assegno a res la stringa di errore
		if ((iconv_strlen($lastn)<3)||(iconv_strlen($lastn)>20)){
			$res = "Il cognome può essere conposto da un massimo di 20 caratteri e un minimo di 3!";
		}
	}
	//se il valore del documento non corrisponde a quello presente nel db
	if ($docum!= $user['DocuIde']){
		//incremento la variabile contatore
		++$nocheckneed;
		//se il nuovo input è vuoto o riconducibile a valori false, assegno a res la stringa di errore
		if (empty($docum)){
			$res= "Devi compilare tutti i campi";
		}
		//se il nuovo documento non è valido (vedi funzioni.php), assegno a res la stringa di errore
		if (!valid_num_docu($docum)){
			$res= "Il numero di documento inserito non è valido,riprovare. Controllare di non aver digitato caratteri di spaziatura. Si ricorda che si accettano gli identificativi del documento d'identià italiano, patente italiana e passaporto.";
		}
		//se il nuovo documento non è unico nel db, assegno a res la stringa di errore
		if(!unique_docum($docum)){
			$res="Spiacente, questo documento identificativo è già associato ad un altro utente!";
		}	
	}
	//se il valore dell'età non corrisponde a quello presente nel db
	if ($age!=$user['Eta']){
		//incremento la variabile contatore
		++$nocheckneed;
		//se il nuovo input è vuoto o riconducibile a valori false, assegno a res la stringa di errore
		if (empty($age)){
			$res= "Devi compilare tutti i campi";
		}
		//controllo lunghezza età; se anche solo una delle due condizioni è vera assegno a res la stringa di errore
		if (($age<18) || ($age>99) || !is_numeric($age)) {
			$res= "Solo i maggiorenni e le persone ancora vive possono registrarsi!Inolte l'età va digitata in cifre numeriche.";
		}
	} 
	//se il valore del numero di telefono non corrisponde a quello presente nel db
	if ($tel!= $user['Tel']){
		//incremento la variabile contatore
		++$nocheckneed;
		//se il nuovo input è vuoto o riconducibile a valori false, assegno a res la stringa di errore
		if (empty($tel)){
			$res= "Devi compilare tutti i campi";
		}
		//se il nuovo input non è numerico o non è valido assegno a res la stringa di errore relativa all'errore specifico (definiti nella funzione, che si trova in funzioni.php)
		if (!validate_mobile($tel) || !is_numeric($tel)){
		$res= $resmex;
		}
	}
	//se il sesso non corrisponde a quello inserito in origine nel db
	if ($gender!= $user['Sesso']){
		//incremento la variabile contatore
		++$nocheckneed;
	}
	//se la variabile nocheckneed ha un valore superiore a 0 (ovvero almeno un valore passato in input è diverso da quello presente nel db) e la variabile che dovrebbe contenere messaggi di errore ha ancora valore null
	if ($nocheckneed>0 && $res==null ){
		//query che aggiorna i dati di uno specifico utente
		$NewData = $conn->prepare("UPDATE registrato 
								SET Nome=?,
								Cognome=?,
								Eta=?,
								Sesso=?,
								Tel=?,
								DocuIde=?,
								Nickn=?
								WHERE IdU='".$IdUs."'");
		$NewData->bind_param('ssiiiss',$name, $lastn, $age, $gender, $tel, $docum, $nickn);
		$NewData->execute();
		$NewData->close();
		$res = "OK";
	}
	//se non è stato modificato nulla e res ha ancora valore null, assegno a res la stringa di avviso
	else if($res == null){
		$res="non hai apportato nessuna modifica.";
	}
	//associo alla chiave response (dell'array ris) il valore di res che gli sarà stato assegnato in base all errore o alla riuscita dell'operazione
	$ris = array("response" => $res);
	//rappresento l'array ris come un JSON
	echo json_encode($ris);
}

//funzione per modificare la password di un utente
function modify_pass(){
	global $conn;
	global $resmex;
	//variabile res inizializzata a null
	$res=null;
	//ricavo il dato dall'array session
	$IdUs= $_SESSION['IdU'];
	//ricavo i dati dalla richiesta ajax
	$oldpass= $_POST['oldpass'];
	$newpass=$_POST['newpass'];
	$checknew=$_POST['checknew'];
	//rimuovo eventuali backslash
	$oldpass= stripslashes($oldpass);
	$newpass=stripslashes($newpass);
	$checknew=stripslashes($checknew);
	//controllo che nessuno degli input abbia valori riconducibili a false(o sia vuoto); se anche solo uno lo è, assegno a res la stringa di errore
	if ((empty($oldpass)) || (empty($newpass)) || (empty($checknew))){
		$res= "Errore, tutti i campi devono essere compilati";
	}
	//se res ha ancora valore null (nessun errore) e la vecchia password corrisponde alla nuova proposta, assegno a res la stringa di errore
	if ($res==null && $oldpass==$newpass){
		$res= "La proposta di nuova password coincide con quella originale, se decidi di cambiare password abbi cura di sceglierne una diversa!";
	}
	//se res ha ancora valore null (nessun errore),  controllo che tutti i campi non  contengano caratteri di spaziatura all'interno della stringa; se lo contengono oppure res non è null, assegno a res la stringa di errore
	if ($res==null &&((Space($oldpass))|| (Space($newpass))||(Space($checknew)))) {
		$res= "Attento, hai digitato un carattere di spaziatura, rimuovilo e riprova";
	}
	//se res ha ancora valore null (nessun errore)
	if ($res==null){
		//query che seleziona la password dell'utente 
		$getPsw = $conn->prepare("SELECT Password FROM registrato WHERE IdU= ?");
		$getPsw->bind_param('s', $IdUs);
		$getPsw->execute();
		$result = $getPsw->get_result();
		$ServPass= $result->fetch_assoc();
		$ServerPass=$ServPass['Password'];
	    //confronto la password originale passata in input con quella hashata nel db; se corrispondono..
	    if (password_verify($oldpass, $ServerPass)){   
	    	//applico alla nuova proposta e al suo reinserimento i controlli di idoneità e corrispondenza (vedi funzioni.php); se supera il controllo..
	    	if (psw_check($newpass,$checknew)){ 			
	    		//faccio l'hash della nuova password
	    		$hashnew= password_hash($newpass, PASSWORD_DEFAULT);
	    		//faccio un update sul database e salvo la nuova password (hashata) dell'utente
	    		$updateP = $conn->prepare("UPDATE `registrato` SET `Password`=?  WHERE IdU= ?");
				$updateP->bind_param('ss',$hashnew, $IdUs);
				$updateP->execute();
				//assegno a res il valore che indica la buona riuscita dell'operazione
				$res="OK";
	    	}
	    	//riga 322; se non supera il controllo, prendo il messaggio di errore relativo all'errore che è avvenuto dalla funzione pswcheck(funzioni.php) e lo asssegno a res
	    	else {
	    		$res=$resmex; 
	    	}
	    }
	    //se la password corrente inserita e quella nel db non coincidono, assegno a res la stringa di errrore
	    else {
	    	$res="La password corrente da te inserita non è corretta, riprova";
		}
	}
	//associo alla chiave response (dell'array ris) il valore di res che gli sarà stato assegnato in base all errore o alla riuscita dell'operazione
	$ris = array("response" => $res);
	//rappresento l'array ris come un JSON
	echo json_encode($ris);
}

//funzione per cancellare un utente (auto-cancellarsi) 
function delete_user(){
	global $conn;
	//ricavo il dato dall'array session
    $IdUs= $_SESSION['IdU'];
    //uso la transazione per assicurarmi che tutte le azioni vengano svolte "in blocco"
    $conn->begin_transaction();
    //query che ricava tutti i dati dei blog di appartenenza a un utente specifico
    $take_blogs = $conn->query("SELECT * FROM `blog` WHERE IdU=$IdUs");
    //per ognuno di loro...
    while ($row = $take_blogs->fetch_assoc()) {
       $blogs=$row;
       $IdB=$blogs['IdB'];
       $collab=$blogs['IdUcollab'];
       //...se un blog ha un collaboratore
       if ($blogs['IdUcollab']!=null){
       	//viene fatto un update di proprietà: il collaboratore diventa il nuovo proprietario, viene svuotato l'attributo idUcollab su quella riga e all'attributo ereditato viene assegnato il valore "SI"
       	$update_prop= $conn->query("UPDATE `blog` SET `IdU`= $collab,`IdUcollab`= null, Ereditato='SI' WHERE `IdB`=$IdB");
       }
    }
    //arrivato a questo punto, viene cancellato l'utente 
    $elimina = $conn->query("DELETE FROM `registrato` WHERE `registrato`.`IdU` = $IdUs");
    //svuoto l array $_session (ovvero rimuovo le variabili di sessione) assegnandogli come valore un array vuoto
	$_SESSION = array();
	//distruggo tutti i dati legati alla sessione corrente
	session_destroy();
	//chiudo transazione
    $conn->commit();
    //assegno a res il valore che indica la buona riuscita dell'operazione
	$res="OK"; 	
	//associo alla chiave response (dell'array ris) il valore di res che gli sarà stato assegnato in base all errore o alla riuscita dell'operazione
	$ris = array("response" => $res);
	//rappresento l'array ris come un JSON
	echo json_encode($ris);
}