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

    		<article>
    			<header>
    				<h1>Vehicule</h2>
    			</header>


                <?php
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];

                        require("mysql.php");

                        if (isset($_POST['submit'])) {
                            //daca s-a efectuat trimiterea formularului
                            //actualizăm înregistrarea în baza de date
                            $query = "UPDATE vehicule
                                SET nr_inmatriculare='".$_POST['nr_inmatriculare']."',
                                 marca='". $_POST['marca'] ."',
                                 model='". $_POST['model'] ."',
                                 id_societate='". $_POST['societate'] ."'
                                 WHERE id=".$id;
                            //echo $query;die();
                            $result=mysqli_query($conexiune, $query);
                            if (!$result) {
                                echo mysqli_error($conexiune);
                            } else {
                                echo "<h2>Modificare efectuată cu success!</h2>";
                                echo "<p>Înapoi la <a href='vehicule.php'>vehicule</a>";
                            }
                        } else {
                            //dacă nu s-a efectuat trimiterea, înseamnă că trebuie să afișăm formularul
                            $query = "SELECT * FROM vehicule WHERE id=".$id;

                            $rezultat = mysqli_query($conexiune, $query) or die('Eroare');
                            $vehicul=mysqli_fetch_assoc($rezultat);

                            //scoatem și lista de societati să o afișăm în SELECT
                            $sql_societati = "SELECT id, denumire FROM societati ORDER BY denumire ASC";
                            $result_societati = mysqli_query($conexiune, $sql_societati); ?>
                            <form id="editvehicul" action="modvehicul.php?id=<?=$id?>" method="post">

                                <div>
                                <label for="societate">Societate:</label>
                                    <select name="societate" id="societate" >
                                        <?php
                                            while ($societate = mysqli_fetch_assoc($result_societati)) {
                                                if ($societate['id']==$vehicul['id_societate']) {
                                                    //facem sa fie preselectat acea societate care este curent pe vehicul
                                                    echo "<option value=\"".$societate[id]."\" selected>".$societate['denumire']."</option>";
                                                } else {
                                                    echo "<option value=\"".$societate[id]."\">".$societate['denumire']."</option>";
                                                }
                                            } ?>
                                    </select>
                                </div>
                                <div>
                                    <label for="denumire_vehicul">Numar inmatriculare :</label>
                                    <input type="text" name="nr_inmatriculare" id="nr_inmatriculare" value="<?=$vehicul["nr_inmatriculare"]?>" >
                                </div>
                                <div>
                                    <label for="marca">Marca:</label>
                                    <input type="text" name="marca" id="marca" value="<?=$vehicul["marca"]?>" >
                                </div>
                                <div>
                                    <label for="model">Model:</label>
                                    <input type="text" name="model" id="model" value="<?=$vehicul["model"]?>" >
                                </div>


                              <div id="send">
                                <input type="submit" name="submit" value="Submit">
                              </div>

                            </form>

                            <?php
                        }
                        mysqli_close($conexiune);
                    } else {
                        echo "<p>Lipsă paramentru (nu știu ce vehicul să modific)</p>";
                        echo "<p>Mergeți înapoi la <a href='vehicule.php'>vehicule</a> și selectați unul.</p>";
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
