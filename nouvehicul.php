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
    <link href="css/page.css" rel="stylesheet">

</head>

<body>

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

    <div id="page-wrap">

    	<header id="page">
            <div id="logo">
                <img src="img/logo.png" alt="Alerte logo" width="70px">
            </div>
			    		<nav>
			    			<ul>
									<li><a href="index.php" >Acasă</a></li>
									<li><a href="societati.php" >Societati</a></li>
									<li><a href="vehicule.php" class="activ">Vehicule</a></li>
									<li><a href="alerte.php">Alerte vehicule</a></li>
									<li><a href="soferi.php" >Șoferi</a></li>
									<li><a href="alerte_soferi.php" >Alerte șoferi</a></li>
									<li><a href="reparatii.php" >Reparații</a></li>
									<li><a href="rapoarte.php">Rapoarte</a></li>
									<li class="search">
											<a href="cautare.php"><span class="icon"><i class="fa fa-search"></i></span>
											<span class="text">Cautare</span></a>
									</li>
			    			</ul>
			    		</nav>
    	</header>

    	<section id="main">

        <?php

            require("mysql.php");

            if (isset($_POST['submit'])) {

                //daca s-a efectuat trimiterea formularului
                //înregistrăm societatea nouă în baza de date

                if (isset($_GET['id'])) {
                    $id_societate = $_GET['id'];
                } else {
                    $id_societate = $_POST['societate'];
                }
                $nr_inmatriculare = $_POST['nr_inmatriculare'];
                $marca = $_POST['marca'];
                $model =$_POST['model'];


                $query = "INSERT INTO vehicule  (nr_inmatriculare, marca, model, id_societate)
                     VALUES ('$nr_inmatriculare', '$marca', '$model', '$id_societate');";
                $result = mysqli_query($conexiune, $query);


                if (!$result) {
                    echo mysqli_error($conexiune);
                } else {
                    echo "<h2>Inserare efectuată cu success!</h2>";
                    echo "<p>Înapoi la <a href='vehicule.php'>vehicule</a>";
                }
            } else {
                //dacă nu s-a efectuat trimiterea, înseamnă că trebuie să afișăm formularul
                //dacă s-a transmis un id de societate, îl preluăm, altfel facem un select cu vehiculele
                $societate = false;
                if (isset($_GET['id'])) {
                    $societate = true; //avem deja societate
                    $id = $_GET['id'];
                }
                if ($societate) {
                    $query = "SELECT denumire FROM societati WHERE id=".$id;
                    $result = mysqli_query($conexiune, $query);
                    $row = mysqli_fetch_assoc($result);
                } else {
                    $query = "SELECT id, denumire FROM societati ORDER BY denumire ASC";
                    $result = mysqli_query($conexiune, $query);
                }



                if (mysqli_num_rows($result) == 0) {
                    echo "<h2>Eroare la id-ul societatii, sau nu aveti societati.</h2>";
                    echo "<p>Mergeți la pagina <a href='societati.php'>societati</a>.</p>";
                    echo "<meta http-equiv=\"refresh\" content=\"4; URL='societati.php'\" >";
                } else {
                    ?>

    		<article>
    			<header>
    				<h1>Adăugare vehicul <?php if ($societate) {
                        echo " - societate " . $id .": ". $row['denumire'];
                    } ?></h2>
    			</header>
                         <form id="vehicul" action="nouvehicul.php<?php if ($societate) {
                        echo "?id=$id";
                    } ?>" method="post">

                                <?php

                                    if (!$societate) {
                                        //daca nu avem societatea trimisa prin parametru, afisam selector pentru societate

                                        echo "<div>";
                                        echo "<label for=\"societate\">societate:</label>";
                                        echo "<select name=\"societate\" id=\"societate\" >";
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<option value=\"".$row[id]."\">".$row['denumire']."</option>";
                                        }
                                        echo "</select>";
                                        echo "</div>";
                                    } ?>
								<div>
                                    <label for="nr_inmatriculare">Numar inmatriculare</label>
                                    <input type="text" name="nr_inmatriculare" id="nr_inmatriculare" value="" >
                                </div>
								<div>
                                    <label for="marca">Marca :</label>
                                    <input type="text" name="marca" id="marca" value="" >
                                </div>
                                <div>
                                    <label for="model">Model:</label>
                                    <input type="text" name="model" id="model" value="" >
                                </div>

                              <div id="send">
                                <input type="submit" name="submit" value="Salvează">
                              </div>

                            </form>

                        <?php
                }
            }
                    mysqli_close($conexiune);

                ?>
    		</article>


    		<article>
    			<header>
    				<h1>Despre noi</h2>
    			</header>
                <p>Coordonator SGBD: <a href="mailto:mmuntean@uab.ro">Lect. Univ. Dr. Maria Muntean</a></p>
    			<p>Coordonator WEB: <a href="mailto:ccucu@uab.ro">Lect. Univ. Dr. Ciprian Cucu</a></p>
    		</article>

    	</section>

    	<footer>
    		<p>Copyright 2020 - Corpodean Alin</p>
    	</footer>
    </div>

</body>

</html>
