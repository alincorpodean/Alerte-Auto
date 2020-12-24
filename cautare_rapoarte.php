<?php
require("mysql.php");

//$output = array('success' => false, 'messages' => array());
$output = array('data' => array());


$data_inceput= $_POST['data_inceput'];
$data_sfarsit = $_POST['data_sfarsit'];
$sql = "select * from alerte WHERE data_sfarsit <= '".$data_sfarsit."'";
$res=mysqli_query($conexiune,$sql);

while ($row = mysqli_fetch_array($res)) {
    $output['data'][] = array(

        $row['nr_alerta'],
        $row['denumire_alerta'],
        $row['data_inceput'],
    );

}

// close database connection
$conexiune->close();

echo json_encode($output);

?>