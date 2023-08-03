<?php
//includo la connessione (più altre cose contenute in include) e le funzioni utili su questa pagina
include "include.php";
include "funzioni.php";
global $conn;
//rendo function globale in modo che sia visibile alla pagina
global $function;
$resmex;
//tutte le funzioni di ricerca restituiscono la variabile $result_blogs, che contiene i dati dei blog che corrispondono alla ricerca.
$result_blogs;
//definizione delle 3 funzioni di ricerca:

//funzione che cerca tutti i blog che hanno un determinato argomento 
function search_arg(){
  global $conn;
  //ricavo id dellargomento tramite post (no ajax)
  $Idarg= $_POST['arg'];
  //inizializzo resuts_blog a null; se la ricerca va a buon fine avrà una riassegnamento
  $result_blogs = null;
  //ricavo dal db di tutti i blog che hanno come argomento quello scelto dall'utente; uso il distinct in modo da ricavare solo una riga delle eventuali molteplici (es se il blog ha più di un sottoargomento)
  $take_blogs = $conn->prepare("SELECT DISTINCT IdB, Titolo, IdU, IdSf FROM `argblogview` WHERE IdT=?");
  $take_blogs->bind_param('i',$Idarg);
  $take_blogs->execute();
  //i valori vengono assegnati alla variabile result_blog che viene poi resitituita dalla funzione
  $result_blogs = $take_blogs->get_result();
  return $result_blogs;
}

//funzione che cerca gli IdU tramite Nickname passato in input e poi seleziona tutti i blog di quell'IdU
function search_nick(){
  global $conn;
  global $resmex;
  //ricavo nickname tramite post (no ajax)
  $Nick= $_POST['nickn'];
  //rimuovo eventuali backslash
  $Nick= stripslashes($Nick);
  //inizializzo resuts_blog a null; se la ricerca va a buon fine avrà una riassegnamento
  $result_blogs = null;
  //se i valori passati vanno bene, ovvero NON sono empty oppure sono lunghi più di 10 caratteri (che è il limite di grandezza ogni nickname)
  if(!empty($Nick) && (iconv_strlen($Nick)<11) && !Space($Nick)){
    //Cerca nella vista blog_nick tutti i dati dei blog il cui Nickname(del proprietario) è simile a ciò che gli viene passato in input(ovvero l'input deve essere contenuto nel nickname)
    $result_blogs = $conn->query("SELECT * FROM `blog_nick` WHERE `Nickn`LIKE \"%$Nick%\"");
  }
  //se sono stati passati valori non validi (vedi riga 38)
  else { 
    //viene assegnata alla variabile resmex il messaggio di errore da allegare al generale messaggio di "nessun risultato di ricerca"
    $resmex = "Non sono stati inseriti dei parametri validi. Ricordati che puoi digitare soltanto una parola, non devono essere presenti caratteri di spaziatura e nessun nickname del sito è più grande di 10 caratteri.";
  }
  return $result_blogs;
}

//Funzione che cerca una parola all'interno dei titoli di tutti i blog del sito
function search_wordtitle(){
  global $conn;
  global $resmex;
  //ricavo nickname tramite post (no ajax)
  $word= $_POST['word'];
  //rimuovo eventuali backslash
  $word= stripslashes($word);
  //inizializzo resuts_blog a null; se la ricerca va a buon fine avrà una riassegnamento
  $result_blogs = null; 
  //se la parola ricercata non è empty (vuota o qualsiasi valore che riconduca a false) e non sono presenti caratteri di spaziatura nella stringa, fa la ricerca
  if(!empty($word) && !Space($word)){
      //seleziono i blog che nel titolo contengono la stringa passata in input e li assegno a variabile result_blog che viene poi resitituita dalla funzione
      $result_blogs = $conn->query("SELECT * FROM `blog` WHERE Titolo LIKE \"%$word%\"");
  }
  // altrimenti allega al messaggio di "risultati non trovati il tipo di errore"
  else{
    //viene assegnata alla variabile resmex il messaggio di errore da allegare al generale messaggio di "nessun risultato di ricerca"
    $resmex = "Non sono stati inseriti dei parametri validi. Ricordati che puoi digitare soltanto una parola e che non devono essere presenti caratteri di spaziatura.";
  }
  return $result_blogs;
}


//switch delle funzioni chiamabili; quando e se chiamate vengono invocate
switch ($function) {
  case 'search_arg':
    $result_blogs = search_arg();
    break;

  case 'search_nick':
    $result_blogs = search_nick();
    break;

  case 'search_wordtitle':
    $result_blogs = search_wordtitle();
    break;
}
?>

<body class="generic_font generic_bg mt-5">
  <div id="page-container">
    <div id="content-wrap">
      <!--barra di navigazione della pagina-->
      <header class="pb-4">
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
          <div class="container d-flex justify-content-between">
            <h1 class="navbar-brand">Thoughts</h1>
            <a class="navbar-brand">
                <img src="immagini/logo.png" class="rounded-circle" alt="Logo" style="width:50px;">
            </a>
            <ul class="navbar-nav ml-auto">
              <li class="nav-item">
                <?php
                //se l'utente è loggato gli mostro i bottoni di suo interesse
                if(isset($_SESSION['OK']) && !empty($_SESSION['OK'])) {
                ?>
                  <button type="button" class="btn btn-outline-light  mr-3" onClick="window.location = 'Home.php'">Home</a></button>
                  <button  type="button" class="btn btn-outline-light  mr-3" onClick="window.location = 'Cerca.php'">Torna a ricerca</button>
                  <button type="button" class="btn btn-outline-light  mr-3" onClick="window.location = 'Profile.php'">Torna al Profilo</a></button>
                  <button type="button" class="btn btn-outline-light" onClick="window.location = 'logout.php'">Logout</a></button>
                <?php
                }
                //se l'utente è visitatore gli mostro i bottoni di suo interesse
                else{
                ?>   
                  <button type="button" class="btn btn-outline-light  mr-3" onClick="window.location = 'Home.php'">Home</a></button>
                  <button type="button" class="btn btn-outline-light" onClick="window.location = 'Cerca.php'">Torna a ricerca</button>
                  <?php
                }
                ?>
              </li>
            </ul>
          </div>
        </nav> 
      </header>

      <?php 
      $count_ris = 0;
      //se la variabile è settata
      if(isset($result_blogs)){ 
        //fa la conta delle righe risultate dalla ricerca e le assegna alla variabile count_ris
        $count_ris = $result_blogs-> num_rows;
      }
      //se ci sono risultati (qualsiasi valore diverso da zero)
      if ($count_ris!=0){
          //per ogni blog risultato dalla ricerca
          while ($row = $result_blogs->fetch_assoc()) { 
              //salvo nella variabile blog i valori del blog corrente
              $blog = $row; 
              //Ricavo nicknme del proprietario del blog corrente (query utile per tutte le funzioni di ricerca tranne search_nick)
              $Take_prop= $conn->prepare("SELECT  `Nickn`  FROM `registrato` WHERE IdU=?");
              $Take_prop->bind_param('i',$blog['IdU']);
              $Take_prop->execute();
              $result_prop = $Take_prop->get_result();
              $prop = $result_prop->fetch_assoc();
              //ricavo nickname collaboratore del blog corrente
              $Take_coll= $conn->prepare("SELECT  `Nickn`  FROM `registrato` WHERE IdU=?");
              $Take_coll->bind_param('i',$blog['IdUcollab']);
              $Take_coll->execute();
              $result_coll = $Take_coll->get_result();
              $coll = $result_coll->fetch_assoc();
              //ricavo id degli argomenti e sottoargomenti del blog corrente
              $Take_IDarg= $conn->prepare("SELECT `IdT` FROM `argblog` WHERE IdB=?");
              $Take_IDarg->bind_param('i',$blog['IdB']);
              $Take_IDarg->execute();
              $result_IDarg = $Take_IDarg->get_result();
              $IDarg = $result_IDarg->fetch_assoc();
              //ricavo il nome dell'argomento (del blog corrente) che corrisponde all'id dell'argomento precedentemente ricavato
              $Take_arg= $conn->prepare("SELECT  `Argomento` FROM `tema` WHERE `IdT`=?");
              $Take_arg->bind_param('i',$IDarg['IdT']);
              $Take_arg->execute();
              $result_arg = $Take_arg->get_result();
              $arg = $result_arg->fetch_assoc();
              //ricavo id dei sottoargomenti 
              $take_idsott = $conn->prepare("SELECT IdSt FROM argblog WHERE IdB= ?");
              $take_idsott->bind_param('i',$blog['IdB']);
              $take_idsott->execute();
              $result_idsott = $take_idsott->get_result();
              ?> 
              <!--stampo i vari blog risultanti (con inerenti dati d'interesse) sfruttando il ciclo while-->
              <nav class="navbar">
                <div class="navbar-nav mx-auto mt-5">
                  <div class="card align-items-center" style="width:600px" >
                    <div class="card-body">
                      <!--link/titolo blog-->
                      <a href="ChosenBlog.php?idB=<?php echo $blog['IdB']?>" class="nav-link">
                        <h5 class="card-title"><?php echo $blog['Titolo']?></h5>
                      </a>
                    </div>
                    <!--stampo il nickname del proprietario-->
                    <h6 class="card-text mb-4">Blog di <b><?php echo $prop['Nickn']?></b>
                      <?php 
                      //se il blog ha un collaboratore stampo la riga che lo presenta e contiene il suo nickname
                      if ($blog['IdUcollab']!=null) { ?> 
                        in collaborazione con <b><?php echo $coll['Nickn']?></b>
                      <?php 
                      }
                      ?>
                    </h6>
                    <!--stampo l'argomento del blog-->
                    <h6 class="card-text text-center mb-4">Argomento:</br><b><?php echo $arg['Argomento']?></b></h6>
                    <h6 class="card-text text-center mb-4">Sottoargomenti: </br> <b>
                      <?php  
                      //per ogni sottoargomento 
                      while ($row = $result_idsott->fetch_assoc()) {
                        //salvo nella variabile IdSt L'Id del sottoargomento corrente
                        $IdSt = $row; 
                        //se il sottoargomento è diverso da null
                        if($IdSt['IdSt']!=null){
                          //ricavo il nome del sottoargomento corrente nel while e lo stampo (con allegato trattino e a capo)
                          $take_sottarg = $conn->prepare("SELECT  `sottoargomento` FROM `sottotema` WHERE `IdSt`=?");
                          $take_sottarg->bind_param('i',$IdSt['IdSt']);
                          $take_sottarg->execute();
                          $result_sottarg = $take_sottarg->get_result();
                          $sottarg = $result_sottarg->fetch_assoc();
                          echo "-".$sottarg['sottoargomento'];
                          echo "</br>";
                        } //chiude if riga 217
                        //se il sottoargomento (id) è uguale a null
                        else echo "Questo blog non ha sottoargomenti";
                      //chiude while riga 213
                      }
                      ?> 
                    </b></h6>
                  </div>
                </div>
              </nav>
            <?php 
          }//chiusura del while di riga 139
      }//chiusura dell'if di riga 137
    //else dell'if riga 149--altrimenti se non ci sono risultati alla ricerca(count_ris resta null) OPPURE i parametri passati in input non rispettano i criteri di accettabilità espressi negli if delle relative funzioni(in quel caso la variabile non sarebbe settata) 
    else { 
    ?>
      <nav class="navbar">
        <div class="navbar-nav mx-auto mt-5">
          <div class="card text-center" style="width:600px" >
            <div class="card-body">
              <h5 class="card-title">Siamo spiacenti la tua ricerca non ha prodotto risultati. 
                <?php 
                  //Se resmex non è vuoto (gli sono stati assegnati valori all'interno della funzione search_wordtitle OPPURE search_nick (ovvero sono stati passati input non accettabili)), stampo il valore di resmex
                  if(!empty($resmex)){ 
                  ?>
                    </br>
                    <?php echo $resmex; 
                  }
                  ?>
                </br>Perchè non provi con un'altra ricerca?
              </h5>
              <!--bottone per tornare alla pagina di ricerca-->
              <button type="button" class="btn btn-success" onClick="window.location = 'Cerca.php'">Torna a ricerca</a></button>
            </div>
          </div>
        </div>
      </nav>
      <?php
    }//chiusura else riga 225
    ?>