<?php
include'include.php';
include'funzioni.php';
global $conn;


//se tramite post ( chiamata ajax) non è stata settata una funzione chiamo la funzione a riga 65
if(!isset($_POST["function"]))
{
	index();
}
else
{ 	//in base alla funzione passata tramite ajax data in post
	$function = $_POST["function"];
	//switch che determina la funzione da invocare in base a (continua riga 13)
	switch ($function) {	

		case "new_post":
			new_post();
			break;
		case "ins_comm":
			ins_comm();
			break;
		case "Del_comment":
			Del_comment();
			break;
		case "save_post":
			save_post();
			break;
		case "Del_post":
			Del_post();
			break;
		case "delete_blog":
			delete_blog();
			break;
		case "insert_coll":
			insert_coll();
			break;
		case "delete_coll":
			delete_coll();
			break;
		case "blog_mod":
			blog_mod();
			break;
		case "insert_follow":
			insert_follow();
			break;
		case "del_follow":
			del_follow();
			break;
		case "ins_like":
			ins_like();
			break;
		case "del_like":
			del_like();
			break;

		default:
			$ris = array("response" => "Metodo non valido $function");
			echo json_encode($ris);
			break;
	}
}

function index(){
	//se non è settato  l'id del blog nell'array superglobale get (prende dato tramite metodo get)oppure è vuoto il valore della chiave (id)
	if (!isset($_GET["idB"])||empty($_GET["idB"])){
		//riporta alla pagina che reindirizza alla home
		 header("location: index.php");
	}
	//riassegno il valore della chiave page in array session (prima null (vedi include.php) e null su tutte le altre pagine), poi definisco il title page e body, richiamo page.php per assemblare la pagina 
	$_SESSION["page"] = "ChosenBlog";
	$page_title = "ChosenBlog";
	$body = "views/ChosenBlogView.php";
	include ("Page.php");
}

//funzione per creare un post
function new_post(){
	
	global $conn;
	$date;
	$time;
	$newIdP;
	//ricavo i dati dalla richiesta ajax
	$IdB = $_POST['IdB'];
	$Titolo=$_POST['newtit'];
	$Testo=$_POST['newtext'];
	//VEDI COME PASSARTI LE IMMAGINI
	$Titolo= stripslashes($Titolo);
	$Testo= stripslashes($Testo);
	//se è vuoto anche solo uno dei due campi, salva nella variabile il messaggio di errore che verrà stampato tramite alert da ajax
	if(empty($Titolo) || empty($Testo)){
		$res = "E'necessario compilare entrambi i campi!";
	}
	//altrimenti se il titolo ha lunghezza minore di 1 o maggiore di 50, salva nella variabile il messaggio di errore che verrà stampato tramite alert da ajax
	else if ((iconv_strlen($Titolo)<3)||(iconv_strlen($Titolo)>50)){
		$res = "Il titolo può essere conposto da un massimo di 50 caratteri e un minimo di 3!"; 
	}
	//altrimenti se il testo ha lunghezza minore di 1 o maggiore di 2000, salva nella variabile il messaggio di errore che verrà stampato tramite alert da ajax
	else if ((iconv_strlen($Testo)<3)||(iconv_strlen($Testo)>2000)){
		$res = "Il corpo di un post può essere conposto da un massimo di 2000 caratteri e un minimo di 3!"; 
	}
	//se non ci sono gli errori descritti nelle condizioni sopra
	else{
		//salvo in variabili il path dell'immagine nella cartella upload (dove la funzione sposta l'immagine)
		$image_path1 = save_image("image1");
		$image_path2 = save_image("image2");
		$image_path3 = save_image("image3");

		//cattura l'orario e la giornata corrente e li salva nelle relative variabili
		$date= date("Y-m-d");
		$time= date("h:i:s");

		//$conn->begin_transaction();
		//inserisce il post nel db e salva l'ultimo id inserito in una variabile
		$new_post = $conn->prepare("INSERT INTO `post`(`TitoloP`, `TestoP`, `DataP`, `OraP`, `IdB`) VALUES (?,?,?,?,?)");
		$new_post->bind_param('ssssi', $Titolo, $Testo, $date, $time, $IdB);
		$new_post->execute();
		$newIdP= $conn->insert_id;
		$new_post->close();
		//se c'è un valore diverso da null (è stata passata un immagine)
		if($image_path1 != null){	
			//inserisco la stringa del path nel database (con riferimento al post su cui è inserita)
			$conn->query("INSERT INTO img (File_Img, Idp) VALUES (\"$image_path1\", $newIdP)");
		}
		//stessa spiegazione di righe 122 e 124
		if($image_path2 != null){
			$conn->query("INSERT INTO img (File_Img, Idp) VALUES (\"$image_path2\", $newIdP)");
		}
		//stessa spiegazione di righe 122 e 124
		if($image_path3 != null){
			$conn->query("INSERT INTO img (File_Img, Idp) VALUES (\"$image_path3\", $newIdP)");
		}
		//è andato tutto bene e salvo valore ok dentro la variabile,che sarà il valore di response all'interno dell'array ris, che verrà rappresentato tramite oggetto json da json_encode; 
		$res="OK";
	}
    $ris = array("response" => $res);
	echo json_encode($ris);
}
//Funzione per inserire commento al corrispondente post
function ins_comm(){
	global $conn;
	$dateC;
	$timeC;
	$IdU=$_SESSION['IdU'];
	$IdP = $_POST['IdP'];
	$TestoC=$_POST['comtext'];
	$TestoC= stripslashes($TestoC);
	//se il testo passato in input è empty(vuoto o 0), salva nella variabile il messaggio di errore che verrà stampato tramite alert da ajax
	if(empty($TestoC)){
		$res = "Compila il campo se vuoi commentare !";
	}
	//altrimenti,se il testo passato in input è più lungo di 1000 o più piccolo di 1 carattere, salva nella variabile il messaggio di errore che verrà stampato tramite alert da ajax
	else if ((iconv_strlen($TestoC)<1)||(iconv_strlen($TestoC)>1000)){
		$res = "Il titolo può essere conposto da un massimo di 1000 caratteri e un minimo di 1!"; 
	}
	// se non ci sono gli errori descritti nelle condizioni sopra
	else{
		$dateC= date("Y-m-d");
		$timeC= date("h:i");
		$new_comment= $conn->prepare("INSERT INTO `commenti`( `IdU`, `TestoC`, `DataC`, `OraC`, `Idp`) VALUES (?,?,?,?,?)");
		$new_comment->bind_param('ssssi', $IdU, $TestoC, $dateC, $timeC, $IdP);
		$new_comment->execute();
		$res="OK"; 
	}
    $ris = array("response" => $res);
	echo json_encode($ris);
}
//funzione invocata dal submit del form del "Gestici collaborazioni" nel caso il blog non abbia un collaboratore
function insert_coll(){

	global $conn;
	$IdB = $_POST['IdB'];
	$your_nickn= $_SESSION['Nickn'];
	$coll_nick=$_POST['nickn'];
	$coll_nick= stripslashes($coll_nick);
	//query per vedere se esiste il nickn che vuole inserire come collaboratore
	$exist_nick= $conn->prepare("SELECT Nickn FROM registrato WHERE Nickn= ?");
	$exist_nick->bind_param('s', $coll_nick);
	$exist_nick->execute();
	$result= $exist_nick->get_result();
	$exist_nick->close();
	$rowcount = $result-> num_rows;
	//se il nickname passato in input è empty,salva nella variabile il messaggio di errore che verrà stampato tramite alert da ajax
	if (empty($coll_nick)){
		$res="Se desideri inserire un nuovo collaboratore devi inserire un nickname.";
	}
	//altrimenti se il nickname passato in input è più lungo di 11 e più piccolo di 4 caratteri ,salva nella variabile il messaggio di errore che verrà stampato tramite alert da ajax
	else if ((iconv_strlen($coll_nick)<4)||(iconv_strlen($coll_nick)>11)){
		$res = "Il nickname può essere conposto da un massimo di 10 caratteri e un minimo di 4!";
	}
	//altrimenti se il nickname passato in input è uguale al nikname dell'utente in sessione ,salva nella variabile il messaggio di errore che verrà stampato tramite alert da ajax
	else if ($your_nickn==$coll_nick){
		$res= "Non puoi nominare collaboratore te stesso se il blog è tuo, digita il nickname di altri";
	}
	//altrimenti se il nickname passato in input non corrisponde a nessun nickname nel db ,salva nella variabile il messaggio di errore che verrà stampato tramite alert da ajax
	else if ($rowcount==0){
		$res= "Il nickname da te digitato non corrisponde a nessun utente, controlli di aver digitato correttamente il nickname.";
	}
	else {
		//voglio che tutte queste azioni vengano realizzate in blocco, quindi uso la transaction
		$conn->begin_transaction();
		//take_idcoll serve per risalire all'id del nickname digitato dall'utente; 
		$take_idcoll = $conn->prepare("SELECT IdU FROM registrato WHERE Nickn= ?");
		$take_idcoll->bind_param('s', $coll_nick);
		$take_idcoll->execute();
		$result_id = $take_idcoll->get_result();
		$idcoll = $result_id->fetch_assoc();
		$idcollab=$idcoll['IdU'];
		//controllo se l'utente che viene nominato collaboratore è anche un follower dello stesso blog
		$is_follower= $conn->query("SELECT * FROM segue WHERE IdU=$idcollab AND IdB=$IdB");
		$count_isfollow= $is_follower-> num_rows;
		//$isfollow= $isfollower->fetch_assoc();
		//se il futuro collaboratore non è un follower del blog, lo nomino direttamente collaboratore
		if ($count_isfollow!=1){
			//insert_coll serve per inserire l'id precedentemente ricavato in corrispondenda dell'id del blog dove questo diventerà collaboratore.
			$insert_coll = $conn->prepare("UPDATE `blog` SET `IdUcollab`=? WHERE IdB=$IdB");
			$insert_coll->bind_param('i', $idcollab);
			$insert_coll->execute();
			$conn->commit();
			$res="OK";
		}
		//se il futuro colalboratore è un follower del blog	
		else {
			//rimuovo il follow dalla pagina
			$delete_follow= $conn->query("DELETE FROM `segue` WHERE IdU=$idcollab AND IdB=$IdB");
			//e dopo lo nomino collaboratore (commento riga 218)
			$insert_coll = $conn->prepare("UPDATE `blog` SET `IdUcollab`=? WHERE IdB=$IdB");
			$insert_coll->bind_param('i', $idcollab);
			$insert_coll->execute();
			$conn->commit();
			$res="OK";
		}	  
	}
	$ris = array("response" => $res);
	echo json_encode($ris);
}

//funzione invocata dal submit del form del bottone "Gestici collaborazioni" nel caso il blog abbia già un collaboratore e lo si voglia rimuovere
function delete_coll(){
	
	global $conn;
	$IdB = $_POST['IdB'];
	//query che cancella l'id del collaboratore dalla riga del blog dove viene fatta la cancellazione
	$delete_coll = $conn->query("UPDATE `blog` SET `IdUcollab`=null WHERE `blog`.`IdB` = $IdB;");
	$res="OK"; 
    $ris = array("response" => $res);
	echo json_encode($ris);
}
//funzione invocata dal bottone "Elimina blog", che fa due azioni diverse in base al fatto se esiste o no il collaboatore
function delete_blog(){
	
	global $conn;
	$IdB = $_POST['IdB'];
	//query che seleziona gli id dell utente collaboratore e proprietario dal blog su cui viene invocata la funzione
	$take_diritti= $conn->query("SELECT IdU, IdUcollab FROM blog WHERE IdB=$IdB");
	while ($row = $take_diritti->fetch_assoc()) {
	    $diritti = $row;
	}
	$Idcollabor= $diritti['IdUcollab'];
	//se NON esiste un collaboratore per quel blog
	if ($diritti['IdUcollab']==null){
		//viene cancellato il blog dal database
		$delete_blog = $conn->query("DELETE FROM `blog` WHERE  `blog`.`IdB` = $IdB");
		$res="OK";
	} 
	//se esiste un collaboratore
	else {
		//faccio un passaggio di proprietà: IdUcollab viene settato a null, quel collaboratore diventa il nuovo proprietario (IdU) e assegno all' attributo "ereditato" un valore, per distinguerlo dai non ereditati (default null)
		$update_prop= $conn->query("UPDATE `blog` SET `IdU`= $Idcollabor,`IdUcollab`= null, Ereditato='SI' WHERE `IdB`=$IdB");
		$res="OK";
	}
    $ris = array("response" => $res);
	echo json_encode($ris);
}
//funzione per modificare grafica e titolo blog
function blog_mod(){
	
	global $conn;
	//inizializzo variabile contatore a 0
	$nocheckneed=0;
	$res=null;
	$IdB = $_POST['IdB'];
	$titolo_blog=$_POST['titolom'];
	$sfondo=$_POST['sfondom'];
	$font=$_POST['fontm'];
	$titolo_blog= stripslashes($titolo_blog);
 	//query che prende alcuni dati relativi al blog in cui ci si trova
	$take_bdata = $conn->prepare("SELECT Titolo, IdSf, IdFo FROM blog WHERE IdB=?");
    $take_bdata->bind_param('i', $IdB);
    $take_bdata->execute();
    $result_bdata = $take_bdata->get_result();
    //salvo in variabile blog i dati appena ricavati
    $blog = $result_bdata->fetch_assoc();
    //se il titolo del blog in input è diverso da quello nel db
	if ($titolo_blog!= $blog['Titolo']){
		//incrementa variabile contatore: è cambiato qualcosa
		++$nocheckneed;
		//se l'input è empty, messaggio di errore che poi tramite alert verrà stampato
		if (empty($titolo_blog)){
			$res= "Errore, tutti i campi devono essere compilati";
		}
		//se l'input è più lungo di 100, messaggio di errore che poi tramite alert verrà stampato
		if (iconv_strlen($titolo_blog)>100){
			$res = "Il titolo può essere conposto da un massimo di 100 caratteri!";
		}
	}
	//se lo sfondo passato in input è diverso da quello nel db
	if ($sfondo!= $blog['IdSf']){
		//incrementa variabile contatore: è cambiato qualcosa
		++$nocheckneed;
	}//se lo sfondo passato in input è diverso da quello nel db
	if ($font!= $blog['IdFo']){
		//incrementa variabile contatore: è cambiato qualcosa
		++$nocheckneed;
	}
	//se la variabile contatore è maggiore di 0 (è cambiato qualcosa da qualche parte) ma quel qualcosa che è cambiato non ha errori (res non ha avuto assegnamenti di messaggi di errore)
	if ($nocheckneed>0 && $res==null){
		$Mody_blog = $conn->prepare("UPDATE blog SET Titolo=?,IdSf=?,IdFo=? WHERE IdB=?");
		$Mody_blog->bind_param('siii',$titolo_blog, $sfondo, $font, $IdB);
	    $Mody_blog->execute();
		$Mody_blog->close();
		$res="OK";
	}
	//altrimenti, se non è cambiato nulla e res continua a valere null (non ci sono errori) non eseguo la query di update e lo avviso che non ha apportato modifiche
	else if($res==null)
	{
		$res="non hai apportato nessuna modifica.";
	}

$ris = array("response" => $res);
echo json_encode($ris);
}

//funzione che inserisce il "follow" da parte dell'utente in sessione verso il blog su cui invoca la funzione
function insert_follow(){
	global $conn;
	$IdB = $_POST['IdB'];
	$IdU = $_SESSION['IdU'];
	$Mody_blog = $conn->prepare("INSERT INTO `segue`(`IdU`, `IdB`) VALUES (?,?)");
	$Mody_blog->bind_param('ii',$IdU, $IdB);
	$Mody_blog->execute();
	$Mody_blog->close();
	$res="OK";
	$ris = array("response" => $res);
	echo json_encode($ris);
}

//funzione che rimuove il "follow" da parte dell'utente in sessione verso il blog su cui invoca la funzione
function del_follow(){
	global $conn;
	$IdB = $_POST['IdB'];
	$IdU = $_SESSION['IdU'];
	$Mody_blog = $conn->prepare("DELETE FROM `segue` WHERE IdU=? AND IdB=?");
	$Mody_blog->bind_param('ii',$IdU, $IdB);
	$Mody_blog->execute();
	$Mody_blog->close();
	$res="OK";
	$ris = array("response" => $res);
	echo json_encode($ris);
}

//funzione che rimuove un commento specifico (controlli su chi può farlo su chosenlogview.php)
function Del_comment(){
	global $conn;
	$IdC = $_POST['IdC'];
	$Del_comm = $conn->prepare("DELETE FROM `commenti` WHERE IdC= ?");
	$Del_comm->bind_param('i',$IdC);
	$Del_comm->execute();
	$res="OK";
	$ris = array("response" => $res);
	echo json_encode($ris);
}

//funzione che rimuove un post specifico (controlli su chi può farlo su chosenlogview.php)
function Del_post(){
	global $conn;
	$IdP = $_POST['IdP'];
	$Del_post = $conn->prepare("DELETE FROM `POST` WHERE IdP= ?");
	$Del_post->bind_param('i',$IdP);
	$Del_post->execute();
	$res="OK";
	$ris = array("response" => $res);
	echo json_encode($ris);
}

//funzione che inserisce un like su un post specifico da parte dell'utente in sessione (controlli su chi può farlo su chosenlogview.php)
function ins_like(){
	global $conn;
	$IdP = $_POST['IdP'];
	$IdU = $_SESSION['IdU'];
	$ins_like = $conn->prepare("INSERT INTO `apprezza`(`IdU`, `IdP`) VALUES (?,?)");
	$ins_like->bind_param('ii',$IdU,$IdP);
	$ins_like->execute();
	$res="OK";
	$ris = array("response" => $res);
	echo json_encode($ris);
}

//funzione che rimuove un like su un post specifico da parte dell'utente in sessione (controlli su chi può farlo su chosenlogview.php)
function del_like(){
	global $conn;
	$IdP = $_POST['IdP'];
	$IdU = $_SESSION['IdU'];
	$del_like = $conn->prepare("DELETE FROM `apprezza` WHERE IdU=? AND IdP=?");
	$del_like->bind_param('ii',$IdU,$IdP);
	$del_like->execute();
	$res="OK";
	$ris = array("response" => $res);
	echo json_encode($ris);
}