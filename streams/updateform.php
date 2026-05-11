<?php  
$id = $_GET['id'];

$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
            or die("niet mogelijk om te verbinden");

$query = "SELECT id, name, provider, medium, prijs FROM streams WHERE id=$id";

$preparedquery = $dblink->prepare($query);
if(!$preparedquery) { 
    die("prepare niet gelukt:" . $dblink->error);
}

$preparedquery->execute();

$result = $preparedquery->get_result();

$row = $result->fetch_assoc();

$name = $row['name'];
$provider = $row['provider'];
$medium = $row['medium'];
$prijs = $row['prijs'];

echo "<a href=\"..\index.php\">Main</a>" . "<br>";
?>

<form action="save.php" method="post" > 
    <p>Id</p>
    <input type="hidden" name="old_id" value="<?= $id ?>">
    <input type="text" name="id" value="<?= $id ?>" readonly>
    <p>name</p>
    <input type="text" name="name" value="<?= $name ?>"><br>
    <p>provider</p>
    <input type="text"  name="provider" value="<?= $provider ?>"><br>
    <p>medium</p>
    <input type="text" name="medium" value="<?= $medium ?>"><br>
    <p>prijs</p>
    <input type="text" name="prijs" value="<?= $prijs ?>"><br>
    <button type="submit" >Update</button>
</form>
