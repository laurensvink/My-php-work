<?php  
$id = $_GET['id'];

$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
            or die("niet mogelijk om te verbinden");

$query = "SELECT id, label FROM types WHERE id=?";

$preparedquery = $dblink->prepare($query);
if(!$preparedquery) { 
    die("prepare niet gelukt:" . $dblink->error);
}

$preparedquery->bind_param("i", $id);
$preparedquery->execute();

$result = $preparedquery->get_result();

$row = $result->fetch_assoc();

$label = $row['label'];


echo "<a href=\"..\index.php\">Main</a>" . "<br>";
?>

<form action="save.php" method="post" >
    <p>Id</p>
    <input type="hidden" name="old_id" value="<?= $id ?>">
    <input type="text" name="id" value="<?= $id ?>" readonly>
    <p>label</p>
    <input type="text" name="label" value="<?= $label ?>"><br>
    <button type="submit" >Update</button>
</form>
