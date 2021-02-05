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

$fullname = "";
$address = "";
$tajnumber = "";

foreach ($allusers as $user)
{
    if ($user['email']==$_SESSION['loggedin'])
    {
        $fullname = $user['fullname'];
        $address = $user['address'];
        $tajnumber = $user['taj'];  
    }
}

$errors = [];

$agree = false;

if (!$_POST)
{
    if (!isset($_POST['agree']))
    {
        $errors[] = 'A feltételek elfogadása a jelentkezéshez kötelező!';
    }

}

if ($_POST['agree']=='OK')
{
    $success=false;

    foreach ($allusers as $user)
    {
        if ($user['email']==$_SESSION['loggedin'])
        {
            $data = $user;
            $data['appdatum'] = $appdatum;
            $data['apptime'] = $apptime;
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
                $data['freeplaces'] = $data['freeplaces']-1;
                $id = $app['id'];
    
                $appstorage->update($id, $data);
            }
        }

    }

    header("location:success.php");
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
    <h2><p>Foglalás</p></h2>

    <p><b><?= $appdatum ?> <?= $apptime ?></b></p>

        <p><b>Név:</b> <?= $fullname ?></p>
        <p><b>Lakcím:</b> <?= $address ?></p>
        <p><b>TAJ szám:</b> <?= $tajnumber ?></p>

    <ul>
        <?php foreach ($errors as $error) : ?>
        <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
    <form method="POST" novalidate>
    A jelentkezés feltételeit (kötelező megjelenés) tudomásul vettem és elfogadom
    <input type="checkbox" name="agree" value="OK" /><br><br>
    <button type="submit">Jelentkezés megerősítése</button>
    </form>

</body>
</html>