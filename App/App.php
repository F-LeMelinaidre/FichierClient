<?php

namespace App;


use Core\Data\DataFileHandler;
use Core\Exception\RouterException;
use Core\HttpRouter\Router;

class App
{


    /**
     * @var DataFileHandler instance du gestionnaire de fichier JSON
     */
    public static DataFileHandler $_Data;

    public static $_Root;

    /**
     * @var Router instance du router
     */
    public static Router $_Router;

    /**
     * @param string $root
     */
    public function __construct(string $root)
    {

        self::$_Root = $root;
        self::$_Router = Router::getInstance();
        self::$_Data = DataFileHandler::getInstance();
    }

    public function run()
    {
        self::$_Data->addFile('.access', 'user');
        self::$_Data->addFile('data.dat', 'customer');

        self::$_Router->get('home', '/',
            [Controller\Home::class, 'index']);

        self::$_Router->post('connect', '/connect',
            [Controller\Home::class, 'connect']);
        self::$_Router->get('disconnect', '/disconnect',
            [Controller\Home::class, 'disconnect']);

        self::$_Router->get('customer', '/client',
            [Controller\Customer::class, 'index']);
        self::$_Router->get('customers_list', '/liste-clients',
            [Controller\Customer::class, 'list']);
        self::$_Router->get('customers_paginate', '/ajax/customers-paginate/{page}/{per_page}',
            [Controller\Customer::class, 'paginate'],
            ['page'     => '[0-9]+',
             'per_page' => '[0-9]+']);

        self::$_Router->get('customer_search', '/rechercher/client',
            [Controller\Customer::class, 'search']);

        self::$_Router->get('customer_add', '/nouveau/client',
            [Controller\Customer::class, 'add']);
        self::$_Router->post('customer_add', '/nouveau/client',
            [Controller\Customer::class, 'add']);

        self::$_Router->get('customer_edit', '/modifier/client',
            [Controller\Customer::class, 'edit']);
        self::$_Router->post('customer_edit', '/modifier/client',
            [Controller\Customer::class, 'edit']);

        self::$_Router->get('customer_delete', '/client/{slug}',
            [Controller\Customer::class, 'search'], ['slug' => 'supprimer']);
        self::$_Router->post('customer_delete', '/ajax/customer/delete',
            [Controller\Customer::class, 'delete']);

        self::$_Router->get('customers_all', '/ajax/customers/list',
            [Controller\Customer::class, 'getAll']);

        try {
            $route = self::$_Router->resolve($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

            $callback = $route->getCallback();
            $params = $route->getParams();

            $callback[0] = new $callback[0]();

            return call_user_func_array($callback, $params);

        } catch (RouterException $e) {
            // TODO PAGE 404
            echo $e->getMessage();
        }

    }
}