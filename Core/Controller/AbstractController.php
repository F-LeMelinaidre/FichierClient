<?php

namespace Core\Controller;

use App\App;
use Core\Controller\ControllerInterface;
use Core\Renderer\Render;

abstract class AbstractController implements ControllerInterface
{

    private Render $render;

    public function __construct()
    {
        $class_name = basename(get_called_class());

        $this->render = new Render($class_name);
    }


    public function index()
    {
        // TODO: Implement index() method.
    }


    public function show()
    {
        // TODO: Implement show() method.
    }


    public function create()
    {
        // TODO: Implement create() method.
    }


    public function update()
    {
        // TODO: Implement update() method.
    }


    public function delete()
    {
        // TODO: Implement delete() method.
    }


    public function render(string $view, array $data = []): string
    {
        return $this->render->render($view, $data);
    }
}