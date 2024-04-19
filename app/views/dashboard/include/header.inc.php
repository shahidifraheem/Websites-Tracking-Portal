<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $page_title ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= ASSETS ?>img/shahid-ifraheem-favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= ASSETS ?>css/dashboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="<?= ASSETS ?>js/dashboard.js"></script>
</head>

<body>
    <header id="header">
        <div class="wrapper">
            <div class="site-logo">
                <a href="<?= ROOT ?>dashboard">
                    <img src="<?= ASSETS ?>img/Shahid-Ifraheem.png" alt="Shahid Ifraheem" width="200px">
                </a>
            </div>
            <div class="header-btns">
                <a href="<?= ROOT ?>user/logout" class="universal-btn">Logout</a>
            </div>
            <a href="<?= ROOT ?>dashboard/profile" class="user-info">
                <h3><?= $user_data->name ?></h3>
                <img src="<?= ROOT . "uploads/" . $user_data->avatar ?>" alt="<?= $user_data->name ?>" class="avatar">
            </a>
        </div>
    </header>
    <div id="main-wrapper">