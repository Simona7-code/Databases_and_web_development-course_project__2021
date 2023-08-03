//essendo la struttura di tutte le chiamate ajax ed eventi jquery sempre la stessa, valgono per tutte i commenti che farò alla prima (righe 5-32), tranne eventuali specificazioni 

//FORM LOGIN IN HOMEVIEW.PHP

//quando per questo id di form viene fatto un submit dei dati(evento)
$( "#form_login" ).submit(function( event ) {
	//impedisce al comportamento di default del submit(evento in questione) di avvenire, ovvero di fare il submit istantaneamente
	event.preventDefault();
	//definisco i dati da passare
	var data = {
		'function' : 'save_login',
		'email' : $('#EmailInserita').val(),
		'psw' : $('#PswInserita').val()
	};
	// .ajax() fa una richiesta http/ajax asincrona
	$.ajax({
		//stringa che rappresenta l'URL a cui inviare la richiesta 
		url: "Home.php",
		//dati da inviare
		data: data,
		//tramite metodo post
		method: "POST",
		//tipo di dati restituiti dal server
		dataType: "JSON",
	//nel caso di successo della richiesta,
	}).done(function(ris) {
		//se nell'oggetto ris (rappresentato come un json tramite i json_encode nelle funzioni invocate), il valore di response è "OK" (che nelle funzioni ho inserito nel caso sia andato tutto bene)
		if (ris.response=="OK"){
			//reindirizzo a pagina Profile.php
			window.location="Profile.php";
		}
		//altrimenti stampo il valore di response ( che è contenuto in ris), ( nel mio caso sono solitamente messaggi di errore)
		else alert(ris.response);	
	}); //chiusura tonda e graffa riga 25
});//chiusura tonda e graffa riga 5

//EVENTI DI SIGNUPVIEW.PHP------------------------------------------------------------------------------------------------------------------------------

//FORM DI SIGNUP IN SIGNUPVIEW.PHP
$( "#formsignup" ).submit(function( event ) {
	
	event.preventDefault();
	var data = {
		'function' : 'save_signup',
		'nickn' : $('#nik').val(),
		'name' : $('#name').val(),
		'lastn' : $('#lastname').val(),
		'age' : $('#age').val(),
		'email' : $('#email').val(),
		'tel' : $('#telef').val(),
		'docum' : $('#docu').val(),
		'psw' : $('#psw').val(),
		'pswcheck' : $('#ctrpsw').val(),
		'gender' : $("option:selected").val()	
	};
	$.ajax({
	url: "SignUp.php",
	data: data,
	method: "POST",
	dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			window.location="Profile.php";
		}
		else alert(ris.response);
	});
});

//FORM DI CONTROLLO DISPONIBILITÀ NICKNAME IN PAGINA SIGNUPVIEW.PHP
$( "#validate_nik" ).click(function(event) {

	event.preventDefault();
	var data = {
		'function' : 'validate_nik',
		'nik' : $('#nik').val()
	};
	$.ajax({

		url: "SignUp.php",
		data: data,
		method: "POST",
		dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			//prendo l'oggetto con ID nik e aggiungo una classe css (definita in generic.css)
			$("#nik").addClass("ok-nick");
		}
		else {
			alert(ris.response);
		}
	});
});

//EVENTI DI PROFILEVIEW.PHP------------------------------------------------------------------------------------------------------------------------------

//FORM CREAZIONE BLOG IN PROFILEVIEW.PHP
$( "#Blogmodal" ).submit(function(event) {

	event.preventDefault();
	var data = {
		'function' : 'create_blog',
		'title' : $('#Blogtitle').val(),
		'tema': $('#argomento option:selected').val(),
		'sottotemi': $('select#sottargomento.selectpicker').val(),
		'font':  $("#dimfont option:selected").val(),
		'sfondo':  $("#sfondocolor option:selected").val()	
	};
	$.ajax({

			url: "Profile.php",
			data: data,
			method: "POST",
			dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			//dentro il ris, dentro la chiave IdBlog (definito nella relativa funzione)ho salvato il valore dell'id del blog, e sfrutto il get per passarlo alla pagina che presenta il blog, così che poi mi possa recuperare i dati relativi ad esso
			window.location="ChosenBlog.php?idB="+ris.idBlog;
		}
		else {
			alert(ris.response);
		}
	});
});
// FORM CREAZIONE BLOG, PRENDE ID ARGOMENTO SELEZIONATO CHE DETERMINA LE OPZIONI DI SOTTOARGOMENTI MOSTRATI IN PROFILEVIEW.PHP
$( "#argomento" ).change(function() {
	var data = {
		'function' : 'get_sottoargomento',
		'argomento' : $( "#argomento" ).val()	
	};
	$.ajax({
		url: "Profile.php",
		data: data,
		method: "POST",
		dataType: "JSON"
	}).done(function(ris) {
		if (ris.response=="OK"){
			//dentro ris, dentro la chiave "html" ho salvato come valore tutta la costruzione html del select option dei relativi sottoargomenti selezionabili per dato argomento (tramite fun riga 121)
			//tramite .html vengono inseriti, nell  elemento che ha come id "div_sottoargomento", l'html contenuto in "html" (che è contenuto in ris)
			$('#div_sottoargomento').html(ris.html);
			//gli dico che nello specifico solo la select con quell'id deve essere di tipo selectpicker.
			$('#div_sottoargomento select').selectpicker();
		}
		else {
			alert(ris.response);
		}	
	});
});

//FORM MODIFICA DATI UTENTE in Profileview.php
$( "#Modinfo" ).submit(function( event ) {
	
	event.preventDefault();
	var data = {
		'function' : 'modify_data',
		'nickn' : $('#datanick').val(),
		'name' : $('#dataname').val(),
		'lastn' : $('#datalastn').val(),
		'age' : $('#datage').val(),
		'tel' : $('#datatel').val(),
		'docum' : $('#datadoc').val(),
		'gender' : $("#gender option:selected").val()
	};
	$.ajax({
		url: "Profile.php",
		data: data,
		method: "POST",
		dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			//comunico all'utente che è andato tutto bene e ricarico la pagina in modo che possa visualizzare le modifiche
			alert("Dati salvati correttamente");
			location.reload();
		}
		else alert(ris.response);	
	});
});
//FORM MODIFICA PASSWORD UTENTE in Profileview.php
$( "#ModPass" ).submit(function( event ) {
	
	event.preventDefault();
	var data = {
		'function' : 'modify_pass',
		'oldpass' : $('#oldpass').val(),
		'newpass' : $('#newpass').val(),
		'checknew' : $('#checknew').val()
	};
	$.ajax({
		url: "Profile.php",
		data: data,
		method: "POST",
		dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			//comunico all'utente che è andato tutto bene e ricarico la pagina
			alert("password salvata correttamente");
			location.reload();
		}
		else alert(ris.response);	
	});
});
//BOTTONE CHE CANCELLA L'ACCOUNT in profileview.php
$( "#DelAcc" ).click(function( event ) {
	
	event.preventDefault();
	var data = {
		'function' : 'delete_user'
	};
	$.ajax({
		url: "Profile.php",
		data: data,
		method: "POST",
		dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			window.location= "index.php";
		}
		else alert(ris.response);	
	});
});

// EVENTI DI CHOSENBLOG.PHP-----------------------------------------------------------------------------------------------------------------------------------

//FORM MODIFICA BLOG in ChosenBlogView.php
$( "#BlogMod" ).submit(function( event ) {
	
	event.preventDefault();
	var data = {
		'function' : 'blog_mod',
		'titolom' : $('#Blogtitle').val(),
		'fontm':  $("#dimfont option:selected").val(),
		'sfondom':  $("#sfondocolor option:selected").val(),
		//ho creato un div "ausiliario"  circa a riga 92 di chosenblogview per poter portare facilmente il valore dell' id del blog qui, mettendolo come valore dell attributo value
		'IdB':$("#IdBlog").attr("value")
	};
	$.ajax({
		url: "ChosenBlog.php",
		data: data,
		method: "POST",
		dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			//comunico all'utente che è andato tutto bene e ricarico la pagina in modo che possa visualizzare le modifiche
			alert("Nuovi dati del Blog salvati correttamente");
			location.reload();
		}
		else alert(ris.response);	
	});
});
//FORM CREA POST in ChosenBlogView.php
$( "#Newpost" ).submit(function( event ) {
	
	event.preventDefault();

	//creo un oggetto di tipo formdata (necessario per passare file immagine) e lo salvo in data
	var data = new FormData();
	
	//salvo dentro image1 il valore (il file) dell'id image1
	var image1 = $('#image1')[0].files;
	//per ogni elemento in image1 
	$.each(image1, function(key, value){
		//faccio un append all'interno di data che ha come chiave la stinga 'image1' e valore corrispondente a riga 256
	    data.append('image1', value);
	});

	//stesso meccanismo descritto nelle righe 253, 255, 257,259
	var image2 = $('#image2')[0].files;
	$.each(image2, function(key, value){
	    data.append('image2', value);
	});
	//stesso meccanismo descritto nelle righe 253, 255, 257,259
	var image3 = $('#image3')[0].files;
	$.each(image3, function(key, value){
	    data.append('image3', value);
	});
	//tramite append inserisco coppie chiavi valore all'interno del formdata salvato in data
	data.append('function', 'new_post');
	data.append('newtit', $('#PosTitle').val());
	data.append('newtext', $('#PosText').val());
	data.append('IdB', $("#IdBlog").attr("value"));

	$.ajax({
		url: "ChosenBlog.php",
		data: data,
		method: "POST",
		dataType: "JSON",
		//prossime tre righe necessarie per passare un oggetto di tipo FormData
		cache: false,
		processData: false, // evitare la convesione (processamento) dei dati (un form-data) in una stringa
        contentType: false, //cosi jquery non aggiunge un header di content type
	}).done(function(ris) {
		if (ris.response=="OK"){
			//comunico all'utente che è andato tutto bene e ricarico la pagina in modo che possa visualizzare il nuovo post creato
			alert("post creato correttamente");
			location.reload();
		}
		else alert(ris.response);	
	});
});

//FORM INSERISCI COMMENTO IN ChosenBlogView.php
$( ".makecomment" ).submit(function( event ) {
	//nella variabile this salvo l'oggetto jquery che racchiude l'elemento in cui è invocato il submit di makecomment
	var $this = $(this);

	event.preventDefault();
	var data = {
		'function' : 'ins_comm',
		//.find cercherà la classe "commentoinser" nella discendenza di $this (definito a riga 285) e tramite .val() ne ottiene il valore e lo salvo all'interno di comtext
		'comtext' : $this.find('.CommentoInser').val(),
		//prendo il valore dell'attributo value della classe makecomment selezionata dal $this (metodo che ho usato per passare l'id del post corrente qui) e lo salvo in IdP
		'IdP': $this.attr("value")
	};
	$.ajax({
		url: "ChosenBlog.php",
		data: data,
		method: "POST",
		dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			//comunico all'utente che è andato tutto bene e ricarico la pagina in modo che possa visualizzare il commento inserito
			alert("commento inserito correttamente");
			location.reload();
		}
		else alert(ris.response);	
	});
});

//CANCELLA COMMENTO in ChosenBlogView.php
$( ".delate_comment" ).submit(function( event ) {
	//spiegazione riga 285
	var $this = $(this);

	event.preventDefault();
	var data = {
		'function' : 'Del_comment',
		//spiegazione equivalente a riga 293
		'IdC': $this.attr("value")
	};
	$.ajax({
		url: "ChosenBlog.php",
		data: data,
		method: "POST",
		dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			//comunico all'utente che è andato tutto bene e ricarico la pagina in modo che possa visualizzare la buon riuscita della cancellazione
			alert("commento rimosso correttamente");
			location.reload();
		}
		else alert(ris.response);	
	});
});

//CANCELLA POST in ChosenBlogView.php
$( ".delate_post" ).submit(function( event ) {
	//spiegazione riga 285
	var $this = $(this);

	event.preventDefault();
	var data = {
		'function' : 'Del_post',
		//spiegazione equivalente a riga 293
		'IdP': $this.attr("value")
	};
	$.ajax({
		url: "ChosenBlog.php",
		data: data,
		method: "POST",
		dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			//comunico all'utente che è andato tutto bene e ricarico la pagina in modo che possa visualizzare la buon riuscita della cancellazione
			alert("post rimosso correttamente");
			location.reload();
		}
		else alert(ris.response);	
	});
});

//INSERIRE LIKE AD UN POST in ChosenBlogView.php
$( ".like" ).click(function( event ) {
	//spiegazione riga 285
	var $this = $(this);
	
	event.preventDefault();
	var data = {
		'function' : 'ins_like',
		//spiegazione equivalente a riga 293
		'IdP': $this.attr("value")	
	};
	$.ajax({
		url: "ChosenBlog.php",
		data: data,
		method: "POST",
		dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			//nascondo il bottone corrente aggiungendo l'attributo hidden nell'html
			$this.attr('hidden', '');
			//mostro il bottone per togliere il like rimuovendo l'attributo hidden dall'html per tale bottone (dello specifico post, ralizzato tramite this.arttr value riferito alla classe)
			$('.dislike[value='+$this.attr("value")+']').removeAttr('hidden');
		}
		else alert(ris.response);	
	});
});

//TOGLIE IL LIKE AD UN POST in ChosenBlogView.php
$( ".dislike" ).click(function( event ) {
	//spiegazione riga 285
	var $this = $(this);

	event.preventDefault();
	var data = {
		'function' : 'del_like',
		//spiegazione equivalente a riga 293
		'IdP': $this.attr("value")	
	};
	$.ajax({
		url: "ChosenBlog.php",
		data: data,
		method: "POST",
		dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			//stesso meccanismo spiegato nelle righe 379 e 382 ma per mostrare e nascondere i bottoni opposti
			$this.attr('hidden', '');
			$('.like[value='+$this.attr("value")+']').removeAttr('hidden');
			
		}
		else alert(ris.response);	
	});
});

//CANCELLARE IL BLOG in ChosenBlogView.php
$( "#delblog" ).click(function( event ) {
	
	event.preventDefault();
	var data = {
		'function' : 'delete_blog',
		//spiegazione a riga 224
		'IdB':$("#IdBlog").attr("value")
	};
	$.ajax({
		url: "ChosenBlog.php",
		data: data,
		method: "POST",
		dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			window.location= "Profile.php";
		}
		else alert(ris.response);	
	});
});

//INSERIRE UN COLLABORATORE SU UN BLOG in ChosenBlogView.php
$( "#insert" ).click(function( event ) {
	
	event.preventDefault();
	var data = {
		'function' : 'insert_coll',
		'nickn' : $('#newcoll').val(),
		//spiegazione a riga 224
		'IdB':$("#IdBlog").attr("value")
	};
	$.ajax({
		url: "ChosenBlog.php",
		data: data,
		method: "POST",
		dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			//comunico all'utente che è andato tutto bene e ricarico la pagina in modo che possa visualizzare la buon riuscita dell'inserimento
			alert("Nuovo collaboratore inserito con successo.");
			location.reload();
		}
		else alert(ris.response);	
	});
});

//ELIMINARE UN COLLABORATORE SU UN BLOG in ChosenBlogView.php
$( "#cancel" ).click(function( event ) {
	
	event.preventDefault();
	var data = {
		'function' : 'delete_coll',
		//spiegazione a riga 224
		'IdB':$("#IdBlog").attr("value")	
	};
	$.ajax({
		url: "ChosenBlog.php",
		data: data,
		method: "POST",
		dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			//comunico all'utente che è andato tutto bene e ricarico la pagina in modo che possa visualizzare la buon riuscita della cancellazione
			alert("Collaboratore rimosso con successo.");
			location.reload();
		}
		else alert(ris.response);	
	});
});

// UTENTE INSERISCE IL FOLLOW  SU UN BLOG in  ChosenBlogView.php
$( "#followb" ).click(function( event ) {
	
	event.preventDefault();
	var data = {
		'function' : 'insert_follow',
		//spiegazione a riga 224
		'IdB':$("#IdBlog").attr("value")	
	};
	$.ajax({
		url: "ChosenBlog.php",
		data: data,
		method: "POST",
		dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			//aggiungo l'attributo hidden all'elemento (bottone) con id followb (quello che è stato appena cliccato) e mostro l'altro bottone fino ad ora nascosto
			$('#followb').attr('hidden', '');
			$('#unfollowb').removeAttr('hidden');

		}
		else alert(ris.response);	
	});
});

// UTENTE RIMUOVE IL FOLLOW  DA UN BLOG in  ChosenBlogView.php
$( "#unfollowb" ).click(function( event ) {
	
	event.preventDefault();
	var data = {
		'function' : 'del_follow',
		//spiegazione a riga 224
		'IdB':$("#IdBlog").attr("value")	
	};
	$.ajax({
		url: "ChosenBlog.php",
		data: data,
		method: "POST",
		dataType: "JSON",
	}).done(function(ris) {
		if (ris.response=="OK"){
			//aggiungo l'attributo hidden all'elemento (bottone) con id unfollowb (quello che è stato appena cliccato) e mostro l'altro bottone fino ad ora nascosto
			$('#unfollowb').attr('hidden', '');
			$('#followb').removeAttr('hidden');

		}
		else alert(ris.response);	
	});
});