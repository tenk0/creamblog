<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController
{
    public $components = array('Flash', 'Paginator');
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel('Category');
        $this->set('category', $this->Category->find('list'));
        $this->Auth->allow('add', 'logout');
        $this->Paginator->settings = array('limit' => 5, 'order' => 'Post.id');
    }

    public function isAuthorized($user)
    {
        $id = $this->request->params['pass'][0];
        if ($id == $user['id']) {
            return true;
        }
        return false;
    }

    public function login()
    {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->redirect('/users/index');
            } else {
                $options = array('conditions' => array('User.username' => $this->request->data['User']['username']));
                $lockedUser = $this->User->find('first', $options);
                if (!empty($lockedUser) && $lockedUser['User']['state'] == 1) {
                    $this->Flash->error(__('This account is currently suspended'));
                } else {
                    if (!empty($lockedUser)) {
                        $this->User->incrementErrorCount($lockedUser['User']);
                    }
                    $this->Flash->error(__('Invalid username or password, try again'));
                }
            }
        }
    }

    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }

    public function index()
    {
        $id = $this->Auth->user('id');
        $this->loadModel('Post');
        $this->Post->recursive = 0;
        $this->set('posts',
            $this->Paginator->paginate(
                'Post',
                array(
                    'user_id' =>
                        array(
                            'user_id' => $id
                        )
                )
            )
        );
    }

    public function view($id = null)
    {

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('users', $this->User->findById($id));
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('The user has been saved'));
                return $this->redirect(array('action' => 'login'));
            }
            $this->Flash->error(
                __('The user could not be saved. Please, try again.')
            );
        }
    }

    public function edit($id = null)
    {
        $this->loadModel('Post');

        $post = $this->Post->find('first', array(
                'conditions' => array(
                    'Post.user_id' => $this->Auth->user('id'),
                    'Post.id' => $id
                ),
                'callbacks' => true
            )
        );
        if ($post == null) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
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

    public function delete($id = null)
    {
        $this->request->allowMethod('post');

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Flash->success(__('User deleted'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Flash->error(__('User was not deleted'));
        return $this->redirect(array('action' => 'index'));
    }
}


