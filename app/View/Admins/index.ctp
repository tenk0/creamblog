<h1>User Manager</h1>
<p><?php echo $this->Html->link('Add Post', array('action' => 'add')); ?></p>
<table>
    <tr>State
        <th>Id</th>
        <th>UserName</th>
        <th>State</th>
        <th>Created</th>
    </tr>
    <!-- ここで $posts 配列をループして、投稿情報を表示 -->

    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['User']['id']; ?></td>
            <td>
                <?php
                echo $this->Html->link(
                    $user['User']['username'],
                    array( 'view', $user['User']['id'])
                );
                ?>
            </td>
            <td>
                <?php
                if($user['User']['state']==0){
                    print 'Permit';
                }else{
                    print 'Ban';
                } ?>
                <?php
                echo $this->Form->postLink(
                    'Change State',
                    array('action' => 'Change', $user['User']['id'],$user['User']['state']),
                    array('confirm' => 'Are you sure?')
                );
                ?>
            </td>
            <td>
                <?php echo $user['User']['created']; ?>
            </td>
        </tr>
    <?php endforeach; ?>

</table>