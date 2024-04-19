<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "<h1>403 Forbidden - Direct access not allowed.</h1>";
    exit;
}

$this->include_dashboard('header', $data);
$this->include_dashboard('sidebar', $data);
if ($user[0]->properties != "") {
    $properties_array = explode(",", $user[0]->properties);
}
?>

<form action="<?= ROOT ?>dashboard/edit_user/<?= $user[0]->id ?>" method="post" enctype="multipart/form-data">
    <h1 class="title">Edit - <?= $user[0]->name ?></h1>
    <?= check_error();
    check_success() ?>
    <div class="input-check-box">
        <div class="input-box">
            <label for="avatar">Avatar</label>
            <input type="file" id="avatar" name="avatar">
        </div>
        <div class="input-box">
            <?php if ($user[0]->avatar != "") : ?>
                <img src="<?= ROOT . "uploads/" . $user[0]->avatar ?>" width="120px" />
            <?php endif; ?>
        </div>
    </div>
    <div class="input-box">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Enter name..." value="<?= $user[0]->name ?>">
    </div>
    <div class="input-box">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter email...">
        <span><b>Old Email:</b> <?= $user[0]->email ?></span>
    </div>
    <div class="input-box">
        <label for="password">Password</label>
        <input type="text" id="password" name="password" placeholder="Enter password...">
    </div>
    <div class="input-box">
        <label for="rank">Rank</label>
        <select name="rank" id="rank">
            <option value="customer" <?= $user[0]->rank == "customer" ? " selected" : "" ?>>Customer</option>
            <option value="admin" <?= $user[0]->rank == "admin" ? " selected" : "" ?>>Admin</option>
        </select>
    </div>
    <h1 class="normal-title">Properties Access</h1>
    <div class="input-check-box">
        <?php $count = -1;
        if (isset($properties)) : foreach ($properties as $property) : $count++; ?>
                <div class="checkbox">
                    <input type="checkbox" id="<?= $property->url ?>" value="<?= $property->url ?>" name="property-access[]">
                    <label for="<?= $property->url ?>"><?= $property->url ?></label>
                </div>
        <?php endforeach;
        endif; ?>
    </div>
    <button type="submit">Update User</button>
</form>

<?php if (isset($properties_array)) : ?>
    <script>
        jQuery(function($) {
            const properties = <?= json_encode($properties_array) ?>;
            console.log(JSON.stringify(properties));
            // Loop through each checkbox
            $("input[type='checkbox']").each(function() {
                // Get the ID of the current checkbox
                var checkboxId = $(this).attr("id");

                // Check if the ID matches any URL in the array
                if (properties.includes(checkboxId)) {
                    // If a match is found, set the "checked" attribute
                    $(this).prop("checked", true);
                    $(this).val(checkboxId);
                }
            });
        })
    </script>
<?php endif; ?>

<?php $this->include_dashboard('footer', $data); ?>