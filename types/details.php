<?php

$id = $_GET['id'];

$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
            or die("niet mogelijk om te verbinden");


$query = "SELECT id, Label, FROM streams WHERE id=$id";

$preparedquery = $dblink->prepare($query);
if(!$preparedquery) { 
    die("prepare niet gelukt:" . $dblink->error);
}

$preparedquery->execute();

$result = $preparedquery->get_result();

while ($row = $result->fetch_assoc()) {
    echo $row['id'] . "-" $row['Label'] . "<br>";
    echo "<a href='updateform.php?id={$row['id']}'>Update Form</a><br>";
};

$next = $id;

for ($i = 1; $i <= 5; $i++) {
    if ($i == $id) {
        $next = $i + 1;
        if ($next > 5) {
            $next = 1;
        }
        break;
    }
}

$previous = $id;

for ($i = 5; $i >= 1; $i--) {
    if ($i == $id) {
        $previous = $i - 1;
        if ($previous < 1) {
            $previous = 5;
        }
        break;
    }
}
echo "<a href=\"confirm.php?id=$id\">Delete</a>" . "<br>";
echo "<a href=\"..\index.php\">Main</a>" . "<br>";
echo '<a href="overview.php?id=' . $previous . '">Previous</a> ' . "<br>";
echo '<a href="overview.php?id=' . $next . '">Next Page</a>' . "<br>";


$preparedquery->close();
$dblink->close();
?>
