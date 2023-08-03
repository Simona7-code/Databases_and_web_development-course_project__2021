<?php
global $conn;
//query che recupera tutti i dati degli argomenti dalla tabella che contiene gli argomenti
$take_argomento = $conn->query("SELECT * FROM tema");      
?>
<body class="generic_font generic_bg">
	<div id="page-container">
   		<div id="content-wrap">
   			<!--barra di navigazione-->
		 	<header class="pb-5">
				<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
					<div class="container d-flex justify-content-between">
				  		<h1 class="navbar-brand">Thoughts</h1>
				  		<a class="navbar-brand">
					        <img src="immagini/logo.png" class="rounded-circle" alt="Logo" style="width:50px;">
					    </a>
						<ul class="navbar-nav ml-auto">
				  			<li class="nav-item">
						        <?php
						        //se c'è un utente in sessione (ovvero se non è un visitatore) mostro i bottoni d'interesse a un utente loggato
						        if(isset($_SESSION['OK']) && !empty($_SESSION['OK'])){
						        ?>
							        <button type="button" class="btn btn-outline-light  mr-3" onClick="window.location = 'Profile.php'">Torna al Profilo</a></button>
							        <button type="button" class="btn btn-outline-light  mr-3" onClick="window.location = 'Home.php'">Home</a></button>
							        <button type="button" class="btn btn-outline-light" onClick="window.location = 'logout.php'">Logout</a></button>       
						        	<?php
						        }
						        //se l'utente è visitatore mostro il bottone d'interesse a un utente visitatore
						        else{
						        ?>   
						      	   	<button type="button" class="btn btn-outline-light" onClick="window.location = 'Home.php'">Home</a></button>
						  			<?php
						        }
						        ?>
				  			</li>
						</ul>
					</div>
				</nav> 
			</header>
			<!--box/form che riuguarda la ricerca di blog tramite argomento-->
			<form id="argomento" method="post" action="BlogVision.php">
				<div class="p-5 mt-5 ml-5 mr-5 bg-white rounded box-shadow">
					<input type="hidden" id="function" name="function" value="search_arg"/>
					<h4>Ricerca i blog in base ad argomenti!</h4>
					<div class="form-group mt-3">
				        <select id="arg" name="arg">
				           	<option value="" disabled select>Seleziona un argomento</option>
				           	<?php 
			                //tramite ciclo stampo una riga di option per ogni argomento
			                while ($row = $take_argomento->fetch_assoc()) {
			                    $argomento = $row; 
			                ?>
			                    <option value="<?php echo $argomento["IdT"] ?>"><?php echo $argomento["Argomento"] ?></option>
			                <?php  
			                } 
			                ?>
				        </select>
				        <div class="mt-3">
				  			<button id="arg_button" class="btn btn-primary " type="submit">Cerca tramite argomento</button>
						</div>
				    </div>
				</div>
			</form>
			<!--box/form che riuguarda la ricerca di blog tramite parole all'interno dei titoli dei blog-->
			<form id="parola" method="post" action="BlogVision.php">
				<div class="p-5 mt-5 ml-5 mr-5 bg-white rounded box-shadow">
					<input type="hidden" id="function" name="function" value="search_wordtitle" />
				 	<h4>Ricerca i blog in base ad una parola!</h4>
					<input type="text" class="form-control" name="word" id="word" placeholder="Inserisci una parola" required>
					<div class="mt-3">
						<button id="word" class="btn btn-primary " type="submit">Cerca tramite parola</button>
					</div>
				</div>
			</form>
			<!--box/form che riuguarda la ricerca di blog tramite nickname dell'autore-->
			<form id="nickname" method="post" action="BlogVision.php">
				<div class="p-5 mt-5 ml-5 mr-5 bg-white rounded box-shadow">
					<input type="hidden" id="function" name="function" value="search_nick" />
					<h4>Ricerca i blog in base all'autore!</h4>
					<input type="text" class="form-control" name="nickn" id="nickn" placeholder="Inserisci un nickname" required>
					<div class="mt-3">
				  		<button id="nick" class="btn btn-primary" type="submit">Cerca tramite autore</button>
					</div>
				</div>
			</form>