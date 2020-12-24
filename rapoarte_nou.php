<!DOCTYPE HTML>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Aplicatie alerte auto</title>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/reset.css" rel="stylesheet">
    <link href="css/page.css" rel="stylesheet">
	<script src="js/sorttable.js" type="text/javascript"></script>

</head>

<body>
    <?php
        $action = "view";
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        }
        require("mysql.php");


        $query = "
            SELECT a.id, a.data_inceput, a.data_sfarsit, a.denumire_alerta, s.denumire as societate, v.nr_inmatriculare
            FROM alerte a, societati s, vehicule v
            WHERE a.id_societate=s.id and a.id_vehicul=v.id
            ORDER BY a.data_sfarsit DESC, s.denumire ASC";


        $rezultat = mysqli_query($conexiune, $query) or die ('Eroare: '.$query);




    ?>
    <div id="page-wrap">

    	<header id="page">
            <div id="logo">
                <img src="img/logo.png" alt="Alerte logo" width="70px">
            </div>
				    		<nav>
				    			<ul>
										<li><a href="index.php" >Acasă</a></li>
										<li><a href="societati.php" >Societati</a></li>
										<li><a href="vehicule.php" >Vehicule</a></li>
										<li><a href="alerte.php">Alerte vehicule</a></li>
										<li><a href="soferi.php" >Șoferi</a></li>
										<li><a href="alerte_soferi.php" >Alerte șoferi</a></li>
										<li><a href="reparatii.php" >Reparații</a></li>
                    <li><a href="rapoarte.php">Rapoarte</a></li>
                    <li><a href="rapoarte_nou.php" class="activ">Rapoarte noi</a></li>
                    <li class="search">
                        <a href="cautare.php"><span class="icon"><i class="fa fa-search"></i></span>
                        <span class="text">Cautare</span></a>
                    </li>
				    			</ul>
				    		</nav>
    	</header>



        <div class="dropdown">
          <button class="dropbtn">Dropdown</button>
          <div class="dropdown-content">
            <a href="#">Link 1</a>
            <a href="#">Link 2</a>
            <a href="#">Link 3</a>
          </div>
        </div>


    	<section id="main">


    		<article>
    			<header>
    				<h1>Despre noi</h2>
    			</header>
                <p>Coordonator SGBD: <a href="mailto:mmuntean@uab.ro">Lect. Univ. Dr. Maria Muntean</a></p>
    			<p>Coordonator WEB: <a href="mailto:ccucu@uab.ro">Lect. Univ. Dr. Ciprian Cucu</a></p>
    		</article>

    	</section>

    	<footer>
    		<p>Copyright 2017 - Corpodean Alin</p>
    	</footer>
    </div>

</body>

</html>
