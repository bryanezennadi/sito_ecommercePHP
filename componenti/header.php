<?php
require '../componenti/navbar.php';
$titolo = function () {
    $currentPage = basename($_SERVER['PHP_SELF']);
    switch($currentPage) {
        case "index.php": return 'HOME';
        case "Avventura.php": return 'Libri Avventura';
        case "Sportivi.php": return 'Libri Sportivi';
        case "Fantasy.php": return 'Libri Fantasy';
        case "Carrello.php": return 'Carrello';
        default: return 'pagina non trovata';
    }
};
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $titolo() ?></title>
    <link rel="stylesheet" href="../altri_file/home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

