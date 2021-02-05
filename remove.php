<?php

include_once('tools.php');
include_once('userstorage.php');
include_once('appstorage.php');

$userstorage = new UserStorage();
$allusers = $userstorage->findAll();

$appstorage = new AppStorage();
$allapps = $appstorage->findAll();

$appdatum = $_GET['datum'];
$apptime = $_GET['time'];
$usermail = $_GET['usermail'];

$success=false;

foreach ($allusers as $user)
{
    if ($user['email']==$_SESSION['loggedin'])
    {
        $data = $user;
        $data['appdatum'] = "";
        $data['apptime'] = "";
        $id = $user['id'];

        $userstorage->update($id, $data);

        $success = true;
    }
}

if ($success)
{
    foreach ($allapps as $app)
    {
        if ($app['datum']==$appdatum && $app['time']==$apptime)
        {
            $data = $app;
            $data['freeplaces'] = $data['freeplaces']+1;
            $id = $app['id'];

            $appstorage->update($id, $data);
        }
    }

}


header("location:index.php");


?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nemzeti Koronavírus Depó - Időpont lemondása</title>

    <style>
    body {text-align: center;}
    h1   {color: blue;}
    p    {font-size: 20px;}
    </style>
</head>
<body>
    <h1>NemKoVID - oltásra jelentkezési felület</h1>
    <h2><p>Az iőpont lemondása sikeres!</p></h2>
    <button onclick="location.href='./index.php'">Vissza a listaoldalra</button>
</body>
</html>