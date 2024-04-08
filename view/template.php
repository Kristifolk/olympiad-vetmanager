<?php

use App\Services\View;

/** @var View $this */
?>

<!doctype html>
<html lang="en" class="page">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="/resources/css/style.css">

    <title>Олимпиада Vetmanager</title>
</head>
<body class="main-background">
<main id="app" >
    <?= $this->arguments['content'] ?>
</main>
</body>
</html>
