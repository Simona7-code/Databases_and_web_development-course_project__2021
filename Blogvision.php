<?php
//se nessuna funzione è settata, riporta alla pagina di ricerca
if(!isset($_POST["function"]))
{
	header("Cerca.php");
}
//altrimenti: salvo la function passata tramite post in una variabile, definisco il body e il titolo della pagina e richiamo page.php per assemblarla
else
{
	$function = $_POST["function"];
	$page_title = "Blogvision";
	$body = "views/BlogvisionView.php";

	include ("Page.php");
}