<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "403 Forbidden - Direct access not allowed.";
    exit;
}
?>
<div class="text-center">
    <p>404</p>
    <h3><strong>Sorry!</strong> The mention include name not found.</h3>
</div>