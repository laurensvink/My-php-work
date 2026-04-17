<head> 
    <link rel="stylesheet" href="style.css">
</head>

<div class="list">
<?php
$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die("niet mogelijk om te verbinden");

$query = "SELECT id, label FROM types";

$preparedquery = $dblink->prepare($query);
if(!$preparedquery) { 
    die("prepare niet gelukt:" . $dblink->error);
}

$preparedquery->execute();
$result = $preparedquery->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<a class='list-item' href='details.php?id={$row['id']}'>{$row['label']}</a>";
}

echo "<div class='bottom-links'>";
echo "<a href='../index.php'>Main</a>";
echo "<a href='form.php'>Nieuw type toevoegen</a>";
echo "</div>";

$preparedquery->close();
$dblink->close();
?>
</div>