<?php
global $conn;
//assegno alla variabile IdB il valore dell'id del blog tramite get
$IdB = $_GET["idB"];
//seleziono l'id raccolto dal get all'interno del database
$take_idb = $conn->query("SELECT IdB FROM blog WHERE IdB=$IdB ");
//conto le righe risultate da questa selezione
$idbexist = $take_idb-> num_rows;
//se la selezione ha prodotto un risultato diverso da 0
if ($idbexist!=0){
    //se è settato l'Id dell'utente loggato dentro l'array superglobale di sessione, salvo l'id nella variabile IdUsess
    if (isset($_SESSION["IdU"])){
      $IdUsess=$_SESSION["IdU"];
    }
    //query che ricava tutti i dati del blog in cui ci si trova
    $take_blog = $conn->prepare("SELECT * FROM blog WHERE IdB= ?");
    $take_blog->bind_param('i',$IdB);
    $take_blog->execute();
    $result_blog = $take_blog->get_result();
    $blog = $result_blog->fetch_assoc();
    //salvo in variabili gli id di sfondo e font correnti nel blog
    $IDactualSf=$blog['IdSf'];
    $IDactualFo=$blog['IdFo'];
    //ricava nome colore sfondo già scelto alla creazione
    $actualcolorSF= $conn->query("SELECT Sfondo FROM sfondo WHERE IdSf=$IDactualSf");
    $actcolorSF = $actualcolorSF->fetch_assoc();
    //ricava nome colore font già scelto alla creazione
    $actualcolorFo= $conn->query("SELECT Font FROM font WHERE IdFo=$IDactualFo");
    $actcolorFO = $actualcolorFo->fetch_assoc();
    //query che ricava il nickn del proprietario del blog
    $own = $conn->prepare("SELECT Nickn FROM registrato WHERE IdU= ?");
    $own->bind_param('i',$blog['IdU']);
    $own->execute();
    $result_own = $own->get_result();
    $own = $result_own->fetch_assoc();
    //query che ricava il nickn del collaboratore del blog ed eventualmente lo stampa a riga 81
    $take_Nickcoll = $conn->prepare("SELECT Nickn FROM `registrato` WHERE IdU=?");
    $take_Nickcoll->bind_param('i',$blog['IdUcollab']);
    $take_Nickcoll->execute();
    $result_coll = $take_Nickcoll->get_result();
    $nickcol = $result_coll->fetch_assoc();
    //query che ricava l' ID dell' argomento principale del blog in cui ci si trova
    $take_idarg = $conn->prepare("SELECT IdT FROM argblog WHERE IdB= ?");
    $take_idarg->bind_param('i',$IdB);
    $take_idarg->execute();
    $result_idarg = $take_idarg->get_result();
    $IdT = $result_idarg->fetch_assoc();
    //query che ricava l'argomento principale del blog in cui ci si trova
    $take_arg = $conn->prepare("SELECT  `Argomento` FROM `tema` WHERE IdT=?");
    $take_arg->bind_param('i',$IdT['IdT']);
    $take_arg->execute();
    $result_arg = $take_arg->get_result();
    $Arg = $result_arg->fetch_assoc();
    //query che ricava l' ID dei sottoargomenti  del blog in cui ci si trova
    $take_idsott = $conn->prepare("SELECT IdSt FROM argblog WHERE IdB= ?");
    $take_idsott->bind_param('i',$IdB);
    $take_idsott->execute();
    $result_idsott = $take_idsott->get_result();
    //PRENDE IL NIKCNAME DEL COLLABORATORE DEL BLOG IN CUI CI SI TROVA
    $take_nick = $conn->prepare("SELECT Nickn FROM registrato, blog WHERE ?=registrato.IdU");
    $take_nick->bind_param('i', $blog['IdUcollab']);
    $take_nick->execute();
    $result_nick = $take_nick->get_result();
    $nickcoll = $result_nick->fetch_assoc();
    //ricava i post del blog in ordine di posting
    $take_posts = $conn->prepare("SELECT * FROM `post` WHERE IdB=? ORDER BY DataP DESC, OraP DESC");
    $take_posts->bind_param('i',$IdB);
    $take_posts->execute();
    $result_post = $take_posts->get_result();
     //conta le righe risultanti dalla precedente query
    $countpost = $result_post-> num_rows;
    //ricava tutti i colori disponibili per lo sfondo
    $take_colorSF = $conn->query("SELECT * FROM sfondo");
     //ricava tutti i colori disponibili per il font
    $take_colorFO= $conn->query("SELECT * FROM font");
    //se l'id dell'utente è settato in sessione (se chi sta visualizzando la pagina è loggato)
    if (isset($_SESSION["IdU"])){
      //query che controlla se l'utente in sessione segue il blog corrente
      $take_follow= $conn->query("SELECT * FROM segue WHERE IdU=$IdUsess AND IdB=$IdB");
      //conta le righe risultanti dalla precedente query
      $existfollow = $take_follow-> num_rows;
    }
}
//altrimenti se l'id del blog passato tramite get non esiste all'interno del database, reindirizza alla pagina iniziale
else header("location: index.php");
?>

<body class="main-color generic_font">
  <div id="page-container">
    <div id="content-wrap">
      <!--barra di navigazione-->
      <header class="pb-5">
        <!--creo div per poter ricavare facilmente il valore dell'id del blog e passarlo alle chiamate ajax e funzioni sulla pagina quando necessario-->
        <div id="IdBlog" value="<?php echo $IdB?>"></div>
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
          <div class="container d-flex justify-content-between">
            <h1 class="navbar-brand">Thoughts</h1>
            <a class="navbar-brand">
              <img src="immagini/logo.png" class="rounded-circle" alt="Logo" style="width:50px;">
            </a>
            <ul class="navbar-nav ml-auto">
              <li class="nav-item">
                <?php 
                //se c'è un utente in sessione 
                if(isset($_SESSION['OK']) && !empty($_SESSION['OK'] )){
                ?>
                  <!--mostro i bottoni utili per l'utente in sessione-->
                  <button type="button" class="btn btn-outline-light  mr-3" onClick="window.location = 'Profile.php'">Torna al Profilo</a></button>
                  <button type="button" class="btn btn-outline-light  mr-3" onClick="window.location = 'Home.php'">Home</a></button>      
                  <button type="button" class="btn btn-outline-light  mr-3" onClick="window.location = 'Cerca.php'">Cerca</a></button>
                  <button type="button" class="btn btn-outline-light" onClick="window.location = 'logout.php'">Logout</a></button>
                <?php 
                }
                //se non ci sono utenti in sessione (utente visitatore)
                else {
                ?>
                  <!--mostro i bottoni utili per l'utente visitatore-->
                  <button type="button" class="btn btn-outline-light" onClick="window.location = 'Home.php'">Home</a></button>
                  <button type="button" class="btn btn-outline-light  mr-3" onClick="window.location = 'Cerca.php'">Cerca</a></button>
                <?php 
                }
                ?>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--necessario per layout: distanzia dal sidenav e permette affiancamento blocchi-->
      <div class="row content mt-5">
        <!--riservo 3/12 di pagina al sidenav che conterrà le informazioni (col--grid system di bootstrap)-->
        <div class="col-sm-3 ml-5 mt-4 sidenav ">
          <!--inizio box a sinistra contenente le informazioni del blog-->
          <div class="pt-8 p-5 bg font-color rounded box-shadow">
            <h4><small>INFORMAZIONI SUL BLOG:</small></h4> 
            <p class="media-body border-bottom border-gray"></p></br>
            <!--stampo nickname proprietario-->
            <h4 class="title-color"> Blog di <b><?php echo $own['Nickn']?></b> 
              <?php 
              //se il blog ha un collaboratore stampo la riga che contiene il suo nickname
              if($blog['IdUcollab']!=null){
              ?> 
                in collaborazione con <b><?php echo $nickcol['Nickn']?></b>
                <?php 
              }
              ?> 
            </h4></br>
            <p class="media-body border-bottom border-gray"></p></br>
            <h4>Questo blog ha come Argomento:</br><b><?php echo $Arg['Argomento']?></b></h4> 
            <p class="media-body border-bottom border-gray"></p></br>
            <h4>Questo blog ha come Sottoargomenti:</br><p><b>
              <?php  
              //ciclo per stampare tutti gli eventuali sottoargomenti del singolo blog
              while ($row = $result_idsott->fetch_assoc()) {
                $IdSt = $row; 
                //se l'id del sottoargomento nella riga corrente è diverso da null
                if ($IdSt['IdSt']!=null){
                    //ricava il "nome" del sottoargomento corrente nel while
                    $take_sottarg = $conn->prepare("SELECT  `sottoargomento` FROM `sottotema` WHERE `IdSt`=?");
                    $take_sottarg->bind_param('i',$IdSt['IdSt']);
                    $take_sottarg->execute();
                    $result_sottarg = $take_sottarg->get_result();
                    $sottarg = $result_sottarg->fetch_assoc();
                    //stampo il sottoargomento concatenandolo a un trattino e vado a capo
                    echo "-".$sottarg['sottoargomento'];
                    echo "</br>"; 
                }
                //altrimenti, se il blog non ha sottoargomenti stampo la riga che lo comunica
                else{ 
                ?> 
                  Questo blog non ha sottoargomenti
                  <?php 
                }
              //chiudo while di riga 153
              }
              ?>
            </b></p></h4>
          <!--chiusura del box riga 133-->
          </div></br>
          <!--faccio visualizzare specifici contenuti agli utenti in sessione in base ai loro ruoli (i visitatori non visualizzano nulla)-->
          <?php 
          //se esiste un utente in sessione
          if(isset($_SESSION['OK']) && !empty($_SESSION['OK'] )){
            //se questo utente in sessione è il proprietario oppure il collaboratore
            if ((($_SESSION['IdU'])==$blog['IdU']) || (($_SESSION['IdU'])==$blog['IdUcollab'])){?>
              <!--mostro i bottoni d'interesse per il proprietario e il collaboratore del blog-->
              <div class="btn-group-vertical ml-4">
                <button type="button"  data-toggle="modal" data-target="#Coll" class="btn btn-primary btn-lg"> Gestione Collaborazioni</button>
                <button type="button" data-toggle="modal" data-target="#BlogMod" class="btn btn-primary btn-lg"> Modifica Blog</button>
                <?php 
                if ((($_SESSION['IdU'])==$blog['IdU'])){?>
                  <button type="button" data-toggle="modal" data-target="#divdelblog" class="btn btn-primary btn-lg" >Elimina Blog</button>
                  <?php 
                } 
                ?>
              </div>
              <?php 
            } 
            //se l'utente loggato non è il proprietario oppure il collaboratore
            else {
              //se l'utente loggato NON ha ancora messo il segui al blog nascondo il bottone che ne permette la rimozione e mostro quello che permette di inserire il segui
              if ($existfollow==0){?>
                <button type="button" id="followb" class="btn btn-primary btn-lg" >Segui questo Blog</button>
                <button type="button" hidden id="unfollowb" class="btn btn-primary btn-lg" >Non Seguire più questo Blog</button>
                <?php 
              }
              //se l'utente loggato ha già messo il segui al blog nascondo il bottone che ne permette l'inserimento e mostro quello che permette di togliere il segui
              else {?>
                <button type="button" id="unfollowb" class="btn btn-primary btn-lg" >Non Seguire più questo Blog</button>
                <button type="button" hidden id="followb" class="btn btn-primary btn-lg" >Segui questo Blog</button>
              <?php 
              }
            }
          }
          ?>
        <!--chiusura sidenav di sinistra-->
        </div> 
        <!--riservo 8/12 di pagina al contenuto concreto del blog (col--grid system di bootstrap)-->
        <div class="col-sm-8">
          <div class="pt-8 p-5 mt-4 mb-5 bg font-color rounded box-shadow">
            <h1 class="text-center"><?php echo $blog['Titolo']?></h1> 
          </div>
          <?php 
          //se c'è un utente in sessione (non un visitatore)
          if(isset($_SESSION['OK']) && !empty($_SESSION['OK'] )){
            //se tale utente è il proprietario o il collaboratore gli mostro il pulsante che permette la creazione di nuovi post
            if((($_SESSION['IdU'])==$blog['IdU']) || (($_SESSION['IdU'])==$blog['IdUcollab'])){?>
              <button type="button" data-toggle="modal" data-target="#Newpost" class="btn btn-primary btn-lg btn-block">Crea nuovo post</button>
              <?php 
            }
          }
          ?>
          <div class="pt-8 p-5 mt-5 ml-5 mr-5 bg font-color rounded box-shadow">
            <h4><b>POST RECENTI</b></h4>
              <p class="media-body border-bottom border-gray"></p></br>
              <?php 
              //se il blog non contiene post stampo la relativa riga
              if ($countpost==0){ 
              ?>
                <p>Questo blog non contiene ancora nessun post.</p>
              <?php 
              }
              //se il blog ha dei post
              else{
                //attraverso il ciclo stampo i singoli post (e relativi "allegati")
                while ($row = $result_post->fetch_assoc()){
                  $Post = $row; 
                  //se l'utente è in sessione (non un visitatore )
                  if (isset($_SESSION['OK']) && !empty($_SESSION['OK'])){
                    //se l'utente in sessione è il collaboratore o il proprietario del blog, mostro il bottone che permette la cancellazione del post corrente
                    if ((($_SESSION['IdU'])==$blog['IdU']) || (($_SESSION['IdU'])==$blog['IdUcollab'])){
                    ?>
                      <form class="delate_post" value="<?php echo $Post['IdP']?>" action="ChosenBlog.php" method="post">
                        <input type="image" class="float-right" alt="delpost" src="immagini/cestino.png" style="width:40px;">
                      </form>
                      <?php 
                    }
                  }
                  ?>
                  <!--stampo il titolo del post-->
                  <h3><?php echo $Post['TitoloP']?></h3>

                  <!--inizio carosello (elemento bootstrap) che permette uno slideshow delle immagini dei singoli post-->
                  <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" style="width:300px;">
                    <div class="carousel-inner">
                    <?php 
                      //ricavo il path delle immagini allegate al post
                      $take_img = $conn->query("SELECT `File_Img` FROM `img` WHERE `Idp`=".$Post['IdP']);
                      //variabile contatore inizializzata a 0
                      $cont = 0;
                      //per ogni immagine (se ce ne sono)
                      while ($Img = $take_img->fetch_assoc()) {
                        //se la variabile contatore ha valore 0 (all'inizio o se non ci sono immagini) alla variabile active viene assegnato il valore "active", altrimenti stringa vuota; ergo, al caricamento della pagina, se ci sono 3 immagini, $active avrà, rispettivamente per ogni immagine, questi valori: "active", "","";
                        $active = $cont == 0 ? "active" : "";
                        //incremento variabile contatore
                        $cont++;
                        ?>
                        <!--se c'è un immagine, questo sarà sempre "active", altrimenti $active alterna il suo valore tra "active" e "" (grazie al meccanismo del carosello) ,cosi che si mostrino le diverse immagini-->
                        <div class="carousel-item <?php echo $active ?>">
                          <!--nell'src stampo il path dell'immagine (contenuto nel db)-->
                          <img class="d-block w-100" src="<?php echo $Img["File_Img"] ?>" alt="First slide">
                        </div>
                        <?php 
                      //Chiusura while riga 270
                      } 
                      ?>
                    <!--chiusura  riga 263-->
                    </div> 
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="sr-only">Next</span>
                    </a>
                  <!--fine carosello-->
                  </div>
                  <!--stampo orario e giorno di pubblicazione post-->
                  <h5><span class="glyphicon glyphicon-time"></span><small>Postato alle ore <?php echo $Post['OraP']?> del giorno <?php echo $Post['DataP']?></small></h5>
                  <!--stampo il testo del post-->
                  <p><?php echo $Post['TestoP']?></p>
                  <?php 
                    //se c'è un utente in sessione (non un visitatore)
                    if(isset($_SESSION['OK']) && !empty($_SESSION['OK'])){
                        $IdPs=$Post["IdP"];
                        //vedo se per il post corrente ci sono like da parte dell'utente in sessione
                        $take_like= $conn->query("SELECT * FROM apprezza WHERE IdU=$IdUsess AND IdP=$IdPs");
                        $existlike = $take_like-> num_rows;
                        //se non esiste un like, mostro il bottone che permette di inserire un like e nascondo quello che permette di rimuoverlo
                        if ($existlike==0){
                          ?>
                          <img class="like" alt="like" src="immagini/notlike.png" style="width:40px;cursor:pointer;" value="<?php echo $Post["IdP"] ?>"/>
                          <img class="dislike" hidden alt="dislike" src="immagini/like.png" style="width:40px;cursor:pointer;" value="<?php echo $Post["IdP"] ?>"/>
                          <?php 
                        }
                        //se esiste, mostro il bottone che permette di rimuovere il like e nascondo quello che permette di inserirlo
                        else {
                          ?>
                          <img class="dislike" alt="dislike" src="immagini/like.png" style="width:40px;cursor:pointer;" value="<?php echo $Post["IdP"] ?>"/>
                          <img class="like" hidden alt="like" src="immagini/notlike.png" style="width:40px;cursor:pointer;" value="<?php echo $Post["IdP"] ?>"/>
                          <?php 
                        } 
                        ?>
                        <!--form per inserire commenti con bottone per submit-->
                        <form class="makecomment" value="<?php echo $Post['IdP']?>" method="post" action="ChosenBlog.php"> 
                          <h5>Lascia un Commento:</h5>
                          <div class="form-group">
                            <textarea class="CommentoInser" class="form-control" rows="3" placeholder="inserisci commento"></textarea>
                          </div>
                          <button type="submit" class="btn btn-success">Commenta</button>
                        </form></br>
                        <?php 
                    }
                    else{
                    ?>
                      <p>Ti piace questo post o vorresti dire la tua a riguardo? <a href="SignUp.php">Iscriviti adesso!</a></p>
                    <?php 
                    }
                  ?>
                  <!--creo div per poter ricavare facilmente il valore dell'id del post e passarlo alle chiamate ajax e funzioni sulla pagina quando necessario-->    
                  <div id="IdPost" value="<?php echo $Post['IdP']?>"></div> 
                  <!--inizio sezione stampa commenti-->  
                  <div class="row">
                    <div class="col-sm-10">
                      <?php
                        //query che recupera tutti i dati utili dei commenti esistenti per il post corrente
                        $take_comm = $conn->prepare("SELECT `IdC`,`IdU`,`TestoC`, `DataC`, `OraC` FROM `commenti` WHERE Idp=?");
                        $take_comm->bind_param('i',$Post['IdP']);
                        $take_comm->execute();
                        $res_comm = $take_comm->get_result();
                        //ciclo che permette di ricavare dati dei singoli commenti e stamparli
                        while ($row = $res_comm->fetch_assoc()) {
                            $comm = $row; 
                            //ricavo il nickname dell'autore del commento corrente
                            $take_autore = $conn->prepare("SELECT `Nickn` FROM `registrato` WHERE IdU=?");
                            $take_autore->bind_param('i',$comm['IdU']);
                            $take_autore->execute();
                            $res_autore = $take_autore->get_result(); 
                            $autore = $res_autore->fetch_assoc();
                            //se c'è un utente in sessione (non un visitatore)
                            if (isset($_SESSION['OK']) && !empty($_SESSION['OK'])){
                              //se l'utente in sessione è il proprietario del blog oppure è il collaboratore del blog oppure è l'autore del commento
                              if ((($_SESSION['IdU'])==$blog['IdU']) || (($_SESSION['IdU'])==$blog['IdUcollab'])||(($_SESSION['IdU'])==$comm['IdU'])){
                              ?>
                                <!--mostro all'utente il bottone che permette la cancellazione del commento corrente-->
                                <form class="delate_comment" value="<?php echo $comm['IdC']?>" action="ChosenBlog.php" method="post">
                                  <input type="image" class="float-right" alt="delcomm" src="immagini/cestino.png" style="width:25px;">
                                </form>
                                <?php 
                              }
                            }
                            ?>
                            <!--stampo i dati del singolo commento: autore, data e ora della creazione e il testo-->
                            <h4><small>Commento di </small> <b><?php echo $autore['Nickn']?></b>:</h4>
                            <small>Postato alle ore <?php echo $comm['OraC']?> del giorno <?php echo $comm['DataC']?></small>
                            <p><medium><?php echo $comm['TestoC']?></medium></p></br>
                            <?php
                        //chiusura ciclo while riga 348 (while che cicla i commenti di un post)
                        }
                      ?>
                    </div>
                  <!--fine sezione stampa commenti-->
                  </div>   
                  <p class="media-body mt-5 border-bottom border-gray"></p></br>
                  <?php 
                //chiusura while riga 245 (while che cicla i post di un blog)
                }
              //chiusura else riga 243
              }
              ?> 
          <!--chiusura div riga 232-->
          </div>
        <!--chiusura div riga 218-->
        </div>
      <!--chiusura div riga 129-->
      </div>
       
      <!--INIZIO SEZIONE MODALS-->

      <!--MODAL FORM MODIFICA BLOG-->
        <div class="modal" id=BlogMod>
          <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title">Modifica il tuo blog</h4> 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
                <!-- Modal body -->
              <div class="modal-body"> 
                <form id="BlogMod" method="post" action="ChosenBlog.php"> 
                  <div class="form-group">
                    <label for="Blogtitle">Titolo Blog:</label>
                    <!--stampo il titolo corrente del del blog-->
                    <textarea type="text" class="form-control" id="Blogtitle" name="Blogtitle" placeholder= "Inserisci il nuovo titolo del tuo blog" rows='1'><?php echo $blog["Titolo"];?></textarea>
                  </div>
                  <label for="sfondocolor">Scegli un colore di sfondo:</label>
                  <select id="sfondocolor" name="sfondocolor">
                    <!--stampo il colore corrente dello sfondo del blog-->
                    <option value="<?php echo $blog['IdSf']?>"><?php echo $actcolorSF['Sfondo']?></option>
                    <?php   
                      //tramite ciclo stampo una riga di option per ogni colore, dove il value è l'id e stampo il colore (contenuto nel db)
                      while ($row = $take_colorSF->fetch_assoc()) {
                          $colorSf = $row; 
                      ?>
                          <option value="<?php echo $colorSf["IdSf"] ?>"><?php echo $colorSf["Sfondo"] ?></option>
                      <?php  
                      } 
                      ?>
                  </select> </br>
                  <label for="dimfont">Scegli il colore del font:</label>
                  <select id="dimfont" name="dimfont">
                    <!--stampo il colore corrente dello sfondo del blog-->
                    <option value="<?php echo $blog['IdFo']?>"><?php echo $actcolorFO['Font']?></option>
                    <?php
                    //tramite ciclo stampo una riga di option per ogni colore, dove il value è l'id e stampo il colore (contenuto nel db)
                    while ($row = $take_colorFO->fetch_assoc()) {
                        $colorFo = $row; 
                    ?>
                        <option value="<?php echo $colorFo["IdFo"] ?>"><?php echo $colorFo["Font"] ?></option>
                    <?php  
                    } 
                    ?>
                  </select> </br>
                  <div class="form-group">
                    <button type="submit" name="modifica" id="modifica" class="btn btn-primary btn-block" >Modifica</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      <!--FINE MODAL MODIFICA BLOG-->

      <!--MODAL PER CREAZIONE POST-->
        <div class="modal" id=Newpost>
          <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title">Crea un nuovo post</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
                <!-- Modal body -->
              <div class="modal-body"> 
                <!--necessario specificare l'enctype multipart per permettere il caricamento di file-->
                <form id="Newpost" method="post" action="Chosenblog.php" enctype="multipart/form-data"> 
                  <div class="form-group">
                    <label for="PosTitle">Titolo Post:</label>
                    <input type="title" class="form-control" id="PosTitle" name="PosTitle" placeholder="Inserisci il titolo del tuo post" >
                  </div>
                  <div class="form-group">
                    <label for="PostText">Contenuto del Post:</label>
                    <input type="title" class="form-control" id="PosText" name="PosText" placeholder="Inserisci il testo del tuo post" >
                  </div>
                  <div class="form-group">
                    <label for="PostImg">Inserisci da 0 a 3 immagini per il tuo post:</label>
                    <p><small>*puoi caricare <b>solo</b> file immagine, tutti gli altri tipi di file non saranno caricati assieme al tuo nuovo post!</small></p>
                    <input class="overflow_hidden" type="file" accept="image/*" name="image1[]" id="image1" name="image1" >
                    <input class="overflow_hidden" type="file" accept="image/*" name="image2[]" id="image2" name="image2" >
                    <input class="overflow_hidden" type="file" accept="image/*" name="image3[]" id="image3" name="image3" >
                  </div>
                  <div class="form-group">
                    <button type="submit" name="crea" id="crea" class="btn btn-primary btn-block">Crea</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      <!--FINE MODAL crea blog-->

      <!--INIZIO MODAL GESTIONE COLLABORAZIONI-->
        <div class="modal" id=Coll>
          <div class="modal-dialog">
            <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                  <h4 class="modal-title">Gestione Collaborazioni</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <!-- Modal body -->
              <div class="modal-body"> 
                <form id="Coll" method="post" action="Chosenblog.php"> 
                  <div class="form-group">
                    <label for="Collnow">Collaborazioni attive su questo Blog:</label>
                    <?php
                    //se il blog non ha un collaboratore stampo il relativo paragrafo e dispongo un form per l'inserimento di un collaboratore con relativo bottone di submit 
                    if ($blog['IdUcollab']==null){ 
                    ?>
                      <p> Non hai collaborazioni attive al momento su questo Blog!</br> Se vuoi attivarla inserisci qui in nickname del tuo futuro collaboratore!</p>
                      <input type="title" class="form-control" id="newcoll" name="newcoll" placeholder="Inserisci un nickname"></br>
                      <div class="form-group">
                        <button id="insert" type="button" class="btn btn-primary btn-block">Nominalo collaboratore per questo blog</button>
                      </div>
                      <?php 
                    } 
                    //altrimenti se il blog ha un collaboratore stampo il relativo paragrafo e dispongo un bottone per la rimozione del collaaboratore corrente
                    else{ 
                    ?>
                      <p><b> <?php echo $nickcoll['Nickn'];?></b> è il  collaboratore di questo blog al momento. </br></p>
                      <button type="submit" name="cancel" id="cancel" class="btn btn-primary btn-block" >Rimuovi attuale collaboratore</button> 
                      <?php 
                    }
                    ?>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!--Fine modal gestione collaborazioni-->

        <!--INIZIO MODAL ELIMINA BLOG-->
        <div class="modal" id=divdelblog>
          <div class="modal-dialog">
            <div class="modal-content">
            <!-- Modal Header -->
              <div class="modal-header">
                  <h4 class="modal-title">Elimina blog</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
                  <!-- Modal body -->
              <div class="modal-body"> 
                <form id="formdelblog" method="post" action="Chosenblog.php"> 
                  <div class="form-group">
                    <?php 
                    //se il blog ha un collaboratore stampo il relativo paragrafo di avvertimento e dispongo il bottone per eliminare(se stesso dal blog==cederne i diritti al collaboratore) il blog
                    if ($blog['IdUcollab']==null){ 
                    ?>
                      <p> Non hai collaborazioni attive al momento su questo blog quindi questo verrà completamente rimosso dai nostri sistemi. </br> Se vuoi che la proprietà venga ereditata da un altro utente, vai nella sezione <b>Gestione Collaborazioni</b> e inserisci un collaboratore prima di cancellare il blog, altrimenti prosegui.</p>
                      <button id="delblog" type="button" class="btn btn-primary btn-block" >Cancella il blog</button>
                      <?php 
                    }
                    //altrimenti se il blog non ha un collaboratore stampo il relativo paragrafo di avvertimento e dispongo il bottone per eliminare definitivamente il blog dal sito
                    else { 
                    ?>
                      <p> <b><big>ATTENZIONE!</big></b> In questo momento sul blog c'è una collaborazione attiva con <b><?php echo $nickcoll['Nickn'];?></b> quindi <b>il collaboratore erediterà il possesso del tuo blog</b>. </br>Se vuoi <b>completamente eliminare</b> il blog assicurati prima di rimuovere il collaboratore dalla sezione <b>Gestione Collaborazioni</b></p>
                      <button id="delblog" type="button" class="btn btn-primary btn-block" >Cedi i diritti a <?php echo $nickcoll['Nickn'];?> </button> 
                      <?php 
                    }
                    ?>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>