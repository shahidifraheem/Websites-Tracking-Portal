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

<form action="<?= ROOT ?>dashboard/edit_user/<?= $user_data->id ?>" method="post" enctype="multipart/form-data">
    <h1 class="title">Profile - <?= $user_data->name ?></h1>
    <?= check_error();
    check_success() ?>
    <div class="input-check-box">
        <div class="input-box">
            <label for="avatar">Avatar</label>
            <input type="file" id="avatar" name="avatar">
        </div>
        <div class="input-box">
            <?php if ($user_data->avatar != "") : ?>
                <img src="<?= ROOT . "uploads/" . $user_data->avatar ?>" width="120px" />
            <?php endif; ?>
        </div>
    </div>
    <div class="input-box">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Enter name..." value="<?= $user_data->name ?>">
    </div>
    <div class="input-box">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter email...">
        <span><b>Old Email:</b> <?= $user_data->email ?></span>
    </div>
    <div class="input-box">
        <label for="password">Password</label>
        <input type="text" id="password" name="password" placeholder="Enter password...">
    </div>
    <h1 class="normal-title">Properties Access</h1>
    <div class="input-check-box">
        <?php if (!empty($user_data->properties)) : foreach ($user_data->properties as $property) : ?>
                <div class="checkbox">
                    <input type="checkbox" id="<?= $property ?>" value="<?= $property ?>" name="property-access[]" hidden>
                    <a href="<?= $property ?>">
                        <u><?= $property ?></u>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none">
                            <g id="Interface / External_Link">
                                <path id="Vector" d="M10.0002 5H8.2002C7.08009 5 6.51962 5 6.0918 5.21799C5.71547 5.40973 5.40973 5.71547 5.21799 6.0918C5 6.51962 5 7.08009 5 8.2002V15.8002C5 16.9203 5 17.4801 5.21799 17.9079C5.40973 18.2842 5.71547 18.5905 6.0918 18.7822C6.5192 19 7.07899 19 8.19691 19H15.8031C16.921 19 17.48 19 17.9074 18.7822C18.2837 18.5905 18.5905 18.2839 18.7822 17.9076C19 17.4802 19 16.921 19 15.8031V14M20 9V4M20 4H15M20 4L13 11" stroke="#6bc17c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                        </svg>
                    </a>
                </div>
        <?php endforeach;
        endif; ?>
    </div>
    <input type="hidden" name="profile">
    <button type="submit">Update Profile</button>
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