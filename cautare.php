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

    <?php

        require("mysql.php");
    ?>
    <div id="page-wrap">

    	<header id="page">
            <div id="logo">
                <img src="img/logo.png" alt="UAB logo" width="70px">
            </div>
				<nav>
				  <ul>
						<li><a href="index.php" >Acasă</a></li>
						<li><a href="societati.php" >Societati</a></li>
						<li><a href="vehicule.php" >Vehicule</a></li>
						<li><a href="alerte.php" >Alerte vehicule</a></li>
						<li><a href="soferi.php" >Șoferi</a></li>
						<li><a href="alerte_soferi.php" >Alerte șoferi</a></li>
						<li><a href="reparatii.php" >Reparații</a></li>
						<li><a href="rapoarte.php">Rapoarte</a></li>
            <li class="search">
              <a href="cautare.php" class="activ"><span class="icon"><i class="fa fa-search"></i></span>
              <span class="text" >Cautare</span></a>
						</li>
    			</ul>
    		</nav>
    	</header>

    	<section id="main">

    		<article>
    			<header>
    				<h1>Căutare alerta</h2>
    			</header>

                <form action="cautare.php" id="cautare" method="post">
                    <label for="caut">Căutare societate:</label>
                    <input type="text" name="caut" id="caut" value="" >
                    <input type="submit" name="submit" value="caută">
                </form>
    		</article>

            <?php
                if (isset($_POST['submit'])) {
                    $termen_cautare = $_POST['caut'];
                    $query = "SELECT * FROM societati WHERE denumire LIKE '%" . $termen_cautare . "%'";
                    $rezultat = mysqli_query($conexiune, $query) or die('Eroare');
                    $nr_rezultate = mysqli_num_rows($rezultat);

                    if ($nr_rezultate == 0) {
                        echo "<h2>Căutarea nu a produs rezultate.</h2>";
                    } else {
                        ?>

                <article>
            			<header>
            				<h2>Rezultate căutare</h2>
            			</header>
                        <p><strong>Am găsit <?php echo $nr_rezultate; ?> rezultate</strong></p>

												<table class="sortable">
												  <tr>
												    <th>Denumire</th>
														<th>Persoana de contact</th>
														<th>Telefon</th>
														<th>Localitate</th>
												  </tr>

                        <?php
                            while ($row = mysqli_fetch_assoc($rezultat)) {
                                echo "<tr>";
                                echo "<td>" . $row['denumire'] . "</a></td>";
                                echo "<td>" . $row['pers_contact'] . "</a></td>";
                                echo "<td>" . $row['telefon'] . "</a></td>";
                                echo "<td>" . $row['localitate'] . "</a></td>";
                            } ?>
											 </table>

            		</article>

            <?php
                    }
                }
            ?>


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
