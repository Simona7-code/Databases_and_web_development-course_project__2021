<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="UTF-8">
  <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<!-- inclusione degli stylesheet: (in ordine) di Bootstrap 4.5.3, del bootstrap select (usato per il selectpicker) e del css definito da me -->
	<link rel="stylesheet" href="asset/bootstrap-4.5.3-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="asset/bootstrap-select-1.13.14/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="asset/css/generic.css">

	<?php 
      global $conn;
      //se è settata la variabile di sessione page e questa ha valore "ChosenBlog"
      if(isset($_SESSION["page"]) && $_SESSION["page"] == "ChosenBlog") {
            //ricavo id del blog tramite get
            $IdB = $_GET["idB"];
            //query che seleziona L'ID di sfondo scelto per il blog specifico in cui ci si trova (ricavato tramite get a riga 16)
            $take_IdSf = $conn->prepare("SELECT `IdSf` FROM `blog` WHERE IdB=?");
            $take_IdSf->bind_param('i', $IdB);
            $take_IdSf->execute();
            $result_IdSf= $take_IdSf->get_result();
            $colorsf = $result_IdSf->fetch_assoc(); 
            //dichiaro variabile file   
      	   	$file;
            //dichiaro e assegno  alla variabile color il valore dell'id dello sfondo (per il blog corrente) ricavato dal db
            $color= $colorsf['IdSf'];
            //tramite switch assegno alla variabile file il css corrispondente all'id dello sfondo del blog corrente
      		  switch ($color) {
          			case 1:
          				$file = "asset/css/SfVerde.css";
          				break;
          			case 2:
          				$file = "asset/css/SfBlu.css";
          				break;
          			case 3:
          				$file = "asset/css/SfNero.css";
          				break;
          			case 4:
          				$file = "asset/css/SfBianco.css";
          				break;
                case 5:
                  $file = "asset/css/SfRosso.css";
                  break;
                case 6:
                  $file = "asset/css/SfViola.css";
                  break;
                case 7:
                  $file = "asset/css/SfGiallo.css";
                  break;
                case 8:
                  $file = "asset/css/SfArancione.css";
                  break;
          			default:
          				 $file = "immagini/sfondo.jpg";
          				break;
            } ?>
            <!--viene definito il foglio di stile per la pagina chosenblog tramite una stampa echo della variabile file che avrà avuto l'assegnamento del css corrispondente all'id dello sfondo del blog nello switch riga 28-55-->
            <link rel="stylesheet" href="<?php echo $file ?>">
            <?php
            //query che seleziona L'ID del font scelto per il blog specifico in cui ci si trova (ricavato tramite get a riga 16)
            $take_IdFo = $conn->prepare("SELECT `IdFo` FROM `blog` WHERE IdB=?");
            $take_IdFo->bind_param('i', $IdB);
            $take_IdFo->execute();
            $result_IdFo= $take_IdFo->get_result();
            $colorF = $result_IdFo->fetch_assoc();
            //dichiaro e assegno  alla variabile fontcolor il valore dell'id del font (per il blog corrente) ricavato dal db
            $fontcolor= $colorF['IdFo']; 
            //dichiaro variabile fontfile 
            $fontfile;
            //tramite switch assegno alla variabile fontfile il css corrispondente all'id del font del blog corrente
            switch ($fontcolor) {
                case 1:
                      $fontfile = "asset/css/FVerde.css";
                      break;
                case 2:
                      $fontfile = "asset/css/FBlu.css";
                      break;
                case 3:
                      $fontfile = "asset/css/FNero.css";
                      break;
                case 4:
                      $fontfile = "asset/css/FBianco.css";
                      break;
                case 5:
                      $fontfile = "asset/css/FRosso.css";
                      break;
                case 6:
                      $fontfile = "asset/css/FViola.css";
                      break;
                case 7:
                      $fontfile = "asset/css/FGiallo.css";
                      break;
                case 8:
                      $fontfile = "asset/css/FArancione.css";
                      break;
                default:
                      $fontfile= "asset/css/FNero.css";
                      break;
            }?>
            <!--viene definito il foglio di stile per la pagina chosenblog tramite una stampa echo della variabile fontfile che avrà avuto l'assegnamento del css corrispondente all'id del font del blog nello switch riga 71-98-->
            <link rel="stylesheet" href="<?php echo $fontfile ?>">
  <?php       
  } 
  ?>
  <!--inserisco nel tag title il valore corrente di page_title (varia in base alla pagina grazie alla funzione index contenuta nelle pagine Chosenblog.php, Profile.php, Home.php, Signup.php, Cerca.php e Blogvision.php)-->
  <title><?php echo $page_title ?></title>
</head>

