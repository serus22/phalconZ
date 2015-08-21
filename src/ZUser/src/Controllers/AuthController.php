<?php

namespace PhalconZ\ZUser\Controllers;

use Phalcon\Text;
use Phalcon\Http\Request;
use PhalconZ\Rest\Controllers\RestException;
use PhalconZ\ZUser\Models\ZUser;
use Phalcon\Mvc\CollectionInterface;
use PhalconZ\Rest\Controllers\AbstractRestController;

class AuthController extends AbstractRestController {

    public function loginAction() {
        $this->response()->setStatusCode(401);
        return $this->handleRequest(function() {
            $session = $this->getDI()->getShared('session');
            $loggedAt = $session->get('loggedAt');
            $user = $session->get('user');
            if (time() - $loggedAt < 1200) {
                $this->response()->setStatusCode(200);
                return $this->jsonOutput($user);
            }
            if ($this->request()->isPost()) {
                $post = $this->getData();
                $user = $this->auth(@$post->userid, @$post->password, @$post->remember);
                //TODO:Maybe prerobit na UserInterface
                if ($user instanceof CollectionInterface && strlen($user->getId() . '') > 10) {
                    $this->response()->setStatusCode(200);
                    return $this->jsonOutput($user);
                }
            } else if ($this->request()->isOptions()) {
                $this->response()->setStatusCode(200);
                return $this->jsonOutput();
            }
            throw new RestException(401);
        });
    }

    public function registerAction() {
        $req = new Request();
        try {
            if ($req->isPost()) {
                $post = json_decode($req->getRawBody());
                $user = new ZUser();

                $user->salt = Text::random(Text::RANDOM_ALNUM);
                $user->password = $this->hash($post->password, $user->salt);
                unset($post->password);
                $post = (array) $post;
                foreach($post as $key => $value)
                    $user->$key = $value;

                $user->save();
                $data = null;
            } else if($req->isOptions()) {
                $data = null;
            }
        } catch(\Exception $e) {
            $data = ['message' => $e->getMessage()];
        }

        $this->jsonOutput($data);
    }

    public function logoutAction() {
        $session = $this->getDI()->get('session');
        $session->remove('user');
        $session->remove('loggedAt');
        return $this->jsonOutput(null, 200);
    }

    protected function auth($userid, $password, $remember = false) {
        $user = ZUser::findFirst([
            [
                '$or' => [
                    ['username' => $userid],
                    ['email' => $userid],
                ],
            ],
        ]);
        if(empty($user)) return null;
        if(! $this->security->checkHash("super_static_salt" . $password . $user->salt, $user->password)) return null;
        unset($user->password, $user->salt);
        $session = $this->getDI()->getShared('session');
        $session->set('user', $user);
        $session->set('loggedAt', time());
        return $user;
    }

    protected function hash($password, $salt) {
        return $this->security->hash("super_static_salt" . $password . $salt);
    }

    protected function checkHash($raw, $salt, $hash) {
        return $this->security->checkHash("super_static_salt" . $raw . $salt, $hash);
    }

    /**
     * Get list or specific record
     * @param string
     * @param array ()
     * @return array(), mixed
     */
    public function get($id = null) {
    }

    /**
     * Create record
     * @param array
     * @return array
     */
    public function post($data) {
    }

    /**
     * Edit new record
     * @param string
     * @param array
     * @return array
     */
    public function put($id, $data) {
    }

    /**
     * Remove record
     * @param string
     * @return array
     */
    public function delete($id) {
    }
}