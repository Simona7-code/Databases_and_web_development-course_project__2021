-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Gen 02, 2021 alle 13:12
-- Versione del server: 10.4.11-MariaDB
-- Versione PHP: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bdlw`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `apprezza`
--

CREATE TABLE `apprezza` (
  `IdU` int(11) NOT NULL,
  `IdP` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `apprezza`
--

INSERT INTO `apprezza` (`IdU`, `IdP`) VALUES
(20, 42),
(20, 46),
(20, 49),
(30, 58),
(30, 60),
(30, 61),
(30, 46),
(30, 41),
(30, 45),
(39, 46),
(39, 41),
(39, 58),
(39, 4),
(39, 45),
(40, 46),
(40, 41),
(40, 45),
(37, 46),
(37, 41),
(37, 60),
(41, 46),
(41, 41),
(41, 45),
(41, 60),
(39, 61);

-- --------------------------------------------------------

--
-- Struttura della tabella `argblog`
--

CREATE TABLE `argblog` (
  `IdT` int(11) NOT NULL,
  `IdSt` int(11) DEFAULT NULL,
  `IdB` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `argblog`
--

INSERT INTO `argblog` (`IdT`, `IdSt`, `IdB`) VALUES
(7, NULL, 39),
(4, NULL, 67),
(3, 88, 69),
(19, 92, 70),
(19, 93, 70),
(15, 1, 71),
(15, 2, 71),
(15, 3, 71),
(8, 44, 72),
(5, 18, 73),
(9, 80, 76),
(9, 82, 76),
(10, 48, 77),
(10, 50, 77),
(10, 51, 77),
(11, 56, 78),
(11, 57, 78),
(12, 61, 92),
(12, 62, 92),
(12, 68, 92),
(12, 71, 92),
(16, 12, 94),
(16, 13, 94);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `argblogview`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `argblogview` (
`IdT` int(11)
,`IdSt` int(11)
,`IdB` int(11)
,`Titolo` varchar(100)
,`IdU` int(11)
,`IdSf` int(11)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `blog`
--

CREATE TABLE `blog` (
  `IdB` int(11) NOT NULL,
  `Titolo` varchar(100) NOT NULL,
  `IdU` int(11) NOT NULL,
  `IdSf` int(11) NOT NULL,
  `IdFo` int(11) NOT NULL,
  `IdUcollab` int(11) DEFAULT NULL,
  `Ereditato` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `blog`
--

INSERT INTO `blog` (`IdB`, `Titolo`, `IdU`, `IdSf`, `IdFo`, `IdUcollab`, `Ereditato`) VALUES
(39, 'Turismo ', 30, 1, 1, NULL, 'SI'),
(67, 'musica for dummies', 30, 5, 1, NULL, 'SI'),
(69, 'Una mela al giorno', 20, 4, 3, 40, NULL),
(70, 'Ricette di Rokky', 20, 8, 3, NULL, NULL),
(71, 'Corpore sano in mens sana', 20, 2, 1, NULL, NULL),
(72, 'Abbiamo solo un pianeta', 20, 1, 2, 37, NULL),
(73, 'Modellare negli anni 2000', 30, 5, 3, NULL, 'SI'),
(76, 'Cultura e novità dal mondo', 30, 4, 3, NULL, NULL),
(77, 'Piante Grasse: quali sono e come curarle', 30, 1, 1, NULL, NULL),
(78, 'Bricolage che passione!', 30, 2, 2, NULL, NULL),
(92, 'pallavolo nel cuore', 37, 4, 1, NULL, 'SI'),
(94, 'Consigli di lettura', 39, 3, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `blog_nick`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `blog_nick` (
`IdB` int(11)
,`Titolo` varchar(100)
,`IdU` int(11)
,`Nickn` varchar(10)
,`IdSf` int(11)
,`IdFo` int(11)
,`IdUcollab` int(11)
,`Ereditato` varchar(2)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `commenti`
--

CREATE TABLE `commenti` (
  `IdC` int(11) NOT NULL,
  `IdU` int(11) NOT NULL,
  `TestoC` varchar(1000) NOT NULL,
  `DataC` date NOT NULL,
  `OraC` time NOT NULL,
  `Idp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `commenti`
--

INSERT INTO `commenti` (`IdC`, `IdU`, `TestoC`, `DataC`, `OraC`, `Idp`) VALUES
(18, 20, 'la consiglio!', '2020-12-28', '01:36:00', 45),
(23, 20, 'Parole sante!', '2021-01-02', '12:53:00', 46),
(24, 20, 'wow', '2021-01-02', '12:53:00', 4),
(25, 20, 'vero', '2021-01-02', '12:54:00', 47),
(28, 20, 'che noia!', '2021-01-02', '12:55:00', 59),
(29, 39, 'Molto corretto', '2021-01-02', '12:56:00', 46),
(30, 39, 'ottimo lavoro!', '2021-01-02', '12:57:00', 61);

-- --------------------------------------------------------

--
-- Struttura della tabella `font`
--

CREATE TABLE `font` (
  `IdFo` int(11) NOT NULL,
  `Font` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `font`
--

INSERT INTO `font` (`IdFo`, `Font`) VALUES
(1, 'Verde'),
(2, 'Blu'),
(3, 'Nero'),
(4, 'Bianco'),
(5, 'Rosso'),
(6, 'Viola'),
(7, 'Giallo'),
(8, 'Arancione');

-- --------------------------------------------------------

--
-- Struttura della tabella `img`
--

CREATE TABLE `img` (
  `IdI` int(11) NOT NULL,
  `File_Img` varchar(500) NOT NULL,
  `Idp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `img`
--

INSERT INTO `img` (`IdI`, `File_Img`, `Idp`) VALUES
(2, 'upload/1608214901.974.jpg', 40),
(3, 'upload/1608215172.1042.jpg', 41),
(4, 'upload/1608215172.1047.jpg', 41),
(5, 'upload/1608215362.4912.jpg', 42),
(6, 'upload/1608215362.4916.jpg', 42),
(7, 'upload/1608215362.4918.jpg', 42),
(10, 'upload/1608215823.2947.jpg', 45),
(11, 'upload/1608215823.2953.jpg', 45),
(12, 'upload/1608215970.1122.jpg', 46),
(13, 'upload/1608216352.8017.jpg', 48),
(14, 'upload/1608216352.8023.jpg', 48),
(15, 'upload/1608216462.6481.jpg', 49),
(16, 'upload/1608216462.6488.jpg', 49),
(17, 'upload/1608216763.5103.jpg', 51),
(18, 'upload/1608216763.5107.jpg', 51),
(30, 'upload/1608219399.609.jpg', 58),
(31, 'upload/1608219475.1841.jpg', 59),
(32, 'upload/1608219669.1622.jpg', 60),
(33, 'upload/1608219718.3866.jpeg', 61),
(34, 'upload/1608219718.3881.jpeg', 61),
(35, 'upload/1608219871.0861.jpg', 62),
(41, 'upload/1609586335.8523.jpg', 75),
(42, 'upload/1609587324.7209.jpg', 77);

-- --------------------------------------------------------

--
-- Struttura della tabella `post`
--

CREATE TABLE `post` (
  `IdP` int(11) NOT NULL,
  `TitoloP` varchar(50) NOT NULL,
  `TestoP` varchar(2000) NOT NULL,
  `DataP` date NOT NULL,
  `OraP` time NOT NULL,
  `IdB` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `post`
--

INSERT INTO `post` (`IdP`, `TitoloP`, `TestoP`, `DataP`, `OraP`, `IdB`) VALUES
(4, 'Vacanze all\'estero per studenti!', 'Qui puoi trovare informazioni di viaggio pratiche sui visti e sui requisiti di accesso, sui regolamenti doganali e di quarantena e sui viaggi accessibili in Australia. Ci sono anche informazioni per aiutarti a pianificare il tuo viaggio, tra cui come arrivare e spostarsi in Australia, i tour che puoi fare mentre sei qui e le strutture in cui soggiornare. Offriamo inoltre alcuni consigli utili sui viaggi nelle zone più remote, suggerimenti, informazioni sui pericoli a cui prestare attenzione e chi contattare in caso di emergenza durante il tuo soggiorno.\r\n\r\nScopri le nostre città, gli stati e i territori, il clima, i fusi orari, la storia, la fauna e la flora uniche al mondo e le informazioni sul visto australiano e i requisiti per l\'ingresso nel paese.', '2020-12-05', '04:56:00', 39),
(40, 'Presento il mio blog!', 'Gli approfondimenti e i consigli degli esperti per mantenersi in salute e prevenire e curare le malattie con un’alimentazione equilibrata, lo sport e uno stile di vita sano. Tanti focus sugli strumenti per una corretta diagnosi, le strategie di prevenzione e le terapie più all’avanguardia per la cura di patologie e intolleranze. Una guida per salvaguardare la propria salute e il proprio benessere ad ogni età e in ogni stadio della vita: i consigli per giovani e anziani, bambini e adolescenti, donne in menopausa, in gravidanza e in allattamento, forniti da medici esperti in tutte le discipline.', '2020-12-17', '03:21:41', 69),
(41, 'Vantaggi di consumare meno sale', 'Buone notizie per la salute pubblica.  Tra il 2016 e 2019 quasi 6 italiani su 10 hanno ridotto la quantità di sale assunta a tavola. Lo rivelano i dati dell’Istituto Superiore di Sanità.  Un risultato incoraggiante, viste le problematiche che un consumo eccessivo di sale comporta per il nostro benessere.Un corretto ed equilibrato uso del sale è di estrema importanza.  Infatti, se da una parte il sale è fondamentale per l’organismo, dall’altra un suo consumo eccessivo è una delle cause più comuni delle malattie cardiovascolari.  Il sale apporta sodio e cloro, due elementi fondamentali nella:  regolazione dell’equilibrio acido-base dell’organismo nel bilancio idrico (vale a dire la distribuzione dei liquidi nonché il volume di sangue nell’organismo). Il sodio, in particolare, ha funzioni molto importanti regolando la quantità di acqua presente nel sangue e tra le cellule dei tessuti. Inoltre, influenza la contrazione muscolare e la trasmissione dell’impulso nervoso.', '2020-12-17', '03:26:12', 69),
(42, 'Quando si ingrassa di più?', 'Quando ingrassiamo di più: lo studio Il nostro peso oscilla nel tempo e molti fattori possono essere all’origine di queste variazioni:  lo stress l’età una malattia i cambiamenti delle abitudini. Tre periodi della vita molto specifici possono avere un impatto sulla nostra linea. Lo rivelano due meta-analisi condotte da ricercatori dell’Università di Cambridge (Regno Unito) e pubblicate su Obesity Reviews. Il lavoro di ricerca si è concentrato su:  impatto della genitorialità conseguenze dell’accesso all’istruzione superiore primo lavoro.', '2020-12-17', '03:29:22', 69),
(45, 'Ricetta pasta per la pizza', 'Per preparare la pasta per la pizza abbiamo scelto di impastare il tutto a mano, ma se preferite utilizzare l’impastatrice potrete seguire gli stessi procedimenti, utilizzando il gancio a velocità medio bassa. Cominciate versando il lievito nell’acqua a temperatura ambiente e scioglietelo per bene 2; in alternativa potete anche sbriciolare il lievito nella farina, il risultato non cambierà. Se preferite, potete utilizzare 2 g di lievito di birra disidratato. Proseguite versando la farina manitoba e quella 00 in un recipiente ', '2020-12-17', '03:37:03', 70),
(46, 'Turismo Sostenibile', 'Se ne parla sempre più spesso, la parola “Turismo Sostenibile” è sulla bocca di tanti. Ma cos’è davvero questa forma di turismo? E come si può praticare? Scopriamolo insieme! Ne abbiamo scritto tante volte, e sono sempre di più le persone che ne parlano: ma sappiamo dire con esattezza cos’è il turismo sostenibile? Forse no, e allora vi presentiamo la nostra guida pratica a questo modo di viaggiare.  Partiamo dalla definizione, data dalla stessa Organizzazione Mondiale del Turismo:  Turismo capace di soddisfare le esigenze dei turisti di oggi e delle regioni ospitanti prevedendo e accrescendo le opportunità per il futuro. Tutte le risorse dovrebbero essere gestite in modo tale che le esigenze economiche, sociali ed estetiche possano essere soddisfatte mantenendo l’integrità culturale, i processi ecologici essenziali, la diversità biologica, i sistemi di vita dell’area in questione.  I prodotti turistici sostenibili sono quelli che agiscono in armonia con l’ambiente, la comunità e le culture locali, in modo tale che essi siano i beneficiari e non le vittime dello sviluppo turistico.', '2020-12-17', '03:39:30', 39),
(47, 'Mens sana in corpore sano', 'Oggi la frase ha assunto un significato leggermente diverso, influenzato dalle moderne conoscenze: mantenere il corpo in forma aiuta anche la salute del cervello.  Ma il corpo e la mente devono essere sforzati separatamente e in momenti diversi, per evitare che lo sforzo di una vanifichi e impedisca l’altro.  La ginnastica sviluppa il coraggio e fin dai tempi antichi deve essere temperata dallo studio della musica, per evitare l’eccesso, cioè che il coraggio diventi ferocia.  L’esercizio fisico è condizione indispensabile per l’efficienza della facoltà spirituale; l’igiene mentale dev’essere sempre accompagnata da un’adeguata igiene fisica; non si deve affaticare troppo la mente nello studio a danno della salute fisica e viceversa.  Voltaire precisava che occorre coltivare il proprio giardino, per tornare al legame di una vita sana e ad un riavvicinamento alla natura.  Tutto ciò significa essere preparati a combattere con lucidità nel momento giusto, significa metabolizzare le informazioni che arrivano dall’esterno in modo da elaborarle nel miglior modo possibile per non cascare nel tranello dei media della mala informazione; è palese infatti che oggi sui mass-media ci sia un continuo attacco mediatico alle menti dei ragazzi.  Per tutto ciò serve disciplina cioè impegno assiduo, esercizio, pratica costante, dominio dei propri impulsi perseguito con sforzo e sacrificio.  È grazie a questa virtù che noi riusciamo a vincere le difficoltà giornaliere', '2020-12-17', '03:43:37', 71),
(48, 'Inquinamento Atmosferico', 'L\'inquinamento atmosferico nuoce all\'ambiente e alla salute umana. In Europa, le emissioni di molti inquinanti atmosferici sono diminuite in modo sostanziale negli ultimi decenni, determinando una migliore qualità dell\'aria nella regione. Le concentrazioni di inquinanti sono tuttavia ancora troppo elevate e i problemi legati alla qualità dell\'aria persistono. Una parte significativa della popolazione europea vive in zone, in particolar modo nelle città, in cui si superano i limiti fissati dalle norme in materia di qualità dell\'aria: l\'inquinamento da ozono, biossido di azoto e particolato pone gravi rischi per la salute. Diversi paesi hanno superato uno o più dei loro limiti relativi alle emissioni per il 2010 per quattro importanti inquinanti atmosferici. Ridurre l\'inquinamento atmosferico, quindi, continua a essere importante.', '2020-12-17', '03:45:52', 72),
(49, 'Di cosa parliamo?', 'Cosa dobbiamo intendere per inquinamento? Negli ultimi anni l\'uso di questa parola si è molto esteso, tanto che nel linguaggio comune la parola inquinamento è spesso usata come sinonimo di ambiente sporco. L\'inquinamento vero e proprio consiste invece nell\'introduzione diretta o indiretta in un ambiente di sostanze o anche di energia capaci di trasformare gli equilibri naturali producendo anche effetti sulla salute umana. Alcune di queste trasformazioni sono irreversibili nel medio o nel lungo periodo.  L\'inquinamento può essere provocato da fenomeni naturali ‒ per esempio eruzioni vulcaniche, incendi, radioattività di alcune rocce ‒ o da attività dell\'uomo. In entrambi i casi, vengono immesse in un ambiente sostanze estranee a esso o sostanze comuni ma in quantità tali che superano la capacità di digestione (demolizione e decomposizione) e assorbimento da parte di quell\'ambiente: è il caso dell\'eutrofizzazione negli ambienti acquatici o dell\'eccesso di produzione di anidride carbonica che provoca l\'effetto serra.  Nell\'ultimo secolo l\'inquinamento provocato dalle attività umane ha di gran lunga superato l\'inquinamento di origine naturale.', '2020-12-17', '03:47:42', 72),
(50, '3D modeling ', 'La modellazione 3D, nella computer grafica 3D, è il processo atto a definire una forma tridimensionale in uno spazio virtuale generata su computer; questi oggetti, chiamati modelli 3D vengono realizzati utilizzando particolari programmi software, chiamati modellatori 3D, o più in generale software 3D.', '2020-12-17', '03:49:48', 73),
(51, 'I miei progetti!', 'Che ne pensate?', '2020-12-17', '03:52:43', 73),
(58, 'Regio Torino ALive, concerto d\'archi', 'Protagonista l\'Orchestra d\'Archi Teatro Regio Torino, diretta da Andrea Mauri, che esegue la Sinfonia per archi n.  10 di Felix Mendelssohn-Bartholdy, l\'Intermezzo da Cavalleria rusticana di Pietro Mascagni, Sospiri op. 70 di Edward Elgar e l\'Adagietto dalla Quinta Sinfonia di Gustav Mahler.     Il concerto rientra nell\'iniziativa #apertinonostantetutto, lanciata dall\'Anfols e alla quale hanno aderito le Fondazioni lirico-sinfoniche italiane, che prevede una ricca stagione in streaming dai siti web e dalle pagine Facebook dei Teatri, nonché sulla web tv dell\'Anfols (anfols.it/webtv) e sul sito dell\'ANSA (ansa.it).', '2020-12-17', '04:36:39', 76),
(59, 'Nuova docu-serie con testimonianza Papa Francesco', ' Netflix annuncia la nuova docu-serie originale ispirata a Sharing the Wisdom of Time (La Saggezza del tempo), il pluripremiato libro scritto da Papa Francesco a cura di Padre Antonio Spadaro, edito da Loyola Press (in Italia edito da Marsilio). La docu-serie rappresenta un racconto corale sulla terza età come tesoro da riscoprire, narrato da un punto di vista inedito ed originale: gli occhi delle giovani generazioni.', '2020-12-17', '04:37:55', 76),
(60, 'New entry!', 'Non è magnifica?', '2020-12-17', '04:41:09', 77),
(61, 'Rinvaso in corso!', 'Che fatica, ma il risultato è valso la pena!', '2020-12-17', '04:41:58', 77),
(62, 'Tante idee per i vostri lavori!', 'scoprirete tante idee creative per arredare casa con il fai da te. Con il bricolage e la giusta dose di ispirazione, il risultato può essere davvero sorprendente! I lavoretti fai da te ci permettono di esprimere la propria creatività e quella dei nostri bambini. Soprattutto il bricolage ci permette di creare decorazioni e arredi personalizzati per nostra abitazione.  Scoprite come realizzare splendidi mobili e decorazioni fai da te in giardino, creare una bella fioriera per il portico per la primavera, una splendida aiuola con le pietre o una bellissima fontana decorativa. Realizzate un dondolo con i bancali o una panchina da giardino con i blocchi di cemento.  Le cose da realizzare con il faidate sono infinite! Scoprite come personalizzare una cornice con materiali di recupero o realizzare un tavolo riciclando i bancali con un po’ di bricolage. Realizzate un bel quadro con legno di mare, decorate le pareti fai da te in modo originale e creativo. Anche in camera da letto si possono realizzare tante cose con il fai da te come per esempio comodini molto particolari con cassette di legno o vecchi cassetti.', '2020-12-17', '04:44:31', 78),
(75, 'pallavolo', 'La pallavolo (chiamata anche volley, abbreviazione dall\'inglese volleyball) è uno sport di squadra che si svolge tra due squadre formate da sei giocatori per ogni squadra. Lo scopo del gioco è realizzare punti in modo che la palla tocchi terra nel campo avversario (fase di attacco), separato da una rete alta 2,43 m nella maschile e 2,24 m nella femminile, e a impedire contemporaneamente che la squadra avversaria possa fare altrettanto (fase di difesa). Ogni squadra ha a disposizione un massimo di tre tocchi per inviare la palla nel campo opposto; i giocatori non possono bloccare, lanciare o trattenere la palla, che può essere giocata solo con tocchi netti (non è possibile fare doppio tocco sarebbe fallo con conseguente punto alla squadra avversaria). È presente nel programma dei Giochi olimpici estivi dal 1964[1] ed è uno degli sport più praticati.', '2021-01-02', '12:18:55', 92),
(77, 'Leggere come hobby', 'Vi piace leggere?', '2021-01-02', '12:35:24', 94);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `post_like`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `post_like` (
`Post` int(11)
,`TitoloP` varchar(50)
,`Testo` varchar(2000)
,`Blog` int(11)
,`TitoloB` varchar(100)
,`IDProp` int(11)
,`IDCollab` int(11)
,`nLike` bigint(21)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `registrato`
--

CREATE TABLE `registrato` (
  `IdU` int(11) NOT NULL,
  `Nome` varchar(30) NOT NULL,
  `Cognome` varchar(20) NOT NULL,
  `Eta` int(11) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Sesso` tinyint(1) NOT NULL,
  `Tel` varchar(10) NOT NULL,
  `DocuIde` varchar(20) NOT NULL,
  `Nickn` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `registrato`
--

INSERT INTO `registrato` (`IdU`, `Nome`, `Cognome`, `Eta`, `Email`, `Password`, `Sesso`, `Tel`, `DocuIde`, `Nickn`) VALUES
(20, 'Rocco Giuseppe Ferdinando', 'Lauria Iacovone', 33, 'roccolauria@gmail.com', '$2y$10$oz2K7O0sl0oKdV.vHbCQy.D5jgR.SzlUCgA.ENSHN33p3LO1WsjDG', 0, '3332565765', 'CA92763CG', 'Rokky33'),
(30, 'Nico', 'Pisano', 70, 'nico@gmail.com', '$2y$10$tpHQbASQ2CJZ/83bvhERkurm4WYHr6YJlXMRHSkull.6/OO6CdBf6', 0, '3556774933', 'CA56432CY', 'nicky66'),
(37, 'Simona', 'Sette', 23, 'simonasette17@gmail.com', '$2y$10$HDrJrGPKbjGQuDA6GiDmduOxdHRrpMDxW9KiWeW3UHk1kfFmF6mvC', 0, '3416774933', 'ZA56432ZA', 'Simona7'),
(39, 'Andrea', 'Milano', 23, 'andrea.milans97@virgilio.it', '$2y$10$0w.AnibKnXDc6vzaa0EUXO46oop2wfHtG06kHoW12nhVvj4O3owoi', 0, '3467884322', 'CA56432ZA', 'Andrea97'),
(40, 'Giuseppe', 'Lorenzin', 25, 'hei@gmail.com', '$2y$10$F2AvLrEtGcKYQHFfV.uFZOf/QR0EMwDXS64FjDVAfmjy3g.Ix0B3C', 0, '3467884343', 'YY56432ZA', 'jojo70'),
(41, 'Lorenzo', 'Felica', 56, 'ciao@libero.it', '$2y$10$DwZyK0fzCt6wnU2RDdHKBOPCl8FLvs2U97co6UzHkJqgkXHMzOufe', 0, '3464567890', 'UI56432CZ', 'Felica89');

-- --------------------------------------------------------

--
-- Struttura della tabella `segue`
--

CREATE TABLE `segue` (
  `IdU` int(11) NOT NULL,
  `IdB` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `segue`
--

INSERT INTO `segue` (`IdU`, `IdB`) VALUES
(20, 76),
(39, 70),
(40, 39),
(40, 70),
(40, 92);

-- --------------------------------------------------------

--
-- Struttura della tabella `sfondo`
--

CREATE TABLE `sfondo` (
  `IdSf` int(11) NOT NULL,
  `Sfondo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `sfondo`
--

INSERT INTO `sfondo` (`IdSf`, `Sfondo`) VALUES
(1, 'Verde'),
(2, 'Blu'),
(3, 'Nero'),
(4, 'Bianco'),
(5, 'Rosso'),
(6, 'Viola'),
(7, 'Giallo'),
(8, 'Arancione');

-- --------------------------------------------------------

--
-- Struttura della tabella `sottotema`
--

CREATE TABLE `sottotema` (
  `IdSt` int(11) NOT NULL,
  `sottoargomento` varchar(50) NOT NULL,
  `IdTema` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `sottotema`
--

INSERT INTO `sottotema` (`IdSt`, `sottoargomento`, `IdTema`) VALUES
(1, 'benessere', 15),
(2, 'società', 15),
(3, 'consigli', 15),
(4, 'domande', 15),
(5, 'idee', 15),
(6, 'generi', 4),
(7, 'strumenti', 4),
(8, 'tutorial', 4),
(9, 'consigli', 4),
(11, 'antica', 16),
(12, 'moderna', 16),
(13, 'romanzi per ragazzi', 16),
(14, 'novità', 16),
(15, 'idee', 16),
(16, 'arte moderna', 5),
(17, 'arti classiche', 5),
(18, '3D art', 5),
(19, 'idee', 5),
(20, 'cult', 6),
(21, 'popolari', 6),
(22, 'attori', 6),
(24, 'consigli', 6),
(25, 'proposte e idee', 6),
(27, 'low cost', 7),
(28, 'lusso', 7),
(30, 'idee, proposte e consigli', 7),
(33, 'Africa', 7),
(34, 'America', 7),
(35, 'Europa', 7),
(36, 'Asia', 7),
(37, 'Oceania', 7),
(43, 'riciclaggio', 8),
(44, 'inquinamento', 8),
(45, 'proposte e idee', 8),
(47, 'piante verdi', 10),
(48, 'piante grasse', 10),
(50, 'parassiti e muffe', 10),
(51, 'cure', 10),
(52, 'consigli , idee e domande', 10),
(55, 'attività ricreative', 11),
(56, 'handcrafting', 11),
(57, 'idee', 11),
(58, 'olimpiadi', 12),
(59, 'wellness', 12),
(60, 'palestra', 12),
(61, 'agonismo', 12),
(62, 'atletica', 12),
(63, 'acquatici', 12),
(64, 'calcio', 12),
(65, 'invernali', 12),
(66, 'individuali', 12),
(67, 'sport da combattimento', 12),
(68, 'pallavolo', 12),
(69, 'tennis', 12),
(70, 'basket', 12),
(71, 'sport con la palla', 12),
(72, 'finanza', 13),
(73, 'borsa', 13),
(74, 'domande', 13),
(75, 'idee', 13),
(76, 'manga', 18),
(77, 'anime', 18),
(78, 'cartoni occidentali', 18),
(79, 'fumetti', 18),
(80, 'Novità', 9),
(81, 'proposte', 9),
(82, 'occidentale', 9),
(83, 'orientale', 9),
(84, 'make-up e body painting', 17),
(85, 'vestiti ', 17),
(86, 'gioielli', 17),
(88, 'novità', 3),
(89, 'teorie', 3),
(90, 'teorie', 14),
(91, 'idee', 14),
(92, 'Ricette', 19),
(93, 'Idee e Consigli', 19),
(94, 'Videogiochi', 20),
(95, 'Giochi da tavolo', 20),
(96, 'Giochi di carte', 20),
(97, 'Idee e consigli', 20);

-- --------------------------------------------------------

--
-- Struttura della tabella `tema`
--

CREATE TABLE `tema` (
  `IdT` int(11) NOT NULL,
  `Argomento` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `tema`
--

INSERT INTO `tema` (`IdT`, `Argomento`) VALUES
(3, 'Medicina e Salute'),
(4, 'Musica'),
(5, 'Arti grafiche'),
(6, 'Cinema e serie TV'),
(7, 'Vacanze e Turismo'),
(8, 'Green'),
(9, 'Cultura'),
(10, 'Giardinaggio'),
(11, 'Hobby'),
(12, 'Sport'),
(13, 'Economia'),
(14, 'Religione'),
(15, 'Psicologia'),
(16, 'Letteratura'),
(17, 'Estetica e Moda'),
(18, 'Animazione e fumetti'),
(19, 'Cucina e Gastronomia'),
(20, 'Giochi');

-- --------------------------------------------------------

--
-- Struttura per vista `argblogview`
--
DROP TABLE IF EXISTS `argblogview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `argblogview`  AS  select `argblog`.`IdT` AS `IdT`,`argblog`.`IdSt` AS `IdSt`,`argblog`.`IdB` AS `IdB`,`blog`.`Titolo` AS `Titolo`,`blog`.`IdU` AS `IdU`,`blog`.`IdSf` AS `IdSf` from (`argblog` join `blog` on(`argblog`.`IdB` = `blog`.`IdB`)) ;

-- --------------------------------------------------------

--
-- Struttura per vista `blog_nick`
--
DROP TABLE IF EXISTS `blog_nick`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `blog_nick`  AS  select `blog`.`IdB` AS `IdB`,`blog`.`Titolo` AS `Titolo`,`blog`.`IdU` AS `IdU`,`registrato`.`Nickn` AS `Nickn`,`blog`.`IdSf` AS `IdSf`,`blog`.`IdFo` AS `IdFo`,`blog`.`IdUcollab` AS `IdUcollab`,`blog`.`Ereditato` AS `Ereditato` from (`blog` join `registrato` on(`registrato`.`IdU` = `blog`.`IdU`)) ;

-- --------------------------------------------------------

--
-- Struttura per vista `post_like`
--
DROP TABLE IF EXISTS `post_like`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `post_like`  AS  select `post`.`IdP` AS `Post`,`post`.`TitoloP` AS `TitoloP`,`post`.`TestoP` AS `Testo`,`blog`.`IdB` AS `Blog`,`blog`.`Titolo` AS `TitoloB`,`blog`.`IdU` AS `IDProp`,`blog`.`IdUcollab` AS `IDCollab`,(select count(0) from `apprezza` where `apprezza`.`IdP` = `post`.`IdP`) AS `nLike` from (`post` join `blog` on(`blog`.`IdB` = `post`.`IdB`)) order by (select count(0) from `apprezza` where `apprezza`.`IdP` = `post`.`IdP`) desc ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `apprezza`
--
ALTER TABLE `apprezza`
  ADD KEY `IdP` (`IdP`),
  ADD KEY `IdU` (`IdU`);

--
-- Indici per le tabelle `argblog`
--
ALTER TABLE `argblog`
  ADD KEY `IdT` (`IdT`),
  ADD KEY `IdSt` (`IdSt`),
  ADD KEY `IdB` (`IdB`);

--
-- Indici per le tabelle `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`IdB`),
  ADD KEY `IdU` (`IdU`),
  ADD KEY `IdUcollab` (`IdUcollab`),
  ADD KEY `blog_ibfk_3` (`IdSf`),
  ADD KEY `blog_ibfk_4` (`IdFo`);

--
-- Indici per le tabelle `commenti`
--
ALTER TABLE `commenti`
  ADD PRIMARY KEY (`IdC`),
  ADD KEY `Idp` (`Idp`),
  ADD KEY `IdU` (`IdU`);

--
-- Indici per le tabelle `font`
--
ALTER TABLE `font`
  ADD PRIMARY KEY (`IdFo`);

--
-- Indici per le tabelle `img`
--
ALTER TABLE `img`
  ADD PRIMARY KEY (`IdI`),
  ADD KEY `Idp` (`Idp`);

--
-- Indici per le tabelle `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`IdP`),
  ADD KEY `IdB` (`IdB`);

--
-- Indici per le tabelle `registrato`
--
ALTER TABLE `registrato`
  ADD PRIMARY KEY (`IdU`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Nickn` (`Nickn`),
  ADD UNIQUE KEY `DocuIde` (`DocuIde`),
  ADD UNIQUE KEY `Tel` (`Tel`);

--
-- Indici per le tabelle `segue`
--
ALTER TABLE `segue`
  ADD KEY `IdU` (`IdU`),
  ADD KEY `IdB` (`IdB`);

--
-- Indici per le tabelle `sfondo`
--
ALTER TABLE `sfondo`
  ADD PRIMARY KEY (`IdSf`);

--
-- Indici per le tabelle `sottotema`
--
ALTER TABLE `sottotema`
  ADD PRIMARY KEY (`IdSt`),
  ADD KEY `IdTema` (`IdTema`);

--
-- Indici per le tabelle `tema`
--
ALTER TABLE `tema`
  ADD PRIMARY KEY (`IdT`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `blog`
--
ALTER TABLE `blog`
  MODIFY `IdB` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT per la tabella `commenti`
--
ALTER TABLE `commenti`
  MODIFY `IdC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT per la tabella `font`
--
ALTER TABLE `font`
  MODIFY `IdFo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `img`
--
ALTER TABLE `img`
  MODIFY `IdI` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT per la tabella `post`
--
ALTER TABLE `post`
  MODIFY `IdP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT per la tabella `registrato`
--
ALTER TABLE `registrato`
  MODIFY `IdU` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT per la tabella `sfondo`
--
ALTER TABLE `sfondo`
  MODIFY `IdSf` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `sottotema`
--
ALTER TABLE `sottotema`
  MODIFY `IdSt` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT per la tabella `tema`
--
ALTER TABLE `tema`
  MODIFY `IdT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `apprezza`
--
ALTER TABLE `apprezza`
  ADD CONSTRAINT `apprezza_ibfk_1` FOREIGN KEY (`IdP`) REFERENCES `post` (`IdP`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `apprezza_ibfk_2` FOREIGN KEY (`IdU`) REFERENCES `registrato` (`IdU`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `argblog`
--
ALTER TABLE `argblog`
  ADD CONSTRAINT `argblog_ibfk_1` FOREIGN KEY (`IdT`) REFERENCES `tema` (`IdT`),
  ADD CONSTRAINT `argblog_ibfk_2` FOREIGN KEY (`IdSt`) REFERENCES `sottotema` (`IdSt`),
  ADD CONSTRAINT `argblog_ibfk_3` FOREIGN KEY (`IdB`) REFERENCES `blog` (`IdB`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`IdU`) REFERENCES `registrato` (`IdU`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blog_ibfk_2` FOREIGN KEY (`IdUcollab`) REFERENCES `registrato` (`IdU`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `blog_ibfk_3` FOREIGN KEY (`IdSf`) REFERENCES `sfondo` (`IdSf`),
  ADD CONSTRAINT `blog_ibfk_4` FOREIGN KEY (`IdFo`) REFERENCES `font` (`IdFo`);

--
-- Limiti per la tabella `commenti`
--
ALTER TABLE `commenti`
  ADD CONSTRAINT `commenti_ibfk_1` FOREIGN KEY (`Idp`) REFERENCES `post` (`IdP`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commenti_ibfk_2` FOREIGN KEY (`IdU`) REFERENCES `registrato` (`IdU`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `img`
--
ALTER TABLE `img`
  ADD CONSTRAINT `img_ibfk_1` FOREIGN KEY (`Idp`) REFERENCES `post` (`IdP`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`IdB`) REFERENCES `blog` (`IdB`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `segue`
--
ALTER TABLE `segue`
  ADD CONSTRAINT `segue_ibfk_1` FOREIGN KEY (`IdU`) REFERENCES `registrato` (`IdU`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `segue_ibfk_2` FOREIGN KEY (`IdB`) REFERENCES `blog` (`IdB`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `sottotema`
--
ALTER TABLE `sottotema`
  ADD CONSTRAINT `sottotema_ibfk_1` FOREIGN KEY (`IdTema`) REFERENCES `tema` (`IdT`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
