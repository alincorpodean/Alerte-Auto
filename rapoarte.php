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
  <link href="js/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
	   <br>
	   <?php  if (isset($_SESSION['username'])) : ?>
	     <p style="text-align:right; margin-right:20px; font-style:italic">Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
	     <p style="text-align:right; margin-right:20px"> <a href="index.php?logout='1'" style="color: red;">Logout</a> </p>
	   <?php endif ?>


		<?php
            require("mysql.php");
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
					<li><a href="rapoarte.php" class="activ">Rapoarte</a></li>
					<li><a href="rapoarte_nou.php" >Rapoarte noi</a></li>
					<li class="search">
					<a href="cautare.php"><span class="icon"><i class="fa fa-search"></i></span>
						<span class="text">Cautare</span></a>
					</li>
    			</ul>
    		</nav>
    	</header>

    	<section id="main">
			<header>
				<h1>Caută alerte in perioada:</h2>
			</header>
			<div class="card-header py-3">
                <form action="rapoarte.php" method="POST">
					<label id="text_inceput">Data inceput: </label><input type="date" id="data_inceput" name="data_inceput" required />
                    <label id="text_sfarsit">Data sfarsit: </label><input type="date" id="data_sfarsit" name="data_sfarsit" required />
                    <button type="submit" value="da" class="btn btn-primary btn-sm float-right" name="CautaModalBtn">Cauta</button>
                </form>
            </div>


            <div class="table-responsive" id="tabel_rezultat">
				<?php
                    $cauta="nu";
                    if (!empty($_POST['CautaModalBtn'])) {
                        $cauta = $_POST['CautaModalBtn'];
                    }
                    if ($cauta=="da") {
                        ?>

					<header>
						<p>Alerte <b>mașini</b> care expiră in perioada selectată:</p>
					</header>

						<table id="rapoarte" class="table-striped display general-table table table-bordered  table-hover table-sm" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Alerta</th>
									<th>Data limita</th>
									<th>Vehicul</th>
									<th>Societate</th>
									<th>Telefon</th>
								</tr>
							</thead>

							<?php
                                $data_inceput= $_POST['data_inceput'];
                        $data_sfarsit = $_POST['data_sfarsit'];
                        $sql = "select a.denumire_alerta, a.data_inceput, a.data_sfarsit, s.denumire, s.telefon, v.nr_inmatriculare
										from alerte a, societati s, vehicule v
										WHERE a.id_societate=s.id and a.id_vehicul=v.id and '".$data_inceput."' <= a.data_sfarsit and a.data_sfarsit <= '".$data_sfarsit."'" ;
                        $res=mysqli_query($conexiune, $sql);
                        if (mysqli_num_rows($res)>0) {
                            while ($row = mysqli_fetch_array($res)) {
                                echo "
											<tr>
												<td>".$row['denumire_alerta']."</td>
												<td>".$row['data_sfarsit']."</td>
												<td>".$row['nr_inmatriculare']."</td>
												<td>".$row['denumire']."</td>
												<td>".$row['telefon']."</td>
											</tr>
											";
                            }
                        } else {
                            echo "<tr><td>Nu sunt inregistrari</td></tr>";
                        } ?>
						</table>

					<div style="float: right; width: 160px">
						<button class="button_export" type="button"><a href="export_alerte_vehicule.php">Alerte vehicule XLS</a></button>
					</div>

					<br>
					<br>
					<br>

					<header>
						<p>Alerte <b>șoferi</b> care expiră in perioada selectată:</p>
					</header>

					<table id="rapoarte" class="table-striped display general-table table table-bordered  table-hover table-sm" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Alerta</th>
							<th>Data limita</th>
							<th>Șofer</th>
							<th>Telefon</th>
							<th>E-mail</th>
						</tr>
					</thead>

						<?php
                        $data_inceput= $_POST['data_inceput'];
                        $data_sfarsit = $_POST['data_sfarsit'];
                        $sql = "select a.denumire_alerta, a.data_sfarsit, c.telefon, c.email, c.nume_prenume
								from alerte_soferi a, soferi c
								WHERE a.id_sofer=c.id and '".$data_inceput."' <= a.data_sfarsit and a.data_sfarsit <= '".$data_sfarsit."'
								ORDER BY a.data_sfarsit DESC";
                        $res=mysqli_query($conexiune, $sql);
                        if (mysqli_num_rows($res)>0) {
                            while ($row = mysqli_fetch_array($res)) {
                                echo "
									<tr>
										<td>".$row['denumire_alerta']."</td>
										<td>".$row['data_sfarsit']."</td>
										<td>".$row['nume_prenume']."</td>
										<td>".$row['telefon']."</td>
										<td>".$row['email']."</td>
									</tr>
																	";
                            }
                        } else {
                            echo "<tr><td>Nu sunt inregistrari</td></tr>";
                        } ?>
					</table>

					<div style="float: right; width: 145px">
						<button class="button_export" type="button"><a href="export_alerte_soferi.php">Alerte soferi XLS</a></button>
					</div>

					<br>
					<br>
					<br>

					<header>
						<p><b>Reparații</b> in perioada selectată:</p>
					</header>

					<table id="rapoarte" class="table-striped display general-table table table-bordered  table-hover table-sm" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Data</th>
							<th>Vehicul</th>
							<th>Service</th>
							<th>Reparatie</th>
							<th>Valoare</th>
							<th>KM</th>
						</tr>
					</thead>

						<?php
                        $data_inceput= $_POST['data_inceput'];
                        $data_sfarsit = $_POST['data_sfarsit'];
                        $sql = "select r.data, r.service, r.reparatie, ROUND(r.valoare,2) as Valoare, r.km, v.nr_inmatriculare
                        from reparatii r, vehicule v
                        WHERE r.id_vehicul=v.id and '".$data_inceput."' <= r.data and r.data <= '".$data_sfarsit."'
                        ORDER BY r.data DESC";
                        $res=mysqli_query($conexiune, $sql);
                        if (mysqli_num_rows($res)>0) {
                            while ($row = mysqli_fetch_array($res)) {
                                echo "
									<tr>
										<td>".$row['data']."</td>
										<td>".$row['nr_inmatriculare']."</td>
										<td>".$row['service']."</td>
										<td>".$row['reparatie']."</td>
										<td>".$row['Valoare']."</td>
										<td>".$row['km']."</td>
									</tr>
																	";
                            }
                        } else {
                            echo "<tr><td>Nu sunt inregistrari</td></tr>";
                        }
                    }

                        ?>
					</table>

					<div style="float: right; width: 125px">
						<button class="button_export" type="button"><a href="export_reparatii.php">Reparatii XLS</a></button>
					</div>

			</div>

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
