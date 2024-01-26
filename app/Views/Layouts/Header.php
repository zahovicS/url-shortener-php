<!DOCTYPE html>
<html lang="es">

<head>
    <meta name="base_url" content="<?= base_url() ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= assets("css/fontawesome.css")  ?>">
    <link rel="stylesheet" href="<?= assets("css/bulma.css")  ?>">
    <style>
        @font-face {
            font-family: 'Rubik';
            src: url("<?= assets("fonts/Rubik.ttf") ?>")
        }
    </style>
    <link rel="stylesheet" href="<?= assets("css/style.css")  ?>">
    <title>TINY URL - <?= $title_page ?? "Inicio" ?></title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?= assets("css/sweetalert-dark.css")  ?>">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="<?= assets("js/app.js")  ?>"></script>
</head>

<body>
    <main class="container" id="app">
        <?php view("Layouts.Navbar") ?>