<?php  
$id = $_GET['id'];

$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "testschool";

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
            or die("niet mogelijk om te verbinden");

$query = "SELECT id, firstname, middlename, lastname FROM persons WHERE id = ?";

$preparedquery = $dblink->prepare($query);
if(!$preparedquery) { 
    die("prepare niet gelukt:" . $dblink->error);
}

$preparedquery->bind_param("i", $id);
$preparedquery->execute();

$result = $preparedquery->get_result();

$row = $result->fetch_assoc();

if (!$row) {
    die("Person not found with ID: " . $id);
}

$firstname = $row['firstname'];
$middlename = $row['middlename'];
$lastname = $row['lastname'];

$preparedquery->close();
$dblink->close();

echo "<a href=\"..\index.php\">Main</a>" . "<br>";
?>

<form action="update.php" method="post" > 
    <p>Id</p>
    <input type="hidden" name="old_id" value="<?= $id ?>">
    <input type="text" name="id" value="<?= $id ?>">
    <p>firstname</p>
    <input type="text" name="firstname" value="<?= $firstname ?>"><br>
    <p>middlename</p>
    <input type="text" name="middlename" value="<?= $middlename ?>"><br>
    <p>lastname</p>
    <input type="text" name="lastname" value="<?= $lastname ?>"><br>
    <button type="submit" >versturen</button>
</form>
