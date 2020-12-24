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
        $action = "view";
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        }
        require("mysql.php");


        $query = "
            SELECT c.id, c.nume_prenume, c.functie, c.telefon, c.email, s.denumire
            FROM  societati s, soferi c
            WHERE c.id_societate=s.id
            ORDER BY s.denumire ASC";


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
									<li><a href="societati.php" >Societati</a></li>
									<li><a href="vehicule.php" >Vehicule</a></li>
									<li><a href="alerte.php">Alerte vehicule</a></li>
									<li><a href="soferi.php" class="activ">Șoferi</a></li>
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
    				<h1>Șoferi</h2>
    			</header>

                <?php
                    if ($action == "view") {
                        ?>
                    <table class="sortable">
                        <tr>
                          <th>Nume și prenume </th>
						  						<th>Funcție</th>
													<th>Societate</th>
						  <th>Telefon</th>
							<th>E-mail</th>
                          <th>Editează</th>
                          <th>Șterge</th>
                        </tr>
                        <?php
                            if (mysqli_num_rows($rezultat) > 0) {
                                // output data of each row
                                while ($row = mysqli_fetch_assoc($rezultat)) {
                                    echo "<tr>";
                                    echo "<td>" . $row["nume_prenume"] . "</a></td>";
                                    echo "<td>" . $row["functie"] . "</a></td>";
                                    echo "<td>" . $row["denumire"] . "</a></td>";
                                    echo "<td>" . $row["telefon"] . "</td>";
                                    echo "<td>" . $row["email"] . "</td>";

                                    echo "<td><a href='modsofer.php?id=" . $row["id"] . " '><img src='img/edit.png' alt='edit icon' width='32px'></a></td>";
                                    echo "<td><a href='soferi.php?id=" . $row["id"] . "&action=delete'><img src='img/delete.png' alt='delete icon' width='32px'></a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>Nu aveti șoferi</td></tr>";
                            } ?>
                    </table>
                    <button type="button"><a href="nousofer.php">Adăugare șofer</a></button>


                <?php
                    } else {
                                    if ($action == "delete") {
                                        //șterge sofer
                                        $query = "DELETE from soferi where id=".$_GET['id'];
                                        $result=mysqli_query($conexiune, $query);

                                        if (!$result) {
                                            echo mysqli_error($conexiune);
                                        } else {
                                            echo "<h2>Ștergere efectuată!</h2>";
                                            echo "<meta http-equiv=\"refresh\" content=\"2; URL='soferi.php'\" >";
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
    		<p>Copyright 2017 - Corpodean Alin</p>
    	</footer>
    </div>

</body>

</html>
