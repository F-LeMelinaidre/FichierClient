<?php


namespace App\Controller;

use App\App;
use App\Entity\User;
use Core\Controller\AbstractController;
use Core\Data\DataFileHandler;
use Core\Util\MessageFlash;

class Home extends AbstractController
{

    protected DataFileHandler $data_handler;

    public function __construct()
    {
        parent::__construct();

        $this->data_handler = App::$_Data;
    }
    public function index()
    {

        $user = new User();
        if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            $user->hydrate(...$_POST);
            $username = $user->username;
            $users = json_decode($this->data_handler->getAll('user'), true);

            $user_find = array_filter($users, function ($u) use ($username) {

                return $u['username'] == $username;
            });

            if(password_verify($user->getPassword(), $user_find[0]['password']) && !empty($user_find)) {

                MessageFlash::create("connecté", "succes");
                $_SESSION['user'] = $username;
                header('Location: /client');
                exit();
            }

        }
        return $this->render('index', compact('user'));
    }

    public function disconnect()
    {
        unset($_SESSION['user']);
        header('Location: /');
        exit;
    }
}