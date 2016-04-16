<?php

namespace PhalconZ\ZUser\Controllers;

use Phalcon\Text;
use Phalcon\Http\Request;
use PhalconZ\Rest\Controllers\RestException;
use Phalcon\Mvc\CollectionInterface;
use PhalconZ\Rest\Controllers\AbstractRestController;

class AuthController extends AbstractRestController
{

  public function getUserDocument()
  {
    return $this->config['zuser']['userCollection'];
  }

  public function loginAction()
  {
    $this->response()->setStatusCode(401);
    return $this->handleRequest(function() {
      $loggedAt = $this->session->get('loggedAt');
      $user = $this->session->get('user');
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

  public function registerAction()
  {
    return $this->handleRequest(function() {
      $req = new Request();
      if ($req->isPost()) {
        $post = json_decode($req->getRawBody());
        $a = $this->getUserDocument();
        $user = new $a();

        $user->salt = Text::random(Text::RANDOM_ALNUM);
        $user->password = $this->hash($post->password, $user->salt);
        unset($post->password);
        $post = (array)$post;
        foreach ($post as $key => $value)
          $user->$key = $value;

        $user->save();
        $this->session->set('user', $user);
      } else if ($req->isOptions()) {
        return '';
      }
      return $this->jsonOutput($user);
    });
  }

  public function logoutAction()
  {
    $this->session->remove('user');
    $this->session->remove('loggedAt');
    return $this->jsonOutput(null, 200);
  }

  protected function auth($userid, $password, $remember = false)
  {
    $user = forward_static_call($this->getUserDocument() . '::findFirst', [
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
    $this->session->set('user', $user);
    $this->session->set('loggedAt', time());
    return $user;
  }

  protected function hash($password, $salt) {
    return $this->security->hash("super_static_salt" . $password . $salt);
  }

  protected function checkHash($raw, $salt, $hash)
  {
    return $this->security->checkHash("super_static_salt" . $raw . $salt, $hash);
  }

  /**
   * Get list or specific record
   * @param string
   * @param array ()
   * @return array(), mixed
   */
  public function get($id = null)
  {
  }

  /**
   * Edit new record
   * @param string
   * @param array
   * @return array
   */
  public function put($id, $data)
  {
  }

  /**
   * Remove record
   * @param string
   * @return array
   */
  public function delete($id)
  {
  }

  /**
   * Create record
   * @param array
   * @return array
   */
  public function post($data)
  {
    $this->registerAction();
  }
}