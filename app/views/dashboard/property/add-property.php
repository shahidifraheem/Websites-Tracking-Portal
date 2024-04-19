<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "<h1>403 Forbidden - Direct access not allowed.</h1>";
    exit;
}

$this->include_dashboard('header', $data);
$this->include_dashboard('sidebar', $data); ?>

<form action="<?= ROOT ?>dashboard/add_property" method="post">
    <h1 class="title">Properties</h1>
    <?= check_error(); ?>
    <div class="input-box">
        <label for="url">Property Url</label>
        <input type="url" id="url" name="url" placeholder="Enter property url...">
    </div>
    <button type="submit">Add Property</button>
</form>

<?php $this->include_dashboard('footer', $data); ?>