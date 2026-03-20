<?php
$id = $_GET["id"];
echo "weet je het zeker?!" . "<br>";
echo "<a href=\"Delete.php?id=$id\">Ja</a>" . "<br>";
echo "<a href=\"Details.php?id=$id\">Nee</a>" . "<br>";

?>   