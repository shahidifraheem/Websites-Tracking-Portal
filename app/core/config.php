<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "403 Forbidden - Direct access not allowed.";
    exit;
}

define('WEBSITE_TITLE', 'Tracking Portal');
define('DASHBOARD_TITLE', 'Dashboard - Tracking Portal');
define('EMAIL', 'hello@shahidifraheem.com');
define('NO_REPLY_EMAIL', 'no-reply@shahidifraheem.com');
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'tracking_portal');
define('DB_USER', 'root');
define('DB_PASS', '');

define('VIEWS', '../app/views/');
define('THEME', 'theme/');
define('DASHBOARD', 'dashboard/');

define('TRACKING_HISTORY', '1');
date_default_timezone_set('Asia/Karachi');

define('DEBUG', true);

if (DEBUG) {
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
}
