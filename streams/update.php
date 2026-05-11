<?php
$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$old_id = $_POST['old_id']; 
$new_id = $_POST['id'];
$the_name = $_POST['the_name'];
$provider = $_POST['provider'];
$medium = $_POST['medium'];
$prijs = $_POST['prijs'];


$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die("Niet mogelijk om te verbinden");


$query = "UPDATE streams 
          SET id = ?, the_name = ?, provider = ?, medium = ?, prijs = ?
          WHERE id = ?";

$preparedquery = $dblink->prepare($query);
if (!$preparedquery) { 
    die("Prepare niet gelukt: " . $dblink->error);
}


$preparedquery->bind_param("isssdi",$new_id,$the_name,$provider,$medium,$prijs,$old_id);

if ($preparedquery->execute()) {
    echo "Record succesvol aangepast";
} else {
    echo "Fout bij aanpassen: " . $preparedquery->error;
}

echo "<a href=\"index.php\">Main</a>";

$preparedquery->close();
$dblink->close();
?>