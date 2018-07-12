<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 11.07.18
 * Time: 9:58
 */

namespace Src\Authorization;


use Src\App;
use Src\Session\FlashMessage;

class Auth
{
    public function isAuth()
    {
        $session = App::getSession();
        if(!$session->cookieExist()) {
            return false;
        }
        $session->start();

        if(!$session->contains('login') || !$session->contains('password')) {
            return false;
        }

//        return $this->checkToken($session->get('login'), $session->get('token'));
        return $this->checkUserData($session->get('login'), $session->get('password'));
    }

    public function auth()
    {
        if(empty($_REQUEST['login']) || empty($_REQUEST['password'])) {
            return false;
        }

        $login = $_REQUEST['login'];
        $password = sha1($_REQUEST['password']);

        $session = App::getSession();


        if(!$this->checkUserData($login, $password)) {
            $session->setFlashMessage(new FlashMessage('error', 'Login failed', 'Login or password is invalid'));
            return false;
        }

        $session->start()
            ->setFlashMessage(new FlashMessage('success', 'Login success', 'You are logged in'))
            ->set("login", $login)
            ->set('password', $password);
//            ->set("token", $this->getToken($password));
        return true;
    }

    public function logout()
    {
        $session = App::getSession()->start();
        $session->delete('login');
        $session->delete('password');
    }

    public function getLogin()
    {
        if(!$this->isAuth()) {
            return false;
        }
        return App::getSession()->get('login');
    }

    protected function checkUserData($login, $password)
    {
        $user = [

            'login'     => "admin@admin.com",
            'password'  =>  sha1("password"),
        ];

        return ($login == $user['login'] && $password == $user['password']);
    }

    protected function checkToken($login, $token)
    {

    }

    protected function getToken($password)
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $user_addr = $_SERVER['REMOTE_ADDR'];

        return sha1($password . $user_agent . $user_addr);
    }


}