<?php
$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$id = $_GET['id'];

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die("Niet mogelijk om te verbinden");


$query = "DELETE FROM streams WHERE id= ?
 LIMIT 1";

$preparedquery = $dblink->prepare($query);
if (!$preparedquery) { 
    die("Prepare niet gelukt: " . $dblink->error);
}

$preparedquery->bind_param("i", $id);

if ($preparedquery->execute()) {
    echo "Record succesvol aangepast" . "<br>";
} else {
    echo "Fout bij aanpassen: " . $preparedquery->error . "<br>";
}

echo "<a href=\"index.php\">Main</a>";

$preparedquery->close();
$dblink->close();
?>