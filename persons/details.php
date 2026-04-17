<head> 
    <link rel="stylesheet" href="style.css">
</head>
<?php

$id = $_GET['id'];

$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
            or die("niet mogelijk om te verbinden");


$query = "
SELECT firstname, middlename, lastname, name
FROM persons
LEFT JOIN orders ON persons.id = orders.personid
LEFT JOIN streams ON orders.streamid = streams.id
WHERE persons.id = ?
";


$preparedquery = $dblink->prepare($query);
if(!$preparedquery) { 
    die("prepare niet gelukt:" . $dblink->error);
}
$preparedquery->bind_param("i", $id);
$preparedquery->execute();

$result = $preparedquery->get_result();
$shownNames = [];

while ($row = $result->fetch_assoc()) {
    $fullName = $row['firstname'] . " " . $row['middlename'] . " " . $row['lastname'];

    if (!in_array($fullName, $shownNames)) {
        echo $fullName . "<br>";
        $shownNames[] = $fullName;
    }

    echo "<div class='stream-line'>Stream: " . $row['name'] . "</div>";
}


echo "<a href='form.php?id=$id'>Update Form</a><br>";
echo "<a href='confirm.php?id=$id'>Delete</a><br>";
echo "<a href='../index.php'>Main</a><br>"; 

$preparedquery->close();
$dblink->close();
?>
