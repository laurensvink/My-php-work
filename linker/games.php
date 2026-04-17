<head>
    <title>Games</title>
</head>
<body>
    <h1>Dit is een pagina vol met Games</h1>
    <p></p>
    <p></p>
    <p></p>

<?php
$game = $_GET['game'];
if ($game == "1") {
    echo '<a href="https://shellshock.io/">Shell Shockers</a>';
}
if ($game == "2") {
    echo '<a href="https://www.rocketleague.com/en/">Rocket league</a>';
}
if ($game == "3") {
    echo '<a href="https://playvalorant.com/en-gb/">Valorant</a>';
}
?>
    <br>
    <a href="index.php">Terug naar homepage</a>
    <br>
    <a href="contact.php">KLIK HIER OM NAAR CONTACT GEGEVENS TE GAAN!</a>
</body>
</html>