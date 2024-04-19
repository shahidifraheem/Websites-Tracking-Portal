<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "<h1>403 Forbidden - Direct access not allowed.</h1>";
    exit;
}

$this->include_dashboard('header', $data);
$this->include_dashboard('sidebar', $data);

?>

<section class="analytics-section">
    <h1 class="title">Analytics - <?= $user_data->name ?></h1>
    <?= check_error();
    check_success() ?>
</section>
<?php $this->include_dashboard('footer', $data); ?>