<aside id="sidebar">
    <h4 class="normal-title">Dashboard</h4>
    <div class="menu-box">
        <ul class="menu">
            <li>
                <a href="<?= ROOT ?>dashboard">Overview</a>
            </li>
            <?php if ($user_data->rank == "admin") : ?>
                <li class="has-sub-menu">
                    <a href="<?= ROOT ?>dashboard/properties">
                        <span>Properties</span>
                        <i class="fa-solid fa-plus"></i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="<?= ROOT ?>dashboard/add_property">Add New Property</a>
                        </li>
                        <li>
                            <a href="<?= ROOT ?>dashboard/properties">View All</a>
                        </li>
                    </ul>
                </li>
                <li class="has-sub-menu">
                    <a href="<?= ROOT ?>dashboard/users">
                        <span> Users</span>
                        <i class="fa-solid fa-plus"></i>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="<?= ROOT ?>dashboard/add_user">Add New User</a>
                        </li>
                        <li>
                            <a href="<?= ROOT ?>dashboard/users">View All</a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if (!empty($user_data->properties)) : ?>
                <li>
                    <br>
                    <h4 class="normal-title">Properties</h4>
                </li>
                <?php foreach ($user_data->properties as $property) : ?>
                    <li>
                        <a href="<?= ROOT . "dashboard?property=" . $property ?>"><?= $property ?></a>
                    </li>
            <?php endforeach;
            endif; ?>
        </ul>
    </div>
    <a href="<?= ROOT ?>" target="_blank" class="universal-btn web-btn">Visit Website</a>
</aside>
<main id="content">