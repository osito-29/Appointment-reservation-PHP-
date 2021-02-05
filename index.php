<?php

include_once('appstorage.php');
include_once('userstorage.php');

include_once('tools.php');

$appstorage = new AppStorage();
$appointments = $appstorage->findAll();

$userstorage = new UserStorage();
$allusers = $userstorage->findAll();

sort($appointments);

$apps_month;

$actual_month = date('m');

if ($_GET['month']>0 && $_GET['month']<=12)
{
    $actual_month = $_GET['month'];
}

$actual_user;

if (isset($_SESSION['loggedin']))
{
    foreach ($allusers as $user)
    {
        if ($user['email']==$_SESSION['loggedin'])
        {
            $actual_user = $user;
        }
    }
}


?>


<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nemzeti Koronavírus Depó</title>

    <style>
    body {text-align: center;}
    h1   {color: blue;}
    p    {font-size: 20px;}
    </style>
</head>
<body>
    <h1>NemKoVID - oltásra jelentkezési felület</h1>
    <h2>
    <p>A védekezés új szakaszába érkeztünk - oltóanyagok állnak rendelkezésre a koronavírus ellen.</p>
    <p>A hatékony védekezés részeként a cél a lakosság minél nagyobb arányú átoltottsága.</p>
    <p>Regisztráljon és válasszon a még elérhető szabad időpontok közül, amikor Önnek alkalmas a részvétel!</p>
    </p></h2>

    <button id="login" onclick="location.href='./login.php'" type="button">Bejelentkezés</button>
    <button id ="register" onclick="location.href='./register.php'" type="button">Regisztráció</button>

    <?php if (isset($actual_user['appdatum']) &&  $actual_user['appdatum']!=''): ?>
        <h2 style="color:blue;">Lefoglalt időpont adatai: <?= $actual_user['appdatum'] ?> <?= $actual_user['apptime'] ?></h2>
        <button id="remove" onclick="location.href='./remove.php?datum=<?= $actual_user['appdatum'] ?>&time=<?= $actual_user['apptime'] ?>'" type="button">Időpont lemondása</button></li>
    <?php endif; ?>

    <h2>Meghirdetett időpontok</h2>

    <?php if (isset($_SESSION['loggedin']) &&  $_SESSION['loggedin']=='admin@nemkovid.hu'): ?>
        <a href="./newdatum.php">új időpont meghirdetése</a>
    <?php endif; ?>
    <ul>
    <?php foreach ($appointments as $appointment) : ?>
        <?php if (substr($appointment['datum'], 5, 2) == $actual_month): ?>
            <li style="color:<?= $appointment['freeplaces'] == 0 ? 'red' : 'green' ?>"><?= $appointment['datum'] ?> 
            <?= $appointment['time'] ?> <?= $appointment['freeplaces'] ?>/<?= $appointment['places'] ?> szabad hely 
            <?php if (isset($_SESSION['loggedin']) && $actual_user['appdatum']=='' && $_SESSION['loggedin']!='admin@nemkovid.hu' && $appointment['freeplaces'] != 0): ?>
            <button id="reserve" onclick="location.href='./appointment.php?datum=<?= $appointment['datum'] ?>&time=<?= $appointment['time'] ?>'" type="button">jelentkezés</button></li>
            <?php elseif (!isset($_SESSION['loggedin'])): ?>
            <button id="reserve" onclick="location.href='./login.php'" type="button">jelentkezés</button></li>
            <?php endif; ?>

            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']=='admin@nemkovid.hu'): ?>
            <button id="reserve" onclick="location.href='./details.php?datum=<?= $appointment['datum'] ?>&time=<?= $appointment['time'] ?>'" type="button">részletek</button></li>
            <?php endif; ?>

        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
    <button id="previous" onclick="location.href='./index.php?month=<?= $actual_month-1 ?>'" type="button">előző hónap</button>
    <button id="next" onclick="location.href='./index.php?month=<?= $actual_month+1 ?>'" type="button">következő hónap</button>
</body>
</html>
