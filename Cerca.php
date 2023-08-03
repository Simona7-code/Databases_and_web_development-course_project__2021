<?php 

include 'include.php';
include 'funzioni.php';
 //se non è settata la function tramite post, richiamo funzione index
if(!isset($_POST["function"])){
	index();
}
//definisco il title page e body, e richiamo page.php per assemblare la pagina
function index(){
	$page_title = "Cerca";
	$body = "views/CercaView.php";
	include ("Page.php");
}