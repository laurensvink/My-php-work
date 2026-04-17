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

// ✅ Prepared statement met placeholder
$query = "SELECT id, label FROM streams WHERE id = ?";

$preparedquery = $dblink->prepare($query);
if (!$preparedquery) {
    die("prepare niet gelukt: " . $dblink->error);
}

// ✅ Bind parameter (i = integer)
$preparedquery->bind_param("i", $id);

$preparedquery->execute();

$result = $preparedquery->get_result();

while ($row = $result->fetch_assoc()) {
    echo $row['id'] . " - " . $row['label'] . "<br>";
    echo "<a href='form.php?id={$row['id']}'>Update types</a><br>";
}

?>

<div class="navigation">
    <a href="confirm.php?id=<?php echo $id; ?>">Delete</a><br>
    <a href="../index.php">Main</a><br>
</div>

<?php

$preparedquery->close();
$dblink->close();

?>