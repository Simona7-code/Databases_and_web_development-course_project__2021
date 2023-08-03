<?php 
//Questa pagina "compone" tutte le pagine del sito, ovvero (in sequenza):
//includo l'header (comune)
include 'views/header.php';
//includo il body, che tramite la funzione index contenuta nelle pagine Chosenblog.php, Profile.php, Home.php, Signup.php, Cerca.php e Blogvision.php subisce un assegnamento  di valore (che saranno le rispettive pagine contenute nella cartella views)
include "$body";
//includo il footer(comune)
include 'views/footer.php';
//rendo la variabile di connessione globale
global $conn;
//chiudo la connessione
$conn->close();
