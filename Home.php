 <?php 
//includo la connessione (più altre cose contenute in include) e le funzioni utili su questa pagina
include 'include.php';
include 'funzioni.php';
 
//se non è settata una funzione tramite post richiamo la funzione index (riga 27)
if(!isset($_POST["function"])){
	index();
}
//altrimenti richiamo la funzione passata tramite post
else{
	$function = $_POST["function"];

	switch ($function) {		
		
		case "save_login":
			save_login();
			break;
		
		default:
			$ris = array("response" => "Metodo non valido $function");
			echo json_encode($ris);
			break;
	}
}

//"costruisco" la pagina tramite page.php, assegnando al body e page title quelli definiti a righe 30 e 31
function index(){
	$page_title = "Home page";
	$body = "views/homeView.php";
	include ("Page.php");
}

//Funzione per effettuare un login
function save_login(){   
	global $conn;
	$res=" ";
	//ricavo i dati dalla richiesta ajax
	$Email = ($_POST["email"]);
	$Psw =($_POST["psw"]);
	//rimuovo eventuali backslash
	$Email= stripslashes($Email);
	$Psw= stripslashes($Psw);
	//se uno dei due campi è vuoto, assegno a res la stringa di errore (sarà stampata tramite alert, file my_script.js)
	if(empty($Email) || empty($Psw)){
		$res = "E'necessario compilare entrambi i campi!";
	}
	//altrimenti se sono presenti caratteri di spazio nelle stringhe (per maggiori dettagli vedi funzioni.php) assegno a res la stringa di errore
	else if ((Space($Email))|| (Space($Psw))){
			$res= "Attento, hai digitato un carattere di spaziatura, rimuovilo e riprova";
	}
	//altrimenti 
	else{
		//Rimuovo dalla stringa salvata in $email tutti i caratteri eccetto lettere, numeri e !#$%&'*+-/=?^_`{
     	$Email = filter_var($Email, FILTER_SANITIZE_EMAIL); 
     	//se il formato della mail non è valido assegno a res la stringa di errore
     	if (filter_var($Email, FILTER_VALIDATE_EMAIL)==false){
     	 	$res= "Email non valida.";
     	 }
     	//query che seleziona i dati d'interesse dell'utente con la mail che gli viene passata
		$query = $conn->prepare("SELECT Nome, Cognome,IdU, Nickn, Password FROM registrato WHERE Email = ?");
		$query->bind_param('s',$Email);
	    $query->execute();
	    $result = $query->get_result();
		$query->close();
		 //conta righe risultanti dalla query
        $rowcount = $result-> num_rows;
        //se non esiste risultato, la mail non è registrata nel sistema e  assegno a res la stringa di errore
        if($rowcount == 0){
        	$res="Spiacente,la tua mail non risulta presente nei nostri sistemi. Controlla di aver digitato bene e riprova";
        } 
        //se esiste uno e un solo risultato
		else if($rowcount == 1){ 
			//tramite ciclo associo a delle variabili i valori che mi interessano dell'utente
        	while ($row = $result->fetch_assoc()){ 

	            $name = $row['Nome'];
				$surname = $row['Cognome'];
		    	$IdU  = $row['IdU']; 
		    	$nickn =$row['Nickn'];
		    	$Pass  = $row['Password'];
        	}
        	//se la password inserita corrisponde alla password hashata salvata nel db
            if (password_verify($Psw, $Pass)){
     	  		//salvo in array di sessione le variabili prima assegnate
		        $_SESSION['Nome'] = $name;
				$_SESSION['Cognome'] = $surname;
				$_SESSION['Nickn'] = $nickn;
				$_SESSION['IdU'] = $IdU;
				$_SESSION['OK']= "OK";
				$res= "OK";       
	     	}
	     	//se la password nel db e quella passata in input non corrispondono assegno a res la stringa di errore
			else {
			 	$res= "La password inserita non è corretta, riprovare";
			}
		}
		//caso in cui non dovrebbe entrare mai (più righe risultate dalla query)
        else {
          $res = "Email non corretta, riprovare";
        }
    } 
//associo alla chiave response (dell'array ris) il valore di res che gli sarà stato assegnato in base all errore o alla riuscita dell'operazione
$ris = array("response" => $res);
//rappresento l'array ris come un JSON
echo json_encode($ris);
}