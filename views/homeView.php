<body class="generic_font generic_bg">
  <div id="page-container">
    <div id="content-wrap">
      <!--barra di navigazione-->
      <header>
        <div class="navbar navbar-light">
          <div class="container d-flex justify-content-between">
            <h4><strong>Thoughts</strong></h4>
            <a class="navbar-brand">
              <img src="immagini/logo.png" class="rounded-circle" alt="Logo" style="width:70px;">
            </a>
            <ul class="navbar-nav ml-auto">
              <li class="nav-item">
                <?php
                //se c'è un utente in sessione 
                if(isset($_SESSION['OK']) && !empty($_SESSION['OK'])) {
                ?>
                  <!--mostro i bottoni utili per l'utente in sessione-->
                  <button type="button" class="btn btn-secondary  mr-3" onClick="window.location = 'Profile.php'">Torna al Profilo</a></button>
                  <button type="button" class="btn btn-secondary" onClick="window.location = 'logout.php'">Logout</a></button>
                <?php
                }
                //se l'utente non è loggato (visitatore)
                else{
                ?>
                  <!--mostro i bottoni utili per l'utente visitatore-->
                  <button type="button" class="btn btn-secondary btn-lg mr-3" data-toggle="modal" data-target="#myModal">Login</button>
                  <button type="button" class="btn btn-secondary btn-lg" onClick="window.location = 'SignUp.php'">Registrati</a></button>
                <?php
                  }
                ?>
              </li>
            </ul>
          </div>
        </div>
      </header>
      <!--sezione centrale pagina home con bottone di ricerca (jumbotron: componente bootstrap)-->
      <section class="jumbotron text-center">
        <div class="container">
          <h1 class="jumbotron-heading">Benvenuto su Thoughts!</h1>
          <p class="lead text-muted">Scrivi tutto ciò che ti viene in mente o cerca opinioni interessanti!</p>
          <form class="pl-4";>
            <button  type="button" class="btn btn-success btn-lg" onClick="window.location = 'Cerca.php'">Cerca</button>
          </form>
        </div>
      </section>
      <!--sezione inferiore pagina home con le stampe dei 9 post con più like del sito (album classe bootstrap)-->
      <div class="album py-5 green_home">
        <div class="container">
          <h2 class="text-center"> I post più amati di thoughts!</h2></br>
          <div class="row">
            <?php 
            global $conn;
            //recupero dalla vista le prime 9 righe (sono già ordinate in modo discendente in base ai like)
            $take_postlikes= $conn->query("SELECT * FROM post_like LIMIT 9");
            //ciclo i risultati (singoli post con info sul blog, creatore, collaboratore, likes)
            while ($row = $take_postlikes->fetch_assoc()) {
              $Post=$row;
              $IDproprietario=$Post['IDProp'];
              //se esiste un collaboratore per il blog a cui appartiene il post corrente
              if(isset($Post['IDCollab'])){
                $IDcollaboratore=$Post['IDCollab'];
                //ricavo il nickname del collaboratore
                $take_nickcoll= $conn->query("SELECT Nickn FROM registrato WHERE registrato.IdU= $IDcollaboratore");
                $Nickcoll= $take_nickcoll->fetch_assoc();
              }
              //ricavo il nickname del proprietario
              $take_nickprop= $conn->query("SELECT Nickn FROM registrato WHERE registrato.IdU=$IDproprietario");
              $Nickpropr= $take_nickprop->fetch_assoc();
              ?>
              <!--stampo i singoli post all'interno di cards (componente bootstrap)-->
              <div class="col-md-4">
                <div class="card h-100 mb-4 box-shadow">
                  <div class="card-body">
                    <!--stampo il titolo del post-->
                    <h4 class="card-text"><?php echo $Post['TitoloP']?></h4>
                    <!--stampo il testo del post-->
                    <p class="card-text"><?php echo $Post['Testo']?></small></br>
                    <div class="align-items-center">
                      <!--stampo il numero di like del post-->
                      <small><p><b><?php echo $Post['nLike']?> mi piace</b></p></small> 
                      <!--stampo il titolo/link del blog-->
                      <medium><p>Post dal blog:<a class="blog_link"href="ChosenBlog.php?idB=<?php echo $Post['Blog']?>" class="nav-link">
                        <h5 class="card-title">"<?php echo $Post['TitoloB']?>"</h5>
                        </a> gestito da <b><?php echo $Nickpropr['Nickn']?></b>
                        <?php
                        //se esiste un collaboratore per il blog a cui appartiene il post corrente stampo la riga corrispondente
                        if (isset($Post['IDCollab'])){?>
                          in collaborazione con <b><?php echo $Nickcoll['Nickn']?></b>
                        <?php }
                        ?>
                      </p></medium> 
                    </div>
                  </div>
                  <!--fine card-->
                </div>
                <!--fine div riga 71-->
              </div>
            <!--fine ciclo stampa post-->
            <?php
            }
            ?>
          <!--chiusura div row riga 50-->
          </div>
        <!--fine container riga 48-->
        </div>
        <!--bottone per tornare in cima alla pagina-->
        <div class="container mt-3">
          <p class="text-center">
            <a href="#" class="button_link">Torna in cima alla pagina</a>
          </p>
        </div>
      <!--fine album riga 47-->
      </div>
      <!--SEZIONE MODALS-->
      <!--modal di login-->
      <div class="modal" id="myModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Login</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
              <form id="form_login" method="post" action="Home.php">
                <div class="form-group">
                  <label for="EmailInserita">Email:</label>
                  <input type="email" class="form-control" id="EmailInserita" name="EmailInserita" placeholder="Inserisci email" >
                </div>
                <div class="form-group">
                  <label for="PswInserita">Password:</label>
                  <input type="password" class="form-control" id="PswInserita" name="PswInserita" placeholder="Inserisci password" required>
                </div>
                <div class="form-group">
                  <button type="submit" name="login" id="login" class="btn btn-primary btn-block" >Accedi</button>
                </div>
              </form>
            <P>Non sei ancora iscritto? allora <a href="SignUp.php">Registrati!</a></P>
            </div>
          </div>
        </div>
      </div>
