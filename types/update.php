<?php
$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$old_id = $_POST['old_id']; 
$new_id = $_POST['id'];
$Label = $_POST['Label'];


$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die("Niet mogelijk om te verbinden");


$query = "UPDATE streams 
          SET id = ?, Label = ?
          WHERE id = ?";

$preparedquery = $dblink->prepare($query);
if (!$preparedquery) { 
    die("Prepare niet gelukt: " . $dblink->error);
}


$preparedquery->bind_param("is",$new_id,$Label);

if ($preparedquery->execute()) {
    echo "Record succesvol aangepast";
} else {
    echo "Fout bij aanpassen: " . $preparedquery->error;
}

echo "<a href=\"..\index.php\">Main</a>" . "<br>";

$preparedquery->close();
$dblink->close();
?>