<h1>Edit Post</h1>
<?php
echo $this->Form->create('Post');
echo $this->Form->input('title');
echo $this->Form->input('body', array('rows' => '3'));
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->input('category_id', array('options'=>$category, 'empty'=>'選択してください'));
echo $this->Form->end('Save Post');
?>



