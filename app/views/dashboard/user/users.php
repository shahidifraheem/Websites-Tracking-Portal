<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "<h1>403 Forbidden - Direct access not allowed.</h1>";
    exit;
}

$this->include_dashboard('header', $data);
$this->include_dashboard('sidebar', $data); ?>

<h1 class="title">All Customers</h1>
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Avatar</th>
                <th>Name</th>
                <th>Email</th>
                <th>Rank</th>
                <th>Properties</th>
                <th>Updated</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($users)) : $count = 0;
                foreach ($users as $user) : $count++;
                    if ($user->properties != "") {
                        $properties = explode(",", $user->properties);
                    }
            ?>
                    <tr>
                        <td><?= $count ?></td>
                        <td>
                            <?php if ($user->avatar != "") : ?>
                                <img src="<?= ROOT . "uploads/" . $user->avatar ?>" alt="<?= $user->name ?>" width="50px">
                            <?php else : ?>
                                <img src="<?= ASSETS ?>img/favicon.png" alt="" width="50px">
                            <?php endif; ?>
                        </td>
                        <td><?= $user->name ?></td>
                        <td><?= $user->email ?></td>
                        <td><?= $user->rank ?></td>
                        <td>
                            <?php if (isset($properties)) : foreach ($properties as $property) : ?>
                                    <a href="<?= $property ?>" target="_blank"><?= $property ?></a><br>
                            <?php endforeach;
                            endif; ?>
                        </td>
                        <td><?= $user->updated_at ?></td>
                        <td><?= $user->date ?></td>
                        <td>
                            <a href="<?= ROOT ?>dashboard/edit_user/<?= $user->id ?>" class="edit-btn">Edit</a>
                            <a href="<?= ROOT ?>dashboard/delete_user/<?= $user->id ?>" class="delete-btn">Delete</a>
                        </td>
                    </tr>
            <?php endforeach;
            endif; ?>
        </tbody>
    </table>
</div>

<?php $this->include_dashboard('footer', $data); ?>