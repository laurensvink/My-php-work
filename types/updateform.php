<?php  
$id = $_GET['id'];

$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
            or die("niet mogelijk om te verbinden");

$query = "SELECT id, Label FROM streams WHERE id=$id";

$preparedquery = $dblink->prepare($query);
if(!$preparedquery) { 
    die("prepare niet gelukt:" . $dblink->error);
}

$preparedquery->execute();

$result = $preparedquery->get_result();

$row = $result->fetch_assoc();

$label = $row['Label'];


echo "<a href=\"..\index.php\">Main</a>" . "<br>";
?>

<form action="update.php" method="post" > 
    <p>Id</p>
    <input type="hidden" name="old_id" value="<?= $id ?>">
    <input type="text" name="id" value="<?= $id ?>">
    <p>label</p>
    <input type="text" name="label" value="<?= $label ?>"><br>
    <button type="sumbit" >versturen</button>
</form>
