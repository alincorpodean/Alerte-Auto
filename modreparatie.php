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
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="css/page.css" />
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

    <div id="page-wrap">

    	<header id="page">
            <div id="logo">
                <img src="img/logo.png" alt="Alerte logo" width="70px">
            </div>
				    		<nav>
				    			<ul>
										<li><a href="index.php" >Acasă</a></li>
										<li><a href="index.php" >Acasă</a></li>
										<li><a href="societati.php" >Societati</a></li>
										<li><a href="vehicule.php" >Vehicule</a></li>
										<li><a href="alerte.php" >Alerte vehicule</a></li>
										<li><a href="soferi.php" >Șoferi</a></li>
										<li><a href="alerte_soferi.php" >Alerte șoferi</a></li>
										<li><a href="reparatii.php" class="activ">Reparații</a></li>
										<li><a href="rapoarte.php">Rapoarte</a></li>
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
    				<h1>Reparații</h2>
    			</header>


                <?php
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];

                        require("mysql.php");

                        if (isset($_POST['submit'])) {
                            //daca s-a efectuat trimiterea formularului
                            //actualizăm înregistrarea în baza de date
                            $query = "UPDATE reparatii
                                SET
                                 data='".$_POST['data']."',
                                 service='". $_POST['service'] ."',
                                 reparatie='". $_POST['reparatie'] ."',
                                 valoare='". $_POST['valoare'] ."',
																 id_vehicul='". $_POST['vehicul'] ."',
																 id_societate='". $_POST['societate'] ."',
                                 km='". $_POST['km'] ."'
                                 WHERE id=".$id;
                            //echo $query;die();
                            $result=mysqli_query($conexiune, $query);
                            if (!$result) {
                                echo mysqli_error($conexiune);
                            } else {
                                echo "<h2>Modificare efectuată cu success!</h2>";
                                echo "<p>Înapoi la <a href='reparatii.php'>reparatii</a>";
                            }
                        } else {
                            //dacă nu s-a efectuat trimiterea, înseamnă că trebuie să afișăm formularul
                            $query = "SELECT * FROM reparatii WHERE id=".$id;

                            $rezultat = mysqli_query($conexiune, $query) or die('Eroare');
                            $reparatii=mysqli_fetch_assoc($rezultat);

                            //scoatem și lista de societati să o afișăm în SELECT
                            $sql_societati = "SELECT id, denumire FROM societati ORDER BY denumire ASC";
                            $result_societati = mysqli_query($conexiune, $sql_societati);

                            //scoatem și lista de vehicule să o afișăm în SELECT
                            $sql_vehicule = "SELECT id, nr_inmatriculare FROM vehicule ORDER BY nr_inmatriculare ASC";
                            $result_vehicule = mysqli_query($conexiune, $sql_vehicule); ?>
                            <form id="editreparatie" action="modreparatie.php?id=<?=$id?>" method="post">

                                <div>
                                <label for="societate">Societate:</label>
                                    <select name="societate" id="societate" >
                                        <?php
                                            while ($societate = mysqli_fetch_assoc($result_societati)) {
                                                if ($societate['id']==$reparatii['id_societate']) {
                                                    //facem sa fie preselectat acea societate care este curent pe reparatie
                                                    echo "<option value=\"".$societate[id]."\" selected>".$societate['denumire']."</option>";
                                                } else {
                                                    echo "<option value=\"".$societate[id]."\">".$societate['denumire']."</option>";
                                                }
                                            } ?>
                                    </select>
                                </div>
																<div>
                                <label for="vehicul">Vehicul:</label>
                                    <select name="vehicul" id="vehicul" >
                                        <?php
                                            while ($vehicul = mysqli_fetch_assoc($result_vehicule)) {
                                                if ($vehicul['id']==$reparatii['id_vehicul']) {
                                                    //facem sa fie preselectat acea societate care este curent pe reparatie
                                                    echo "<option value=\"".$vehicul[id]."\" selected>".$vehicul['nr_inmatriculare']."</option>";
                                                } else {
                                                    echo "<option value=\"".$vehicul[id]."\">".$vehicul['nr_inmatriculare']."</option>";
                                                }
                                            } ?>
                                    </select>
                                </div>
                                <div>
                                    <label for="reparatie">Reparație:</label>
                                    <input type="text" name="reparatie" id="reparatie" value="<?=$reparatii["reparatie"]?>" >
                                </div>
                                <div>
                                    <label for="km">Numar KM:</label>
                                    <input type="int" name="km" id="km" value="<?=$reparatii["km"]?>" >
                                </div>
                                <div>
                                    <label for="valoare">Valoare reparație:</label>
                                    <input type="float" name="valoare" id="valoare" value="<?=$reparatii["valoare"]?>" >
                                </div>
																<div>
                                    <label for="data">Data reparației:</label>
                                    <input type="date" name="data" id="data" value="<?=$reparatii["data"]?>" >
                                </div>
																<div>
                                    <label for="service">Service:</label>
                                    <input type="text" name="service" id="service" value="<?=$reparatii["service"]?>" >
                                </div>


                              <div id="send">
                                <input type="submit" name="submit" value="Submit">
                              </div>

                            </form>

                            <?php
                        }
                        mysqli_close($conexiune);
                    } else {
                        echo "<p>Lipsă paramentru (nu știu ce reparatie să modific)</p>";
                        echo "<p>Mergeți înapoi la <a href='reparatii.php'>reparatii</a> și selectați unul.</p>";
                    }

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
