<?php

include_once('tools.php');
include_once('userstorage.php');

function get_value($name) {
    if (empty($_POST[$name])) {
        return '';
    }

    return $_POST[$name];
}

$userstorage = new UserStorage();
$allusers = $userstorage->findAll();

$errors = [];
$email_exists = false;

if ($_POST)
{
    $fullname = $_POST['fullname'];
    $taj = $_POST['taj'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];

    foreach ($allusers as $user)
    {
        if ($user['email']==$email)
        {
            $email_exists = true;
        }
    }

    if (empty($fullname))
    {
        $errors[] = 'Nincs kitöltve a teljes név!';
    }

    if (empty($taj))
    {
        $errors[] = 'Nincs kitöltve a TAJ szám!';
    }

    else if (!is_numeric($taj))
    {
        $errors[] = 'A TAJ szám nem csak számokat tartalmaz!';
    }

    else if (strlen($taj)!=9)
    {
        $errors[] = 'A TAJ szám nem 9 jegyű!';
    }

    if (empty($address))
    {
        $errors[] = 'Nincs kitöltve az értesítési cím!';
    }

    if (empty($email))
    {
        $errors[] = 'Nincs kitöltve az e-mail cím!';
    }

    else if (!strpos($email,"@"))
    {
        $errors[] = 'Nem helyes e-mail formátum';
    }

    else if ($email_exists)
    {
        $errors[] = 'Ezzel az e-maillel már van regisztrált felhasználó!';
    }

    if (empty($password))
    {
        $errors[] = 'Nincs kitöltve a jelszó!';
    }

    if (empty($repassword))
    {
        $errors[] = 'Nincs kitöltve a jelszó megerősítése!';
    }

    if ($password !== $repassword)
    {
        $errors[] = 'A jelszó és a megerősítése nem egyezik!';
    }

}

$valid = FALSE;

if (empty($errors) && $_POST)
{
    $valid = TRUE;
}

if ($valid)
{
    $person = [
        'fullname'  => $fullname,
        'taj' => $taj,
        'address'  => $address,
        'email'  => $email,
        'password' => $password,
        'appdatum' => "",
        'apptime' => ""
    ];

    $userstorage->add($person);

    header("location:login.php");
}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nemzeti Koronavírus Depó - Regisztráció</title>

    <style>
    body {text-align: center;}
    h1   {color: blue;}
    p    {font-size: 20px;}
    </style>
</head>
<body>
    <h1>NemKoVID - oltásra jelentkezési felület</h1>
    <h2><p>Regisztráció</p></h2>

    <ul>
        <?php foreach ($errors as $error) : ?>
        <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
    <form method="POST" novalidate>

        <label>Teljes név</label>
        <input type="text" name="fullname" value="<?= get_value('fullname') ?>"><br><br>

        <label>Taj szám</label>
        <input type="text" name="taj" value="<?= get_value('taj') ?>"><br><br>

        <label>Értesítési cím</label>
        <input type="text" name="address" value="<?= get_value('address') ?>"><br><br>

        <label>E-mail cím</label>
        <input type="text" name="email" value="<?= get_value('email') ?>"><br><br>

        <label>Jelszó</label>
        <input type="text" name="password" value="<?= get_value('password') ?>"><br><br>

        <label>Jelszó megerősítése</label>
        <input type="text" name="repassword" value="<?= get_value('repassword') ?>"><br><br>

        <button type="submit">Elküld</button>
    </form>

</body>
</html>
