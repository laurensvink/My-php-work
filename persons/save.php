<?php
$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$firstname = $_POST['firstname'];
$middlename = $_POST['middlename'];
$lastname = $_POST['lastname'];

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die("DB error");

if (isset($_POST['old_id'])) {
    $old_id = $_POST['old_id'];

    if (empty($firstname) || empty($lastname)) {
        die("Error: First Name and Last Name are required.");
    }

    $query = "UPDATE persons SET firstname=?, middlename=?, lastname=? WHERE id=?";
    $stmt = $dblink->prepare($query);
    $stmt->bind_param("sssi", $firstname, $middlename, $lastname, $old_id);

    if ($stmt->execute()) {
        echo "Record updated<br>";
        echo "<a href='overview.php'>Back to Overview</a><br>";
        echo "<a href='details.php?id=$old_id'>View Person</a>";
    } else {
        echo "Update error: " . $stmt->error;
    }
} else {
    if (empty($firstname) || empty($lastname)) {
        die("Error: First Name and Last Name are required.");
    }

    $query = "INSERT INTO persons (firstname, middlename, lastname) VALUES (?, ?, ?)";
    $stmt = $dblink->prepare($query);
    $stmt->bind_param("sss", $firstname, $middlename, $lastname);

    if ($stmt->execute()) {
        $new_id = $dblink->insert_id;
        echo "Record created<br>";
        echo "<a href='overview.php'>Back to Overview</a><br>";
        echo "<a href='details.php?id=$new_id'>View Person</a>";
    } else {
        echo "Create error: " . $stmt->error;
    }
}

$dblink->close();
?>