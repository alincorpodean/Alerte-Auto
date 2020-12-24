<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        header('Location: login.php');
    }

    if (isset($_GET['logout'])) {
        session_unset($_SESSION['username']);
        session_destroy();
        debugger;
        header("Location: login.php");
        exit();
    }
?>

<!DOCTYPE HTML>

<html>

<head>



	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Aplicatie alerte auto</title>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/reset.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="css/site.css" />
   	<script type="text/javascript" src="js/cssrefresh.js"></script>



</head>

<body>
    <!-- notification message -->
   <?php if (isset($_SESSION['success'])) : ?>
     <div class="error success" >
       <h3>
         <?php
           echo $_SESSION['success'];
           unset($_SESSION['success']);
          ?>
       </h3>
     </div>
   <?php endif ?>

   <!-- logged in user information -->
   <br />
   <?php  if (isset($_SESSION['username'])) : ?>
     <p style="text-align:right; margin-right:20px; font-style:italic">Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
     <p style="text-align:right; margin-right:20px"> <a href="index.php?logout='1'" style="color: red;">Logout</a> </p>
   <?php endif ?>
   </div>


    <?php
        require("mysql.php");
        $query_societati = "SELECT * FROM societati order by id DESC limit 3";
        $lista_societati = mysqli_query($conexiune, $query_societati) or die('Eroare '.$query_societati);
        $query_alerte = "SELECT * FROM alerte WHERE data_sfarsit >= NOW() AND TO_DAYS(data_sfarsit) - TO_DAYS(NOW() ) < 30 order by data_sfarsit DESC";
        $lista_alerte = mysqli_query($conexiune, $query_alerte) or die('Eroare');
    ?>

    <div id="page-wrap">

    	<header id="page">
            <div id="logo">
                <img src="img/logo_alerte.png" alt="alerte logo" width="70px">
            </div>


	 <nav>
    		 <ul>
    				<li><a href="index.php" class="activ">Acasă</a></li>
                    <li><a href="societati.php" >Societăți</a></li>
										<li><a href="vehicule.php" >Vehicule</a></li>
                    <li><a href="alerte.php" >Alerte vehicule</a></li>
										<li><a href="soferi.php" >Soferi</a></li>
										<li><a href="alerte_soferi.php" >Alerte șoferi</a></li>


                  <!--
									<li class="dropdown">
                    	<button class="dropbtn">Alerte
                    	<i class="fa fa-caret-down"></i>
                    	</button>
                        	<div class="dropdown-content">
                            	<a href="alerte.php">Alerte masina</a>
                            	<a href="alerte_soferi.php">Alerte soferi</a>
                          </div>
                    </li>
									-->

                    <li><a href="reparatii.php" >Reparații</a></li>
										<li><a href="rapoarte.php">Rapoarte</a></li>
                    <li><a href="rapoarte_nou.php">Rapoarte noi</a></li>
                    <li class="search">
                        <a href="cautare.php"><span class="icon"><i class="fa fa-search"></i></span>
                        <span class="text">Cautare</span></a>
                    </li>
    			</ul>
    		</nav>
    	</header>

    	<section id="main">

    		<article>
    			<header>
    				<h1>Alerte auto</h2>
    			</header>
    			<p>Aplicația "Alerte auto" permite gestionarea alertelor cu privire la expirarea documentelor mașinii. </p>
				<p> </p>
            </article>

    		<article>
    			<header>
    				<h1>Despre noi</h2>
    			</header>
                <p>Coordonator SGBD: <a href="mailto:mmuntean@uab.ro">Lect. Univ. Dr. Maria Muntean</a></p>
    			<p>Coordonator WEB: <a href="mailto:ccucu@uab.ro">Lect. Univ. Dr. Ciprian Cucu</a></p>
    		</article>

    	</section>



    	<aside>

    		<h2>Societăți</h2>
            <ul>
                <?php
                    if (mysqli_num_rows($lista_societati) > 0) {
                        // output data of each row
                        while ($row = mysqli_fetch_assoc($lista_societati)) {
                            echo "<li> <a href='societati.php?id=" . $row["id"] ."'>" . $row["denumire"] ." - " . $row["localitate"] .")</a></li>";
                        }
                    } else {
                        echo "Nu aveti societati";
                    }
                ?>
            </ul>
            <h2>Alerte în următoarele 30 de zile</h2>
            <ul>
                <?php
                    if (mysqli_num_rows($lista_alerte) > 0) {
                        // output data of each row
                        while ($row = mysqli_fetch_assoc($lista_alerte)) {
                            echo "<li>
                                <a href='alerte.php?nr=" . $row["denumire_alerta"] ."'>" . $row["denumire_alerta"] . " - " . $row['data_sfarsit'] ."  </a>
                            </li>";
                        }
                    } else {
                        echo "Nu aveți alerte în următoarele 30 de zile";
                    }
                ?>
            </ul>
    	</aside>

    	<footer>
    		<p>Copyright 2020 - Alin Corpodean</p>
    	</footer>
    </div>

</body>
<?php
        mysqli_close($conexiune);
?>
</html>
