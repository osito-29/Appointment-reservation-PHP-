<?php

include_once('tools.php');
include_once('userstorage.php');

$userstorage = new UserStorage();
$allusers = $userstorage->findAll();

$appdatum = $_GET['datum'];
$apptime = $_GET['time'];

$filteredusers = [];

foreach ($allusers as $user)
{
    if ($user['appdatum']==$appdatum && $user['apptime']==$apptime)
    {
        $filteredusers [] = $user;
    }
}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nemzeti Koronavírus Depó - Foglalás</title>

    <style>
    body {text-align: center;}
    h1   {color: blue;}
    p    {font-size: 20px;}
    </style>
</head>
<body>
    <h1>NemKoVID - oltásra jelentkezési felület</h1>
    <h2><?= $appdatum ?> <?= $apptime ?>  időpont részletei:</h2>
    <ul>
        <?php foreach ($filteredusers as $user) : ?>
        <li><b>Név:</b> <?= $user['fullname'] ?>, <b>TAJ szám:</b> <?= $user['taj'] ?>, <b>E-mail:</b> <?= $user['email'] ?></li>
        <?php endforeach; ?>
    </ul>
    <button onclick="location.href='./index.php'">Vissza a listaoldalra</button>

</body>
</html>