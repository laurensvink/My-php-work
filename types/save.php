<?php
$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$label = $_POST['label'];

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die("DB error");

if (isset($_POST['old_id'])) {
    // UPDATE mode
    $old_id = $_POST['old_id'];

    if (empty($label)) {
        die("Error: Label is required.");
    }

    $query = "UPDATE types SET label=? WHERE id=?";
    $stmt = $dblink->prepare($query);
    $stmt->bind_param("si", $label, $old_id);

    if ($stmt->execute()) {
        echo "Type updated<br>";
        echo "<a href='overview.php'>Back to Overview</a><br>";
        echo "<a href='details.php?id=$old_id'>View Type</a>";
    } else {
        echo "Update error: " . $stmt->error;
    }
} else {
    // CREATE mode
    if (empty($label)) {
        die("Error: Label is required.");
    }

    $query = "INSERT INTO types (label) VALUES (?)";
    $stmt = $dblink->prepare($query);
    $stmt->bind_param("s", $label);

    if ($stmt->execute()) {
        $new_id = $dblink->insert_id;
        echo "Type created<br>";
        echo "<a href='overview.php'>Back to Overview</a><br>";
        echo "<a href='details.php?id=$new_id'>View Type</a>";
    } else {
        echo "Create error: " . $stmt->error;
    }
}

$dblink->close();
?>