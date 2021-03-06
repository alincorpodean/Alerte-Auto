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
	<script src="js/sorttable.js" type="text/javascript"></script>

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
        $lista = true;
        $action = "view";
        if (isset($_GET['id'])) {
            $lista = false;
            $id = $_GET['id'];
            if (isset($_GET['action'])) {
                $action = $_GET['action'];
            }
        }
        require("mysql.php");

        if ($lista) {
            $query = "SELECT * FROM societati order by id ASC";
        } else {
            $query = "SELECT *
                FROM societati
                WHERE societati.id=".$id;
        }
        //echo $query;

        $rezultat = mysqli_query($conexiune, $query) or die('Eroare: '.$query);




    ?>
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
    				<h1>Societăți</h2>
    			</header>

                <?php
                    if ($action == "view") {
                        if (!$lista) {
                            echo "<p>Ați selectat societatea cu id " . $id . ". Detalii:</p>";
                        } else {
                            echo "<p>Lista societați: </p>";
                        } ?>
                    <table class="sortable">
                        <tr>
                          <th>Denumire</th>
                          <th>C.U.I.</th>
                          <th>Nr. înreg.</th>
                          <th>Pers. contact</th>
                          <th>Telefon</th>
                          <th>Localitate</th>
													<th>E-mail</th>
                          <th>Editează</th>
                          <th>Șterge</th>
                        </tr>
                        <?php

                            if (mysqli_num_rows($rezultat) > 0) {
                                // output data of each row
                                while ($row = mysqli_fetch_assoc($rezultat)) {
                                    echo "<tr>";
                                    echo "<td><a href='societati.php?id=".$row['id']."&action=view'>" . $row["denumire"] . "</a></td>";
                                    echo "<td>" . $row["cui"] . "</td>";
                                    echo "<td>" . $row["nr_reg_com"] . "</td>";
                                    echo "<td>" . $row["pers_contact"] . "</td>";
                                    echo "<td>" . $row["telefon"] . "</td>";
                                    echo "<td>" . $row["localitate"] . "</td>";
                                    echo "<td>" . $row["email"] . "</td>";
                                    echo "<td><a href='modsocietate.php?id=" . $row['id'] . " '><img src='img/edit.png' alt='edit icon' width='32px'></a></td>";
                                    echo "<td><a href='societati.php?id=" . $row['id'] . "&action=delete'><img src='img/delete.png' alt='delete icon' width='32px'></a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>Nu aveti societati</td></tr>";
                            } ?>
                    </table>
                    <button type="button"><a href="nousocietate.php">Adăugare societate</a></button>
                    <?php
                        //daca vedem o singura societate, scoatem si alertele ei
                        if (!$lista) {
                            echo "<p>Lista alerte societate:</p> ";
                            $query = "SELECT *
                                FROM alerte
                                WHERE id_societate=".$id;
                            $rezultat = mysqli_query($conexiune, $query) or die('Eroare'); ?>
                        <table>
                            <tr>
                                <th>Denumire alerta</th>
                                <th>Data sfârșit</th>
                                <th>Editează</th>
                                <th>Șterge</th>
                            </tr>
                        <?php
                            if (mysqli_num_rows($rezultat) > 0) {
                                // output data of each row
                                while ($row = mysqli_fetch_assoc($rezultat)) {
                                    echo "<tr>";
                                    echo "<td>" . $row["denumire_alerta"] . "</td>";
                                    echo "<td>" . $row["data_sfarsit"] . "</td>";
                                    echo "<td><a href='modalerta.php?id=".$row['id']." '><img src='img/edit.png' alt='edit icon' width='32px'></a></td>";
                                    echo "<td><a href='alerte.php?id=".$row['id']."&action=delete'><img src='img/delete.png' alt='delete icon' width='32px'></a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>Nu aveti alerte pentru aceasta societate.</td></tr>";
                            } ?>
                        </table>
                        <button type="button"><a href="noualerta.php?id=<?=$id?>">Adăugare alertă</a></button>
                        <?php
                        } ?>

                <?php
                    } else {
                        if ($action == "delete") {
                            //delete record
                            $query = "DELETE from societati where id=".$id;
                            $result=mysqli_query($conexiune, $query);

                            if (!$result) {
                                echo mysqli_error($conexiune);
                            } else {
                                echo "<h2>Ștergere efectuată!</h2>";
                                echo "<meta http-equiv=\"refresh\" content=\"2; URL='societati.php'\" >";
                            }
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
    		<p>Copyright 2020 - Corpodean Alin</a></p>
    	</footer>
    </div>

</body>

</html>
