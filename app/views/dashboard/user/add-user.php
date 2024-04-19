<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "<h1>403 Forbidden - Direct access not allowed.</h1>";
    exit;
}

$this->include_dashboard('header', $data);
$this->include_dashboard('sidebar', $data); ?>

<form action="<?= ROOT ?>dashboard/add_user" method="post" enctype="multipart/form-data">
    <h1 class="title">Add User</h1>
    <?= check_error(); ?>
    <div class="input-box">
        <label for="avatar">Avatar</label>
        <input type="file" id="avatar" name="avatar">
    </div>
    <div class="input-box">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Enter name...">
    </div>
    <div class="input-box">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter email...">
    </div>
    <div class="input-box">
        <label for="password">Password</label>
        <input type="text" id="password" name="password" placeholder="Enter password...">
    </div>
    <div class="input-box">
        <label for="rank">Rank</label>
        <select name="rank" id="rank">
            <option value="customer">Customer</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <h1 class="normal-title">Properties Access</h1>
    <div class="input-check-box">
        <?php if (isset($properties)) : foreach ($properties as $property) : ?>
                <div class="checkbox">
                    <input type="checkbox" id="<?= $property->url ?>" value="<?= $property->url ?>" name="property-access[]">
                    <label for="<?= $property->url ?>"><?= $property->url ?></label>
                </div>
        <?php endforeach;
        endif; ?>
    </div>
    <button type="submit">Add User</button>
</form>

<?php $this->include_dashboard('footer', $data); ?>