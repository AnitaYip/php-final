<h3>Users</h3>

<table border="1">
    <tr>
        <th>ID</th>
        <th>NAME</th>
        <th>EMAIL</th>
    </tr>
    <?php foreach ($users as $user): ?>
            <tr>
                <td>
                    <a href=<?php echo "/$user->id"; ?>>
                        <?php echo $user->id ?>
                    </a>
                </td>
                <td><?php echo $user->name ?></td>
                <td><?php echo $user->email ?></td>
            </tr>
    <?php endforeach; ?>
</table>
<br>
<a href=<?php echo "/new" ?>>Add User</a>
