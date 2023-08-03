<?php
//se c'è un utente in sessione riporto alla pagina home
if(isset($_SESSION['OK']) || !empty($_SESSION['OK'])) {
  header("location: index.php");
}
?>
<body class="generic_font generic_bg">
  <div id="page-container">
    <div id="content-wrap">
      <!--barra di navigazione-->
      <header class="pb-5"> 
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
          <div class="container d-flex justify-content-between">
            <h1 class="navbar-brand">Thoughts</h1>
            <img src="immagini/logo.png" class="rounded-circle" alt="Logo" style="width:40px;">
            <ul class="navbar-nav ml-auto">
              <li class="nav-item">
                <button type="button" class="btn btn-outline-light" onClick="window.location = 'Home.php'">Home</a></button>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--elemento di bootstrap: container--> 
      <div class="container-sm">
        <!--i dati contenuti nel form con id formsignup saranno inviati tramite metodo post alla pagina signup.php (dopo esecuzione chiamata ajax(file my_script.js) su medesimo id)-->
        <form id="formsignup" method="post" action="SignUp.php">
         <div class="form-group mt-5" align="center">
            <!--per il nickname esiste un bottone per contollare la disponibilità (ajax) del nickname scelto prima del submit-->
            <label for="name" ><b>NickName:</b></label>
            <input type="text" class="form-control mb-2" name="nik" id="nik" placeholder="Inserisci il tuo nickname e verificane la disponibilità*" required>
            <button id="validate_nik" class="btn btn-primary btn-block">controlla disponibilità </button>
            <p><small>*Se non ti va di verificare non ti preoccupare, lo faremo noi al posto tuo!</small></p>

            <label for="name" ><b>Nome:</b></label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Inserisci il tuo nome"required >

            <label for="lastname"><b>Cognome:</b></label>
            <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Inserisci il tuo cognome" required >

            <label for="age"><b>Età:</b></label>
            <input type="text" class="form-control" name="age" id="age" placeholder="Inserisci la tua età" required>

            <label for="email" ><b>Email:</b></label>
            <input type="text" class="form-control" name="email" id="email" placeholder="Inserisci la tua email" required>

            <label for="name" ><b>Telefono:</b></label> 
            <input type="text" class="form-control" name="telef" id="telef" placeholder="Inserisci il tuo numero di telefonia mobile" required>
             <p><small>*Sono accettati solo numeri di telefonia mobile.</small></p>

            <label for="name" ><b>Numero di Documento d'Identità*:</b></label>
            <input type="text" class="form-control" name="docu" id="docu" placeholder="Inserisci il tuo numero di documento" required>
            <p><small>*Sono accettate: carta d'identità, passaporto o patente.</small></p>

            <label for="psw"><b>Password:</b></label>
            <input type="password" class="form-control" name="psw" id="psw" placeholder="Inserisci la tua password"required>
            <p><small>*La password deve contenere un carattere maiuscolo, una cifra ed essere lunga minimo 6 caratteri e massimo 11 caratteri.</small></p>

            <label for="ctrpsw"><b>Reinserisci la password:</b></label>
            <input type="password" class="form-control" name="ctrpsw" id="ctrpsw"placeholder="Reinserisci la tua password" required>

            <p><b>Sesso:</b></p>
            <select class="sesso">
                  <option selected="true" disabled="disabled">Seleziona</option>
                  <option value="0">Maschio</option>
                  <option value="1">Femmina</option>
            </select>
            <div class="form-group mt-5">
              <!--bottone del submit-->
              <button id="enter" class="btn btn-primary btn-block" type="submit">Accedi</button>
            </div>
          </div>
        </form>
      </div>
