<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "<h1>403 Forbidden - Direct access not allowed.</h1>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $page_title ?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= ASSETS ?>img/404.png" type="image/x-icon">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Jost:wght@600;700;800&family=Rubik&display=swap');

        :root {
            --primary-color: #244061;
            --secondary-color: #e3e3e3;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Rubik', sans-serif;
        }

        .not-found {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100vh;
            background-color: var(--secondary-color);
            text-align: center;
            padding: 20px;
        }

        h1 {
            font-family: 'Jost', sans-serif;
            font-size: 150px;
            font-weight: 800;
            color: var(--primary-color);
        }

        p {
            font-size: 22px;
        }

        strong {
            font-size: 30px;
        }

        a {
            display: inline-block;
            font-size: 18px;
            padding: 12px 40px;
            border: 2px solid var(--primary-color);
            background-color: var(--primary-color);
            color: var(--secondary-color);
            text-decoration: none;
            margin-top: 20px;
            transition: .3s;
        }

        a:hover {
            color: var(--primary-color);
            background-color: var(--secondary-color);
            border-radius: 25px;
        }

        @media screen and (max-width:768px) {
            h1 {
                font-size: 80px;
            }

            p {
                font-size: 18px;
            }

            strong {
                font-size: 25px;
            }

            a {
                font-size: 18px;
                padding: 8px 30px;
            }
        }
    </style>
</head>

<body>
    <section class="not-found">
        <div class="not-found-content">
            <h1>404</h1>
            <p><strong>OOP! </strong>The page you are looking for not found.</p>
            <a href="<?= ROOT ?>">Return to Home</a>
        </div>
    </section>
</body>

</html>