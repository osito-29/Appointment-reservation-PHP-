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

$login = false;

if ($_POST)
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email))
    {
        $errors[] = 'Nincs kitöltve az e-mailcím!';
    }

    if (empty($password))
    {
        $errors[] = 'Nincs kitöltve a jelszó!';
    }

    foreach ($allusers as $user)
    {
        if ($user['email']==$email)
        {
            if($user['password']==$password)
            {
                $login = true;
            }
        }
    }

    if (!$login)
    {
        $errors[] = 'Hibás felhasználónév vagy jelszó!';
    }

}

$valid = FALSE;

if (empty($errors) && $_POST)
{
    $valid = TRUE;
}

if ($valid)
{
    $_SESSION["loggedin"] = $email;

    header("location:index.php");
}



?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nemzeti Koronavírus Depó - Bejelentkezés</title>

    <style>
    body {text-align: center;}
    h1   {color: blue;}
    p    {font-size: 20px;}
    </style>
</head>
<body>
    <h1>NemKoVID - oltásra jelentkezési felület</h1>
    <h2><p>Bejelentkezés</p></h2>

    <ul>
        <?php foreach ($errors as $error) : ?>
        <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
    <form method="POST" novalidate>

        <label>E-mail cím</label>
        <input type="text" name="email" value="<?= get_value('email') ?>"><br><br>

        <label>Jelszó</label>
        <input type="text" name="password" value="<?= get_value('password') ?>"><br><br>

        <button type="submit">Bejelentkezés</button>
    </form>

</body>
</html>