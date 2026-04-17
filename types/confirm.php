<head> 
    <link rel="stylesheet" href="style.css">
</head>
<?php
$id = $_GET["id"];
?>
<h2>Weet je het zeker?!</h2>
<a href="Delete.php?id=<?php echo $id; ?>">Ja</a>
<a href="Details.php?id=<?php echo $id; ?>">Nee</a>
<?php
