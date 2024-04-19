<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "<h1>403 Forbidden - Direct access not allowed.</h1>";
    exit;
}

$this->include_dashboard('header', $data);
$this->include_dashboard('sidebar', $data); ?>

<?= check_success(); ?>
<h1 class="title">All Properties</h1>
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Updated</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $count = 0;
            if (isset($properties)) : foreach ($properties as $property) : $count++; ?>
                    <tr>
                        <td><?= $count; ?></td>
                        <td><?= $property->url ?></td>
                        <td><?= $property->updated_at ?></td>
                        <td><?= $property->date ?></td>
                        <td>
                            <a href="<?= ROOT ?>dashboard/edit_property/<?= $property->id ?>" class="edit-btn">Edit</a>
                            <a href="<?= ROOT ?>dashboard/delete_property/<?= $property->id ?>" class="delete-btn">Delete</a>
                        </td>
                    </tr>
            <?php endforeach;
            endif; ?>
        </tbody>
    </table>
</div>

<?php $this->include_dashboard('footer', $data); ?>