<head> 
    <link rel="stylesheet" href="style.css">
</head>
<?php
$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
            or die("niet mogelijk om te verbinden");


$query = "SELECT id, name, provider, medium, prijs FROM streams";

$preparedquery = $dblink->prepare($query);
if(!$preparedquery) { 
    die("prepare niet gelukt:" . $dblink->error);
}

$preparedquery->execute();

$result = $preparedquery->get_result();

?>
<?php

while ($row = $result->fetch_assoc()) {
    echo "<a href='details.php?id={$row['id']}'>{$row['name']}</a><br>";
}
echo "<a href=\"..\index.php\">Main</a>" . "<br>";
echo "<a href=\"form.php\">Nieuwe stream toevoegen</a>";
$preparedquery->close();
$dblink->close();


?>

