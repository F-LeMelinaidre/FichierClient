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

    public function isConnected(): bool
    {
        $connected = false;
        $users = json_decode($this->data_handler->getAll('user'), true);

        if(isset($_SESSION['user'])) {
            $username = $_SESSION['user'];
            $user_find = array_filter($users, function ($u) use ($username) {
                return $u['username'] == $username;
            });
            $connected = !empty($user_find);
        }

        return $connected;
    }

    /**
     * @return void
     */
    public function index()
    {
        // TODO: Implement index() method.
    }


    /**
     * @return void
     */
    public function show()
    {
        // TODO: Implement show() method.
    }


    /**
     * @return void
     */
    public function create()
    {
        // TODO: Implement create() method.
    }


    /**
     * @return void
     */
    public function update()
    {
        // TODO: Implement update() method.
    }


    /**
     * @return void
     */
    public function delete()
    {
        // TODO: Implement delete() method.
    }


    /**
     * @param int $page
     * @param int $per_page
     *
     * @return void
     */
    public function paginate(int $page, int $per_page): void {}


    /**
     * @param string $view
     * @param array  $data
     *
     * @return string
     */
    public function render(string $view, array $data = []): string
    {
        return $this->render->render($view, $data);
    }


    /**
     * @param string $path
     * @param        $params
     *
     * @return void
     */
    public function addJavascript(string $path, $params = []): void
    {
        $this->render->addJavascript($path, ...$params);
    }
}