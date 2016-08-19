<h1>blog</h1>
<p><?php echo $this->Html->link('Add Post', array('action' => 'add')); ?></p>
<table>
    <tr>
        <th><?php echo $this->Paginator->sort('Post.id', 'ID') ?></th>
        <th><?php echo $this->Paginator->sort('Post.title', 'Title') ?></th>
        <th><?php echo $this->Paginator->sort('Category.name', 'Category') ?></th>
<!--        <th>Actions</th>-->
        <th><?php echo $this->Paginator->sort('Post.created', 'Created') ?></th>
    </tr>

    <!-- ここで $posts 配列をループして、投稿情報を表示 -->
    <?php foreach ($posts as $post): ?>
        <tr>
            <td><?php echo $post['Post']['id']; ?></td>
            <td>
                <?php
                echo $this->Html->link(
                    $post['Post']['title'],
                    array('action' => 'view', $post['Post']['id'])
                );
                ?>
            </td>
            <td>
                <?php echo $this->Html->link(
                    $post['Category']['name'],
                    array('action' => 'view', $post['Category']['name'])
                );
                ?>
            </td>
<!--            <td>-->
<!--                --><?php
//                echo $this->Form->postLink(
//                    'Delete',
//                    array('action' => 'delete', $post['Post']['id']),
//                    array('confirm' => 'Are you sure?')
//                );
//                ?>
<!--                --><?php
//                echo $this->Html->link(
//                    'Edit', array('action' => 'edit', $post['Post']['id'])
//                );
//                ?>
<!--            </td>-->
            <td>
                <?php echo $post['Post']['created']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<p><?php echo $this->Paginator->counter('全%pages%ページ中、%pages%ページ目を表示しています。') ?></p>
<ul>
    <li><?php echo $this->Paginator->prev('<< 前へ', array(), null, array('class' => 'prev disabled'));
        ?></li>
    <li><?php echo $this->Paginator->next('次へ >>', array(), null, array('class' => 'next disabled'));
        ?></li>
</ul>
<p><?php echo $this->Paginator->numbers() ?></p>