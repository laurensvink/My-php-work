<head> 
    <link rel="stylesheet" href="style.css">
</head>
<?php
$id = $_GET['id'] ?? 0;


$firstname = $middlename = $lastname = "";
$action = "save.php";
$title = "Add Person";
$button = "Create";

if ($id == 0) {

} else {
    $db = mysqli_connect("127.0.0.1", "root", "", "testschool") or die("DB error");
    $stmt = $db->prepare("SELECT firstname, middlename, lastname FROM persons WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        $firstname = $result['firstname'];
        $middlename = $result['middlename'];
        $lastname = $result['lastname'];
        $action = "save.php";
        $title = "Update Person";
        $button = "Update";
    }
    $db->close();
}
?>

<h1><?php echo $title; ?></h1>
<form action="<?php echo $action; ?>" method="post">
    <?php if ($id > 0) echo "<input type='hidden' name='old_id' value='$id'>"; ?>

    <p>First Name</p>
    <input name="firstname" value="<?php echo $firstname; ?>" required><br>

    <p>Middle Name</p>
    <input name="middlename" value="<?php echo $middlename; ?>"><br>

    <p>Last Name</p>
    <input name="lastname" value="<?php echo $lastname; ?>" required><br>

    <button><?php echo $button; ?></button>
</form>

<a href="overview.php">Back</a>


<br>
<a href="overview.php">Back to Overview</a>
