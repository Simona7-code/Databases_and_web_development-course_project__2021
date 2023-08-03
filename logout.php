<?php
include 'include.php';
//svuoto l array $_session (ovvero rimuovo le variabili di sessione) assegnandogli come valore un array vuoto
$_SESSION = array();
//distruggo tutti i dati legati alla sessione corrente
session_destroy();
//riporto l'utente alla home page
header("location: Home.php");
//termina lo script corrente
exit();
?>