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
                    <li><a href="societati.php" class="activ">Societati</a></li>
										<li><a href="vehicule.php" >Vehicule</a></li>
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
    				<h1>Alerte</h2>
    			</header>


                <?php
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];

                        require("mysql.php");

                        if (isset($_POST['submit'])) {
                            //daca s-a efectuat trimiterea formularului
                            //actualizăm înregistrarea în baza de date
                            $query = "UPDATE societati
                                SET denumire='".$_POST['denumire']."',
                                 cui='". $_POST['cui'] ."',
                                 nr_reg_com='". $_POST['nr_reg_com'] ."',
                                 pers_contact='". $_POST['pers_contact'] ."',
                                 telefon='". $_POST['telefon'] ."',
                                 localitate='". $_POST['localitate'] ."',
																 email='". $_POST['email'] ."'
                                 WHERE id=".$id;

                            $result=mysqli_query($conexiune, $query);
                            if (!$result) {
                                echo mysqli_error($conexiune);
                            } else {
                                echo "<h2>Modificare efectuată cu success!</h2>";
                                echo "<p>Înapoi la <a href='societati.php'>societati</a>";
                            }
                        } else {
                            //dacă nu s-a efectuat trimiterea, înseamnă că trebuie să afișăm formularul
                            $query = "SELECT *
                                FROM societati
                                WHERE societati.id=".$id;

                            $rezultat = mysqli_query($conexiune, $query) or die('Eroare');
                            $row=mysqli_fetch_assoc($rezultat); ?>
                            <form id="editSocietate" action="modsocietate.php?id=<?=$id?>" method="post">

                                <div>
                                    <label for="denumire">Denumire:</label>
                                    <input type="text" name="denumire" id="denumire" value="<?=$row["denumire"]?>" >
                                </div>
                                <div>
                                    <label for="denumire">C.U.I. :</label>
                                    <input type="text" name="cui" id="cui" value="<?=$row["cui"]?>" >
                                </div>
                                <div>
                                    <label for="nr_reg_com">Nr. Reg. Com.:</label>
                                    <input type="text" name="nr_reg_com" id="nr_reg_com" value="<?=$row["nr_reg_com"]?>" >
                                </div>
                                <div>
                                    <label for="pers_contact">Persoana contact:</label>
                                    <input type="text" name="pers_contact" id="pers_contact" value="<?=$row["pers_contact"]?>" >
                                </div>
                                <div>
                                    <label for="telefon">Telefon:</label>
                                    <input type="text" name="telefon" id="telefon" value="<?=$row["telefon"]?>" >
                                </div>
                                <div>
                                    <label for="localitate">Localitate:</label>
                                    <input type="text" name="localitate" id="localitate" value="<?=$row["localitate"]?>" >
                                </div>
																<div>
																		<label for="email">E-mail:</label>
																		<input type="text" name="email" id="email" value="<?=$row["email"]?>" >
																</div>

                              <div id="send">
                                <input type="submit" name="submit" value="Submit">
                              </div>

                            </form>

                            <?php
                        }
                        mysqli_close($conexiune);
                    } else {
                        echo "<p>Lipsă paramentru (nu știu ce alerta să modific)</p>";
                        echo "<p>Mergeți înapoi la <a href='societati.php'>societati</a> și selectați unul</p>";
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
    		<p>Copyright 2017 - Corpodean Alin</p>
    	</footer>
    </div>

</body>

</html>
