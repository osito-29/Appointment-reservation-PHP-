<?php

include_once('tools.php');
include_once('appstorage.php');

function get_value($name) {
    if (empty($_POST[$name])) {
        return '';
    }

    return $_POST[$name];
}

function validateDate($date, $format)
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

$appstorage = new AppStorage();
$allapps = $appstorage->findAll();

$errors = [];

if ($_POST)
{
    $datum = $_POST['datum'];
    $time = $_POST['time'];
    $places = $_POST['places'];

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!='admin@nemkovid.hu')
    {
        $errors[] = 'A felhasználó nem az admin!';
    }

    if (empty($datum))
    {
        $errors[] = 'Nincs megadva a dátum!';
    }

    else if (!validateDate($datum, 'Y.m.d.'))
    {
        $errors[] = 'A megadott dátum nem helyes!';
    }

    if (empty($time))
    {
        $errors[] = 'Nincs megadva az idő!';
    }

    else if (!validateDate($time, 'H:i'))
    {
        $errors[] = 'A megadott időpont nem helyes!';
    }

    if (empty($places))
    {
        $errors[] = 'Nincs megadva a helyek száma!';
    }

    else if (!is_numeric($places))
    {
        $errors[] = 'A helyek száma nem szám!';
    }

    else if ($places <= 0)
    {
        $errors[] = 'A helyek száma nem lehet 1-nél kisebb!';
    }

}

$valid = FALSE;

if (empty($errors) && $_POST)
{
    $valid = TRUE;
}

if ($valid)
{
    $appointment = [
        'datum'  => $datum,
        'time' => $time,
        'freeplaces'  => $places,
        'places'  => $places
    ];

    $appstorage->add($appointment);

    header("location:index.php");
}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nemzeti Koronavírus Depó - Új időpont hirdetése</title>

    <style>
    body {text-align: center;}
    h1   {color: blue;}
    p    {font-size: 20px;}
    </style>
</head>
<body>
    <h1>NemKoVID - oltásra jelentkezési felület</h1>
    <h2><p>Új időpont hirdetése</p></h2>

    <ul>
        <?php foreach ($errors as $error) : ?>
        <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
    <form method="POST" novalidate>

        <label>Dátum</label>
        <input type="text" name="datum" value="<?= get_value('datum') ?>"><br><br>

        <label>Időpont</label>
        <input type="text" name="time" value="<?= get_value('time') ?>"><br><br>

        <label>Helyek száma</label>
        <input type="text" name="places" value="<?= get_value('places') ?>"><br><br>

        <button type="submit">Felvesz</button>
    </form>

</body>
</html>