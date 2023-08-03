<?php
//dati per accedere al DB
$DBhost = 'localhost';
$DBuser= 'root';
$DBpassword = '';
$DBdatabase = 'bdlw';
//connessione al db
$conn = new mysqli($DBhost, $DBuser, $DBpassword, $DBdatabase);
if ($conn-> connect_errno){
	die('Errore di connessione al Database:'.$conn-> connect_error);
} 
$conn->set_charset("utf8mb4");
?>