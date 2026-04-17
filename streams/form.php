<head> 
    <link rel="stylesheet" href="style.css">
</head>
<?php
$id = isset($_GET['id']) ? $_GET['id'] : 0;

$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
            or die("niet mogelijk om te verbinden");

$query = "SELECT id, label FROM types order by id";
$preparedquery = $dblink->prepare($query);
if(!$preparedquery) {
    die("prepare niet gelukt:" . $dblink->error);
}
$preparedquery->execute();
$result = $preparedquery->get_result();
$levels = $result->fetch_all(MYSQLI_ASSOC);
$preparedquery->close();

// Default values for create mode
$name = $provider = $medium = $prijs = "";
$action = "save.php";
$title = "Add Stream";
$button = "Create";

if ($id > 0) {
    // Update mode - get existing data
    $query = "SELECT id, name, provider, medium, prijs FROM streams WHERE id=?";
    $preparedquery = $dblink->prepare($query);
    if(!$preparedquery) {
        die("prepare niet gelukt:" . $dblink->error);
    }
    $preparedquery->bind_param("i", $id);
    $preparedquery->execute();

    $result = $preparedquery->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $name = $row['name'];
        $provider = $row['provider'];
        $medium = $row['medium'];
        $prijs = $row['prijs'];
        $action = "save.php";
        $title = "Update Stream";
        $button = "Update";
    } else {
        die("Stream not found with ID: " . $id);
    }

    $preparedquery->close();
}

$dblink->close();
?>

<h1><?php echo $title; ?></h1>

<form action="<?php echo $action; ?>" method="post">
    <?php if ($id > 0) echo "<input type='hidden' name='old_id' value='$id'>"; ?>

    <p>Name</p>
    <input name="name" value="<?php echo $name; ?>" required><br>

    <p>Provider</p>
    <input name="provider" value="<?php echo $provider; ?>"><br>

    <p>Medium</p>
    <select name="medium">
        <?php
        foreach($levels as $row) {
            $selected = ($medium == $row['id']) ? 'selected' : '';
            echo "<option value='" . $row['id'] . "' $selected>" . $row['label'] . "</option>";
        }
        ?>
    </select><br>

    <p>Price</p>
    <input name="prijs" value="<?php echo $prijs; ?>" type="number" step="0.01"><br>

    <button><?php echo $button; ?></button>
</form>

<a href="overview.php">Back</a>