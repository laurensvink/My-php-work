<?php
$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$name = $_POST['name'];
$provider = $_POST['provider'];
$medium = $_POST['medium'];
$prijs = $_POST['prijs'];

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die("DB error");

if (isset($_POST['old_id'])) {
    // UPDATE mode
    $old_id = $_POST['old_id'];

    if (empty($name)) {
        die("Error: Name is required.");
    }

    $query = "UPDATE streams SET name=?, provider=?, medium=?, prijs=? WHERE id=?";
    $stmt = $dblink->prepare($query);
    $stmt->bind_param("ssidi", $name, $provider, $medium, $prijs, $old_id);

    if ($stmt->execute()) {
        echo "Stream updated<br>";
        echo "<a href='overview.php'>Back to Overview</a><br>";
        echo "<a href='details.php?id=$old_id'>View Stream</a>";
    } else {
        echo "Update error: " . $stmt->error;
    }
} else {
    // CREATE mode
    if (empty($name)) {
        die("Error: Name is required.");
    }

    $query = "INSERT INTO streams (name, provider, medium, prijs) VALUES (?, ?, ?, ?)";
    $stmt = $dblink->prepare($query);
    $stmt->bind_param("ssid", $name, $provider, $medium, $prijs);

    if ($stmt->execute()) {
        $new_id = $dblink->insert_id;
        echo "Stream created<br>";
        echo "<a href='overview.php'>Back to Overview</a><br>";
        echo "<a href='details.php?id=$new_id'>View Stream</a>";
    } else {
        echo "Create error: " . $stmt->error;
    }
}

$dblink->close();
?>