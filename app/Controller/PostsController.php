<?php

class PostsController extends AppController
{
    public $helpers = array('Html', 'Form');
    public $components = array('Flash', 'Paginator');

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Paginator->settings = array('limit' => 5, 'order' => 'Post.id');
        $this->loadModel('Category');
        $this->set('category', $this->Category->find('list'));
    }

    public function index()
    {
        try {
            $this->Paginator->paginate();
            $this->set('posts', $this->paginate('Post'));
        } catch (NotFoundException $e) {
            print'not found 404';
            die();
        }
    }

    public function view($id)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }
        $post = $this->Post->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        $this->set('post', $post);
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->request->data['Post']['user_id'] = $this->Auth->user('id');
            $this->Post->create();
            if ($this->Post->save($this->request->data)) {
                $this->Flash->success(__('Your post has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(__('Unable to add your post.'));
        }
    }

    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }
        $post = $this->Post->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->Post->id = $id;
            if ($this->Post->save($this->request->data)) {
                $this->Flash->success(__('Your post has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(__('Unable to update your post.'));
        }
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function delete($id) {
        $this->loadModel('Category');
        $this->set('category', $this->Category->find('list'));
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        if ($this->Post->delete($id)) {
            $this->Flash->success(
                __('The post with id: %s has been deleted.', h($id))
            );
        } else {
            $this->Flash->error(
                __('The post with id: %s could not be deleted.', h($id))
            );
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function isAuthorized($user) {
        if ($this->action === 'add') {
            return true;
        }
        if (in_array($this->action, array('edit', 'delete'))) {
            $postId = (int)$this->request->params['pass'][0];
            if ($this->Post->isOwnedBy($postId, $user['id'])) {
                return true;
            }
        }
    }
}
