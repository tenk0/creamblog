<h1>User Manager</h1>
<p><?php echo $this->Html->link('Add Post', array('action' => 'add')); ?></p>
<table>
    <tr>State
        <th>Id</th>
        <th>UserName</th>
        <th>Actions</th>
        <th>State</th>
        <th>Created</th>
    </tr>

    <!-- ここで $posts 配列をループして、投稿情報を表示 -->

    <?php foreach ($admins as $admin): ?>
        <tr>
            <td><?php echo $admin['User']['id']; ?></td>
            <td>
                <?php
                echo $this->Html->link(
                    $admin['User']['username'],
                    array('action' => 'view', $admin['User']['id'])
                );
                ?>
            </td>
            <td>
                <?php
                echo $this->Form->postLink(
                    'Change State',
                    array('action' => 'Change', $admin['User']['state']),
                    array('confirm' => 'Are you sure?')
                );
                ?>
                <?php
                echo $this->Html->link(
                    'Edit', array('action' => 'edit', $admin['User']['id'])
                );
                ?>
            </td>
            <td>
                <?php echo $admin['User']['state']; ?>
            </td>
            <td>
                <?php echo $admin['User']['created']; ?>
            </td>
        </tr>
    <?php endforeach; ?>

</table>