<?php
App::uses('AppController', 'Controller');

/**
 * Class AdminsController
 * $this->User @
 */
class AdminsController extends AppController
{
    public function beforeFilter()
    {
        $this->Auth->authenticate = array(
            "Form" => array(
                "userModel" => "Admin"
            )
        );
        // ログインページ
        $this->Auth->loginAction = "/admins/login";
        // ログイン後リダイレクト先
        $this->Auth->loginRedirect = "/admins";
        // ログアウト後リダイレクト先
        $this->Auth->logoutRedirect = "/admins/login";
        // 認証エラーメッセージ
        $this->Auth->authError = "Admin Only";
        // セッションキーの変更
        AuthComponent::$sessionKey = "Auth.Admins";
        // 管理者自身による登録とログアウトを許可する
        $this->Auth->allow('add', 'logout');
    }

    public function login()
    {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->redirect('/admins');
            } else {
                $this->Flash->error(__('Invalid Username or password, try again'));
            }
        }
    }

    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }

    public function index()
    {
        $this->loadModel('User');
        $this->User->recursive = 0;
        $this->set('users', $this->User->find('all'));
    }

    public function view($id = null)
    {
        $this->Admin->id = $id;
        if (!$this->Admin->exists()) {
            throw new NotFoundException(__('Invalid Admin'));
        }
        $this->set('Admins', $this->Admin->findById($id));
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->Admin->create();
            if ($this->Admin->save($this->request->data)) {
                $this->Flash->success(__('The user has been saved'));
                return $this->redirect($this->Auth->redirect());
            }
            $this->Flash->error(
                __('The user could not be saved. Please, try again.')
            );
        }
    }

    public function edit($id = null)
    {
        $this->Admin->id = $id;
        if (!$this->Admin->exists()) {
            throw new NotFoundException(__('Invalid Admin'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Admin->save($this->request->data)) {
                $this->Flash->success(__('The Admin has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(
                __('The Admin could not be saved. Please, try again.')
            );
        } else {
            $this->request->data = $this->Admin->findById($id);
            unset($this->request->data['Admin']['password']);
        }
    }

    public function change($id = null, $state)
    {
        $this->loadModel('User');
        unset($this->User->validate['state']);

        $this->request->allowMethod('post');

        $state = ($state == 0) ? 1 : 0;

        $data = array('id' => $id, 'state' => $state);
        $this->User->id = $id;

        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid Admin'));
        }
        if ($this->User->save($data)) {
            $this->Flash->success(__('State changed'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Flash->error(__('State was not changed'));
        return $this->redirect(array('action' => 'index'));
    }
}