<?php

class PostsController extends AppController {
    public $helpers = array('Html', 'Form');
    public $components = array('Flash');

    public function index() {
        $this->set('posts', $this->Post->find('all'));
    }

    public function view($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->Post->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        $this->set('post', $post);
    }

    public function add(){
        if($this->request->is('Post')){
            $this->Post->create();
            if($this->Post->save($this->request->data)){
                $this->Flash->success(__('成功'));
                return $this->redirect(array('action' => 'index'));
            }
        }
        $this->Flash->error(__('失敗'));
    }
}
/**
 * Created by PhpStorm.
 * User: RD-096
 * Date: 2016/08/10
 * Time: 10:57
 */