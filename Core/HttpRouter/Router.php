<?php

namespace Core\HttpRouter;

use Core\Exception\RouterException;

class Router
{

    private static $instance;
    private array $routes = [
        'GET'  => [],
        'POST' => []
    ];

    /**
     * @return Router
     */
    public static function getInstance()
    {
        if (!self::$instance) self::$instance = new Router();

        return self::$instance;
    }


    /**
     * @param string $name     Nom de la route
     * @param string $path     URL
     * @param array  $callback [Controller, Methode]
     * @param array  $params   Paramètre d'URL [nom du paramètre => format de la valeur (expression régulière)]
     *
     * @return void
     */
    public function get(string $name, string $path, array $callback, array $params = [])
    {
        $this->add('GET', $name, $path, $callback, $params);
    }


    /**
     * @param string $name
     * @param string $path
     * @param array  $callback
     * @param array  $params
     *
     * @return void
     */
    public function post(string $name, string $path, array $callback, array $params = [])
    {
        $this->add('POST', $name, $path, $callback, $params);
    }


    /**
     * @param string $method
     * @param string $name
     * @param string $path
     * @param array  $callback
     * @param array  $params
     *
     * @return void
     */
    public function add(string $method, string $name, string $path, array $callback, array $params)
    {
        $route = new Route($name, $path, $callback, $params);
        $this->routes[$method][$name] = $route;
    }


    /**
     * @param $method
     * @param $path
     *
     * @return Route
     */
    public function resolve($method, $path)
    {
        $match = false;
        $routes = $this->routes[$method];

        while ($match === false && !empty($routes)) {
            $route = array_shift($routes);

            if ($route->match($path)) {
                $match = true;
            } else {
                unset($route);
            }
        }

        if (!isset($route)) {
            //TODO Gestionnaire d'exception
            throw new RouterException("Aucune route correspondante n'a été trouvée");
        }

        return $route;
    }


    /**
     * @param string $name
     * @param array  $params
     *
     * @return string
     */
    public function getUrl(string $name, array $params = []): string
    {
        if (isset($this->routes['GET'][$name])) {

            $route = $this->routes['GET'][$name]->getPath($params);
        } elseif (isset($this->routes['POST'][$name])) {

            $route = $this->routes['POST'][$name]->getPath($params);

        } else {
            $route = '/';
        }

        return $route;
    }

}