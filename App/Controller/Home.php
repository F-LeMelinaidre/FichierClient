<?php


namespace App\Controller;

use App\Entity\User;
use Core\Controller\AbstractController;

class Home extends AbstractController
{

    public function index()
    {
        $user = new User();

        return $this->render('index', compact('user'));
    }

    public function connect()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {


        }
        /*header('Location: /client');
        exit;*/
    }
    public function disconnect()
    {
        header('Location: /');
        exit;
    }
}