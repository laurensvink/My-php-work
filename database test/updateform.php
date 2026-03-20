<?php  
$id = $_GET['id'];

$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
            or die("niet mogelijk om te verbinden");

$query = "SELECT id, the_name, provider, medium, prijs FROM streams WHERE id=$id";

$preparedquery = $dblink->prepare($query);
if(!$preparedquery) { 
    die("prepare niet gelukt:" . $dblink->error);
}

$preparedquery->execute();

$result = $preparedquery->get_result();

$row = $result->fetch_assoc();

$the_name = $row['the_name'];
$provider = $row['provider'];
$medium = $row['medium'];
$prijs = $row['prijs'];

echo "<a href=\"index.php\">Main</a>";
?>

<form action="update.php" method="post" > 
    <p>Id</p>
    <input type="hidden" name="old_id" value="<?= $id ?>">
    <input type="text" name="id" value="<?= $id ?>">
    <p>name</p>
    <input type="text" name="the_name" value="<?= $the_name ?>"><br>
    <p>provider</p>
    <input type="text"  name="provider" value="<?= $provider ?>"><br>
    <p>medium</p>
    <input type="text" name="medium" value="<?= $medium ?>"><br>
    <p>prijs</p>
    <input type="text" name="prijs" value="<?= $prijs ?>"><br>
    <button type="sumbit" >versturen</button>
</form>
