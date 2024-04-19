<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "<h1>403 Forbidden - Direct access not allowed.</h1>";
    exit;
}

include "../app/core/config.php";
include "../app/core/controller.php";
include "../app/core/functions.php";
include "../app/core/database.php";
include "../app/core/tracking.php";
include "../app/core/app.php";
