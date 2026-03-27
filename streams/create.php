<?php
$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$id = $_POST['id'];
$the_name = $_POST['the_name'];
$provider = $_POST['provider'];
$midium = $_POST['midium'];
$prijs = $_POST['prijs'];

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
            or die("niet mogelijk om te verbinden");


$query = "INSERT INTO streams (id,the_name,provider,midium,prijs) VALUES (?, ?, ?, ?, ?)";

$preparedquery = $dblink->prepare($query);
if(!$preparedquery) { 
    die("prepare niet gelukt:" . $dblink->error);
}


$preparedquery->bind_param("issii", $id, $the_name, $provider, $midium, $prijs);

if ($preparedquery->execute()) {
    echo "Record succesfull toegevoegd";
} else {
    echo "fout bij invoegen:" . $preparedquery->error;
}


echo "<a href=\"..\index.php\">Main</a>" . "<br>";


$preparedquery->close();
$dblink->close();


?>

