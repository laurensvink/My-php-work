<?php
$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$id = $_GET['id'];

if (empty($id)) {
    die("Error: No ID provided for deletion.");
}

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die("Niet mogelijk om te verbinden");


$query = "DELETE FROM persons WHERE id = ? LIMIT 1";

$preparedquery = $dblink->prepare($query);
if (!$preparedquery) { 
    die("Prepare niet gelukt: " . $dblink->error);
}

$preparedquery->bind_param("i", $id);

if ($preparedquery->execute()) {
    echo "Record succesvol verwijderd<br>";
    echo "<a href=\"overview.php\">Back to Overview</a>";
} else {
    echo "Fout bij verwijderen: " . $preparedquery->error . "<br>";
    echo "<a href=\"details.php?id=" . $id . "\">Back to Details</a>";
}

echo "<br><a href=\"..\index.php\">Main</a>";

$preparedquery->close();
$dblink->close();
?>