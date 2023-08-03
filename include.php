<?php
//comincio una nuova sessione o recupero la precedente già settata (se esiste)
session_start();
//includo la connessione al db
include 'connect.php';
//setto la variabile di sessione "page" a null; all'interno della pagina Chosenblog.php questa variabile di sessione viene riassegnata con un altro valore che permette di cambiare i colori di font e sfondi  all'interno della suddetta pagina in base alle scelte fatte dagli utenti (vedi views/header.php)
$_SESSION["page"] = null;