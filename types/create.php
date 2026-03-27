<?php
$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$id = $_POST['id'];
$label = $_POST['Label'];


$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
            or die("niet mogelijk om te verbinden");


$query = "INSERT INTO types (id, Label) VALUES (?, ?)";

$preparedquery = $dblink->prepare($query);
if(!$preparedquery) { 
    die("prepare niet gelukt:" . $dblink->error);
}


$preparedquery->bind_param("is", $id, $Label);

if ($preparedquery->execute()) {
    echo "Record succesfull toegevoegd";
} else {
    echo "fout bij invoegen:" . $preparedquery->error;
}


echo "<a href=\"..\index.php\">Main</a>" . "<br>";


$preparedquery->close();
$dblink->close();


?>

