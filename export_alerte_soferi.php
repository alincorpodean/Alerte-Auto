<?php
$user = 'root';
$password = '';
$server = 'localhost';
$database = 'alerte_auto';
$pdo = new PDO("mysql:host=$server;dbname=$database", $user, $password);

$sql = "select a.denumire_alerta as 'Denumire alerta', a.data_sfarsit as 'Data sfarsit', c.telefon as 'Telefon', c.email as 'E-mail', c.nume_prenume as 'Nume si prenume'
from alerte_soferi a, soferi c
WHERE a.id_sofer=c.id
ORDER BY a.data_sfarsit DESC";

$export = $pdo->query($sql);

//Retrieve the data from our table.
$rows = $export->fetchAll(PDO::FETCH_ASSOC);

//The name of the Excel file that we want to force the
//browser to download.
$filename = 'alerte_soferi.xls';

//Send the correct headers to the browser so that it knows
//it is downloading an Excel file.
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

//Define the separator line
$separator = "\t";

//If our query returned rows
if (!empty($rows)) {

                            //Dynamically print out the column names as the first row in the document.
    //This means that each Excel column will have a header.
    echo implode($separator, array_keys($rows[0])) . "\n";

    //Loop through the rows
    foreach ($rows as $row) {

                                //Clean the data and remove any special characters that might conflict
        foreach ($row as $k => $v) {
            $row[$k] = str_replace($separator . "$", "", $row[$k]);
            $row[$k] = preg_replace("/\r\n|\n\r|\n|\r/", " ", $row[$k]);
            $row[$k] = trim($row[$k]);
        }

        //Implode and print the columns out using the
        //$separator as the glue parameter
        echo implode($separator, $row) . "\n";
    }
}
