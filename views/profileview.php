<?php
//estraggo i dati dal db che poi
  global $conn;
  $n= 0;
  //se è settato il valore OK all'interno di $_session e se l'array superglobale $_SESSION non è vuoto (ovvero se qualcuno è loggato)
  if(isset($_SESSION['OK']) && !empty($_SESSION['OK'])) {
      //salvo l'id dell'utente loggato in una variabile
      $IdUs= $_SESSION['IdU'];
      //query seleziona tutti i blog creati direttamente dal proprietario (utente in sessione)
      $take_blogs = $conn->prepare("SELECT * FROM blog WHERE IdU= ? AND Ereditato IS NULL");
      $take_blogs->bind_param('i', $IdUs);
      $take_blogs->execute();
      $result_blogs = $take_blogs->get_result();
      $countblogs= $result_blogs-> num_rows;
      //query seleziona tutti i blog  EREDITATI da altri utenti che hanno ceduto i diritti all'utente in sessione (ex-collaboratore)
      $take_blogsER = $conn->prepare("SELECT * FROM blog WHERE IdU= ? AND Ereditato='SI'");
      $take_blogsER->bind_param('i', $IdUs);
      $take_blogsER->execute();
      $result_blogsER = $take_blogsER->get_result();
      $countblogsER= $result_blogsER-> num_rows;
      //QUERY CHE PRENDE TUTTI GLI ARGOMENTI DALLA TABELLA DEGLI ARGOMENTI
      $result_argomento = $conn->query("SELECT * FROM tema");
      //ricava tutti i colori disponibili per lo sfondo
      $take_colorSF = $conn->query("SELECT * FROM sfondo");
       //ricava tutti i colori disponibili per il font
      $take_colorFO= $conn->query("SELECT * FROM font");
     //query seleziona tutti i dati di un/vari blog in cui l'id del collaboratore corrisponde a  quello in sessione
      $take_collab = $conn->prepare("SELECT * FROM `blog` WHERE IdUcollab=?");
      $take_collab->bind_param('i', $IdUs);
      $take_collab->execute();
      $result_collab = $take_collab->get_result();
      $countblogcoll= $result_collab-> num_rows;
      //query che seleziona tutti i dati dell'utente in sessione
      $take_data = $conn->prepare("SELECT * FROM registrato WHERE IdU=?");
      $take_data->bind_param('i', $IdUs);
      $take_data->execute();
      $result_data = $take_data->get_result();
      $user = $result_data->fetch_assoc();
      //query che seleziona gli ID dei blog seguiti dall'utente in sessione
      $take_follow =$conn->prepare("SELECT * FROM segue WHERE IdU=?");
      $take_follow->bind_param('i', $IdUs);
      $take_follow->execute();
      $result_follow = $take_follow->get_result();
      $countfollow = $result_follow-> num_rows;
  }
  //se non è loggato nessuno (non è settata la session) reindirizzo alla pagina iniziale
  else header("location: index.php");
?>
<body class="generic_font generic_bg">
  <!--div necessari per far andare il footer infondo alla pagina-->
  <div id="page-container">
    <div id="content-wrap">
      <!--header che contiene la barra di navigazione-->
      <header class="pb-5">
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
          <div class="container d-flex justify-content-between">
            <h1 class="navbar-brand">Thoughts</h1>
            <a class="navbar-brand">
              <img src="immagini/logo.png" class="rounded-circle" alt="Logo" style="width:50px;">
            </a>
            <ul class="navbar-nav ml-auto">
              <li class="nav-item">
                <button type="button" class="btn btn-outline-light mr-3" onClick="window.location = 'Home.php'">Home</a></button>
                <button type="button" class="btn btn-outline-light mr-3" onClick="window.location = 'Cerca.php'">Cerca</a></button>      
                <button type="button" class="btn btn-outline-light" onClick="window.location = 'logout.php'">Logout</a></button>         
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--primo box che contiene il nome-cognome dell'utente e i blog che crea, possiede e segue-->
      <div class="p-5 mt-5 ml-5 mr-5 bg-white rounded box-shadow">
        <h2 id="logged"> Area Personale di <?php echo $user["Nome"]; ?> <?php echo $user["Cognome"]; ?></h2></br>

        <!--sezione di stampa dei blog CREATI dall'utente in sessione-->
        <h3 >I tuoi blog</h3>
        <?php 
          //se la variabile che contiene il numero di blog CREATI ("ereditato" ha valore null) dell'utente in sessione è diverso da 0
          if ($countblogs!=0){ 
            //tramite ciclo faccio la stampa dei titoli dei suoi blog e tramite herf e metodo get (tramite la stampa dell'id) creo un link che porti allo specifico blog
            while ($row = $result_blogs->fetch_assoc()) {
              $blog = $row; 
              //incremento la variabile contatore ad ogni stampa di titolo/link al blog
              ++$n
              ?>
              <div class="media text-muted pt-3" id="blog">
                <p class="media-body  border-bottom border-gray">
                     <a href="ChosenBlog.php?idB=<?php echo $blog['IdB'];?>"><?php echo $blog['Titolo'];?> </a>
                </p>
              </div>
            <?php  
            }
          } 
          //se la variabile che contiene il numero di blog CREATI dell'utente in sessione è uguale a 0 stampo il messaggio di riga 99
          else { ?>
           <div class="media text-muted pt-3">
              <p class="media-body  border-bottom border-gray">Non hai ancora creato nessun blog, cosa aspetti?</p>
            </div>
          <?php 
          }      
          /*controllo se l'utente possiede blog ereditati ed eventualmente stampo la sezione inerente all'eredità con il/i titolo/i+link (con la stessa metodologia usata nel while precedente)
          se la variabile che contiene il numero di blog EREDITATI ("ereditato" ha valore "SI") dell'utente in sessione è diverso da 0*/
          if ($countblogsER!=0){ ?>
            <h4><small>Blog ereditati da altri utenti</small></h4>
            <?php 
              while ($row = $result_blogsER->fetch_assoc()) {
                $blogER = $row; 
                ?>
                <div class="media text-muted pt-3">
                  <p class="media-body  border-bottom border-gray">
                    <a href="ChosenBlog.php?idB=<?php echo $blogER['IdB'];?>"><?php echo $blogER['Titolo'];?> </a>
                  </p>
                </div>
                <?php  
              }
          }
           /*STAMPA DEL BOTTONE "CREA BLOG": controllo che l'utente in sessione non abbia raggiunto il massimo dei blog CREABILI tramite un controllo sulla variabile contatore "n". Quando avrà raggiunto il valore di 5, il bottone gli verrà nascosto tramite la funzione jquery hide. 
           Questo meccanismo è reso possibile dal fatto che ogni volta che un utente crea un blog viene reindirizzato al blog appena creato, ergo se dovesse tornare su questa pagina, questa verrà ricaricata, il valore di n sarà quello reale e il bottone sparirà appena sarà raggiunto il massimo numero di blog creabili*/  
          if ($n<5){ ?>
            <button id="crea" type="button" class="btn btn-info" data-toggle="modal" data-target="#Blogmodal">Crea un nuovo blog!</button> 
            <?php 
          }
          else{?>
            <!--la funzione jquery hide prende in oggetto l'elemento del DOM con id "crea" (il bottone) e lo nasconde-->
            <script type="text/javascript">$("#crea").hide();</script>
            <?php
          }
          ?>

          <!--sezione collaborazioni con blog altrui-->
          <p><h3 >Blog a cui collabori</h3></p>
          <?php 
          if ($countblogcoll!=0){
            while ($row = $result_collab->fetch_assoc()) {
              $blogcoll=$row;
              //se è stato settato un valore per il titolo del/dei blog di cui l'utente in sessione è collaboratore (ovvero se lui è collaboratore di almeno un blog) stampo il titolo del blog/link al blog usando la metodologia già vista in precedenza
              ?>
              <div class="media text-muted pt-3">
                <p class="media-body  border-bottom border-gray">
                  <a href="ChosenBlog.php?idB=<?php echo $blogcoll['IdB'];?>"><?php echo $blogcoll['Titolo'];?></a>
                </p>
              </div>
              <?php   
            }
          }
          //se l'utente loggato/in sessione non è collaboratore di nulla, stampo il relativo messaggio
          else { ?>
            <div class="media text-muted pt-3">
              <p class="media-body  border-bottom border-gray"> Non sei ancora collaboratore di nessun blog.</p>
            </div>
            <?php 
          }?>

          <!--sezione follow di  blog altrui-->
          <p><h3 >Blog che segui</h3></p>
          <?php 
          //se la variabile che contiene il conto dei blog seguiti dall'utente in sessione è diverso da 0
          if ($countfollow!=0){ 
            //ciclo i risultati della query che estraeva gli id dei blog che segue l utente in sessione
            while ($row = $result_follow->fetch_assoc()) {
                //salvo in una variabile il valore dell'id del blog corrente
                $follow=$row;
                //tramite query ricavo il titolo del blog corrente
                $take_Bfollow = $conn->prepare("SELECT Titolo FROM `blog` WHERE IdB=?");
                $take_Bfollow->bind_param('i', $follow['IdB']);
                $take_Bfollow->execute();
                $result_Bfollow = $take_Bfollow->get_result();
                $Bfollow = $result_Bfollow->fetch_assoc();
                ?>
                <div class="media text-muted pt-3">
                  <p class="media-body  border-bottom border-gray"> 
                    <!--stampo i titoli/link dei blog che segue l'utente in sessione-->
                    <a href="ChosenBlog.php?idB=<?php echo $follow['IdB'];?>"><?php echo $Bfollow['Titolo'];?></a> </br>
                  </p>
                </div>
                <?php  
            }
          }
          //se il conto dei blog seguiti dall'utente in sessione è uguale a 0 stampo il relativo messaggio
          else { ?>
             <div class="media text-muted pt-3">
                <p class="media-body  border-bottom border-gray"> 
                  Non segui ancora nessun blog.
                </p>
              </div>
            <?php 
          }
         ?>    
      </div> <!--chiusura primo box che si apre a riga 72--> 

      <!--apertura secondo box che contiene i dati dell'utente e i bottoni per modificare dati e password-->
      <div class="mt-4 p-5 ml-5 mr-5 bg-white rounded box-shadow">
        <h3 >I tuoi dati</h3> 
        <div class="media-body border-bottom border-gray">
          <p id='nickname'> Nickname: </br> <?php echo $user["Nickn"] ?> </p>
        </div>
        <div class="media-body border-bottom border-gray">
          <p id='nickname'> Nome: </br> <?php echo $user["Nome"] ?> </p>
        </div>
        <div class="media-body border-bottom border-gray">
          <p id='nickname'> Cognome: </br> <?php echo $user["Cognome"] ?> </p>
        </div>
        <div class="media-body border-bottom border-gray">
          <p id='email'> E-mail: </br> <?php echo$user["Email"] ?> </p>
        </div>
        <div class="media-body border-bottom border-gray">
          <p id='cellulare'> Cellulare: </br> <?php echo $user["Tel"] ?>  </p>
        </div>
        <div class="media-body border-bottom border-gray">
          <p id='documento'> Documento: </br> <?php echo $user["DocuIde"] ?> </p>
        </div>
        <div class="media-body border-bottom border-gray">
          <p id='nascita'> Età: </br> <?php echo $user["Eta"] ?> </p>
        </div>
        <?php 
          $gender;
          //nel database sesso è un valore booleano; ho scelto che lo 0 rappresenta l'uomo e l'1 la donna. Con questo if "converto" il valore nel database con il suo equivalente in linguaggio naturale.
          if($user["Sesso"]==1){
            $gender="Femmina";
          }
          else {
            $gender="Maschio";
          }
        ?>
        <div>
          <!--stampo la variabile calcolata nell'if di riga 218-224-->
          <p id='sesso'> Sesso: </br> <?php echo $gender ?> </p></br>
          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#Modinfo">Modifica i tuoi dati</button>
          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#ModPass">Modifica la tua password</button>
        </div>
      </div> <!--chiusura secondo box che si apre a riga 191-->

      <!--apertura terzo box che contiene il bottone per eliminare il blog-->
      <div class="mt-4 p-5 ml-5 mr-5 bg-white rounded box-shadow">
        <h3 >Il tuo account</h3>
        <p>*<small>se decidi di cancellare il tuo account tutti i tuoi blog che hanno un collaboratore verranno ereditati da questo.</br> Se desideri cancellarli definitivamente rimuovi i collaboratori dai tuoi blog e poi prosegui.</small></p>
        <button id="DelAcc" type="button" class="btn btn-info" >Elimina il tuo account</a></button>
      </div><!--chiusura box che si apre a riga 231-->

      <!--INIZIO SEZIONE MODALS: contiene tutti i modal della pagina Profile.php-->

      <!-- INIZIO modal per creare blog-->
      <div class="modal" id=Blogmodal>
        <div class="modal-dialog">
          <div class="modal-content">
              <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Crea un nuovo blog</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
              <!-- Modal body -->
            <div class="modal-body"> 
              <form id="Blogmodal" method="post" action="Profile.php"> 
                <div class="form-group">
                  <label for="Blogtitle">Titolo Blog:</label>
                  <input type="title" class="form-control" id="Blogtitle" name="Blogtitle" placeholder="Inserisci il titolo del tuo blog" >
                </div>
                <div class="form-group">
                  <label for="argomento">Argomento del tuo blog:</label> </br>
                  <select id="argomento" name="argomento">
                    <option value="" disabled selected>Seleziona argomento</option>
                    <?php 
                        //stampa vari argomenti (seconda query delle iniziali)
                      while ($row = $result_argomento->fetch_assoc()) {
                        $argomento = $row; 
                    ?>
                        <option value="<?php echo $argomento["IdT"] ?>"><?php echo $argomento["Argomento"] ?></option>
                    <?php  
                      } 
                    ?>
                  </select></br>
                </div>
                <div class="form-group">
                  <label for="Blogsottema">Sottoargomento/i* del tuo Blog:</label>
                  <p><small>*scelta opzionale</small></p>
                    <div id="div_sottoargomento" class="col-md-12">
                    </div>
                </div>
                <label for="sfondocolor">Scegli un colore di sfondo:</label>
                <select id="sfondocolor" name="sfondocolor">
                  <option value="" disabled selected>Seleziona un colore di sfondo</option>
                  <?php   
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
                  <option value="" disabled selected>Seleziona un colore di font</option>
                   <?php
                    while ($row = $take_colorFO->fetch_assoc()) {
                          $colorFo = $row; 
                      ?>
                          <option value="<?php echo $colorFo["IdFo"] ?>"><?php echo $colorFo["Font"] ?></option>
                      <?php  
                        } 
                      ?>
                </select>
                <div class="form-group">
                  <button type="submit" name="crea" id="crea" class="btn btn-primary btn-block" >Crea</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    <!--FINE modal crea blog-->
    <!-- INIZIO modal per modificare info-->
    <div class="modal" id=Modinfo>
      <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Modifica i tuoi dati</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
            <!-- Modal body -->
          <div class="modal-body"> 
            <form id="Modinfo" method="post" action="Profile.php"> 
              <div class="form-group">
                <label for="datanick">nickname:</label>
                <textarea type="text" class="form-control" id="datanick" name="datanick" placeholder= "inserisci nuovo nickname" rows='1'><?php echo $user["Nickn"];?></textarea>
              </div>
              <div class="form-group">
                <label for="dataname">nome:</label>
                <textarea type="text" class="form-control" id="dataname" name="dataname" placeholder= "inserisci nuovo nome" rows='1'><?php echo $user["Nome"];?></textarea>
              </div>
              <div class="form-group">
                <label for="datalastn">cognome:</label>
                <textarea type="text"class="form-control" id="datalastn" name="datalastn" placeholder= "inserisci nuovo cognome" rows='1'><?php echo $user["Cognome"];?></textarea>
              </div>
              <div class="form-group">
                <label for="datage">età:</label>
                <textarea type="text" class="form-control" id="datage" name="datage" placeholder= "inserisci nuova età" rows='1'><?php echo $user["Eta"];?></textarea>
              </div>
              <div class="form-group">
                <label for="datatel">numero di telefono:</label>
                <textarea type="text" class="form-control" id="datatel" name="datatel" placeholder= "inserisci nuovo numero" rows='1'><?php echo $user["Tel"];?></textarea>
              </div>
              <div class="form-group">
                <label for="datadoc">documento:</label>
                <textarea type="text" class="form-control" id="datadoc" name="datadoc" placeholder= "inserisci nuovo numero documento" rows='1'><?php echo $user["DocuIde"];?></textarea>
              </div>
              <p>Sesso:
              <select id="gender" class="sesso">
                  <?php 
                  //vale stessa spiegazione di riga 217
                  if ( $user['Sesso']==0){
                    $sesso="Maschio";
                  }
                  else {$sesso="Femmina";
                  }?>
                    <option value="<?php echo $user['Sesso']?>"><?php echo $sesso?></option>
                    <option value="0">Maschio</option>
                    <option value="1">Femmina</option>
              </select> </p>
              <div class="form-group">
                <button type="submit" name="modifica" id="modifica" class="btn btn-primary btn-block" >Modifica</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!--FINE modal per modificare info-->
    <!--INIZIO modal per modificare password-->
    <div class="modal" id=ModPass>
      <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Modifica la tua password</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
            <!-- Modal body -->
          <div class="modal-body"> 
            <form id="ModPass" method="post" action="Profile.php"> 
              <div class="form-group">
                <label for="oldpass">Inserisci la tua vecchia password:</label>
                <input type="text" class="form-control" id="oldpass" name="oldpass" placeholder= "inserisci la vecchia password">
              </div>
              <div class="form-group">
                <label for="newpass">Inserisci la tua nuova password:</label>
                <input type="text" class="form-control" id="newpass" name="newpass" placeholder= "inserisci la nuova password">
              </div>
              <div class="form-group">
                <label for="checknew">Reinserisci tua nuova password:</label>
                <input type="text" class="form-control" id="checknew" name="checknew" placeholder= "reinserisci la nuova password">
              </div>
              <div class="form-group">
                <button type="submit" name="modifica" id="modifica" class="btn btn-primary btn-block" >Modifica</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!--FINE modal per modificare password-->