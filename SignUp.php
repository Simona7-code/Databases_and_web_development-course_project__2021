<?php
//includo la connessione (più altre cose contenute in include) e le funzioni utili su questa pagina
include 'include.php';
include 'funzioni.php';
$resmex;
//se non è settata una funzione tramite post richiamo la funzione index (riga 30)
if(!isset($_POST["function"])){
	index();
}
//altrimenti richiamo la funzione passata tramite post
else{
	$function = $_POST["function"];

	switch ($function) {		
		
		case "save_signup":
			save_signup();
			break;
		case "validate_nik":
			validate_nik();
			break;
		default:
			$ris = array("response" => "Metodo non valido $function");
			echo json_encode($ris);
			break;
	}
}
//funzione che "assembla" la pagina
function index(){
	$page_title = "SignUp";
	$body = "views/SignUpView.php";
	include ("Page.php");
}
//Funzione che esegue i controlli di tutti gli input (utilizza funzioni nel file 'funzioni.php')
function save_signup(){ 
	global $resmex;
	global $conn;
	//ricavo i dati dalla richiesta ajax
	$nickn= $_POST['nickn'];
	$name=$_POST['name'];
	$lastn=$_POST['lastn'];
	$age=$_POST['age'];
	$email = $_POST['email'];
	$psw = $_POST['psw'];
	$pswcheck=$_POST['pswcheck'];
	$gender=$_POST['gender'];
	$tel=$_POST['tel'];
	$docum=$_POST['docum'];
	//rimuovo eventuali backslash
	$nickn= stripslashes($nickn);
	$name=stripslashes($name);
	$lastn=stripslashes($lastn);
	$age=stripslashes($age);
	$email = stripslashes($email);
	$psw = stripslashes($psw);
	$pswcheck=stripslashes($pswcheck);
	$tel= stripslashes($tel);
	$docum= stripslashes($docum);

	//controllo che nessuno degli input abbia valori riconducibili a false(o sia vuoto); se anche solo uno lo è, assegno a res la stringa di errore
	if (empty($nickn) || empty($name) || empty($lastn) || empty($age) || empty($email) || empty($psw) || empty($pswcheck)){
		$res= "Errore, tutti i campi devono essere compilati";
	}
 	//controllo che tutti i campi(tranne nome e cognome) non contengano caratteri di spaziatura all'interno della stringa; se anche solo uno lo ha, assegno a res la stringa di errore
	else if ((Space($nickn))|| (Space($age)) || (Space($email)) || (Space($psw)) || (Space($pswcheck))){
			$res= "Attento, hai digitato un carattere di spaziatura, rimuovilo e riprova";
	}
	//controllo che il nickname scelto sia stato disponibile; se non lo è assegno a res la stringa di errore relativa all'errore specifico (definiti nella funzione, che si trova in funzioni.php)
	else if (!validate_nik_server($nickn)) {
		$res=$resmex;
	}
	//controllo lunghezza nome, se anche solo una delle due condizioni è vera assegno a res la stringa di errore
	else if ((iconv_strlen($name)<3)||(iconv_strlen($name)>30)){
		$res = "Il nome può essere composto da un massimo di 30 caratteri e un minimo di 3!"; 
	}
	//controllo lunghezza cognome, se anche solo una delle due condizioni è vera assegno a res la stringa di errore
	else if ((iconv_strlen($lastn)<3)||(iconv_strlen($lastn)>20)){
		$res = "Il cognome può essere composto da un massimo di 20 caratteri e un minimo di 3!";
	}
	//controllo validità documento; se non è valido assegno a res la stringa di errore
	else if (!valid_num_docu($docum)){
		$res= "Il numero di documento inserito non è valido, riprovare. Controllare di non aver digitato caratteri di spaziatura. Si ricorda che si accettano gli identificativi del documento d'identià italiano, patente italiana e passaporto.";
	}
	//controlla se il documento esiste già nel db; se esiste assegno a res la stringa di errore
	else if (!unique_docum($docum)){
		$res="Spiacente, questo documento identificativo è già associato ad un altro utente!";
	}
	//controllo lunghezza età; se anche solo una delle due condizioni è vera assegno a res la stringa di errore
	else if (($age<18) || ($age>99) || !(is_numeric($age))) {
			$res= "Solo i maggiorenni e le persone ancora vive possono registrarsi! Inolte l'età va digitata in cifre numeriche.";
	} 
	//controllo mail; assegno a res la stringa di errore relativa all'errore specifico (definiti nella funzione, che si trova in funzioni.php)
 	else if (!checkemail($email)){
 	 	$res= $resmex;
 	 }
	//controllo sul numero di telefono; assegno a res la stringa di errore relativa all'errore specifico (definiti nella funzione, che si trova in funzioni.php)
	else if (!validate_mobile($tel) || !is_numeric($tel)){
		$res= $resmex;
	}
	//controllo password; assegno a res la stringa di errore relativa all'errore specifico (definiti nella funzione, che si trova in funzioni.php)
 	else if (psw_check($psw, $pswcheck)==false){
	 	 	$res= $resmex;
	}
	//se i controlli vanno a buon fine
	else {
		//prendo la password proposta in input e faccio un hashing di essa
		$hashed= password_hash($psw, PASSWORD_DEFAULT);
		//query inserimento nuovo utente nel db (nella password inserisco quella hashata a riga 107)
		$NewUser = $conn->prepare("INSERT INTO `registrato`(`Nome`, `Cognome`, `Eta`, `Email`, `Password`, `Sesso`, `Tel`, `DocuIde`, `Nickn`) VALUES(?,?,?,?,?,?,?,?,?)");
		$NewUser->bind_param('ssississs',$name, $lastn, $age, $email, $hashed, $gender, $tel, $docum, $nickn);
		$NewUser->execute();
		$NewUser->close();
		//recupero ID appena creato dal DB (la mail è unique per ogni registrato)
		$getID = $conn->prepare("SELECT IdU FROM registrato WHERE Email=?");
		$getID->bind_param('s',$email);
		$getID->execute();
		$result = $getID->get_result();
		while ($row = $result->fetch_assoc()) {
	       $IdUser=$row["IdU"];
	    }
		//Salvo dati in session
		$_SESSION['Nome'] = $name;
		$_SESSION['Cognome'] = $lastn;
		$_SESSION['Nickn'] = $nickn;
		$_SESSION['IdU'] = $IdUser;
		$_SESSION['OK']= "OK";
		$res = "OK";
	}
	//associo alla chiave response (dell'array ris) il valore di res che gli sarà stato assegnato in base all errore o alla riuscita dell'operazione
	$ris = array("response" => $res);
	//rappresento l'array ris come un JSON
	echo json_encode($ris);
}