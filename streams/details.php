<?php

$id = $_GET['id'];

$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
            or die("niet mogelijk om te verbinden");


$query = "SELECT id, the_name, provider, midium, prijs FROM streams WHERE id=?";

$preparedquery = $dblink->prepare($query);
if(!$preparedquery) { 
    die("prepare niet gelukt:" . $dblink->error);
}
$preparedquery->bind_param("i", $id);
$preparedquery->execute();

$result = $preparedquery->get_result();
 $midium = NULL;
while ($row = $result->fetch_assoc()) {
    $midium = $row['midium'];
    echo $row['the_name'] . "-" . $row['provider'] . "-" . $row['prijs'] .  "<br>";
};


$query = "SELECT Label From types WHERE id= ?";
$preparedquery = $dblink->prepare($query);
if(!$preparedquery) { 
    die("prepare niet gelukt:" . $dblink->error);
}

$preparedquery->bind_param("i", $midium);
$preparedquery->execute();

$result = $preparedquery->get_result();
$row = $result->fetch_assoc();

if ($row) {
    $midium_label = $row['Label'];
} else {
    $midium_label = "?";
}

echo $midium_label;


echo "<a href='updateform.php?id={$row['id']}'>Update Form</a>" . "<br>";
echo "<a href=\"confirm.php?id=$id\">Delete</a>" . "<br>";
echo "<a href=\"..\index.php\">Main</a>" . "<br>";


$preparedquery->close();
$dblink->close();
?>
