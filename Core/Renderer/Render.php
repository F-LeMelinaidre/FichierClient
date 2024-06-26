<?php

namespace Core\Renderer;

use App\App;

/**
 *
 */
class Render
{

    private string $base = 'index.view';
    private string $layout = 'main.view';
    private string $path = '/App/View/';
    private string $layout_path;
    private string $view_path;
    private string $view;


    /**
     * @param string $root        racine de l'application
     * @param string $folder_view représente le dossier des vues lié au controller
     */
    public function __construct(string $folder_view)
    {
        $this->path = App::$_Root . $this->path;

        $this->layout_path = $this->path . 'Layout/';
        $this->view_path = $this->path . $folder_view . '/';

    }


    /**
     * @param string $view
     * @param array  $data
     *
     * @return string
     */
    public function render(string $view, array $data = []): string
    {
        $this->setView($view);

        $content_view = $this->renderView($data);

        $layout = $this->renderLayout($content_view, $data);

        ob_start();
        include_once($this->path . $this->base);
        $view = ob_get_clean();

        return str_replace('{{layout}}', $layout, $view);
    }


    /**
     * @return void
     */
    private function renderLayout($content_view, $data): string
    {
        ob_start();
        include_once($this->layout_path . $this->layout);
        $layout = ob_get_clean();
        return str_replace('{{content}}', $content_view, $layout);
    }


    /**
     * @return void
     */
    private function renderView($data): string
    {
        ob_start();
        extract($data);
        include_once($this->view_path . $this->view);

        return ob_get_clean();
    }


    /**
     * @param $view
     *
     * @return bool
     */
    private function hasPrefix($view): bool
    {
        return strpos($view, '@');
    }


    /**
     * La valeur précédant @ représente le layout alternative à celui défini par defaut dans les propriétés de la class
     *
     * @param string $view
     *
     * @return void
     */
    private function setView(string $view): void
    {
        if ($this->hasPrefix($view)) {

            list($layout, $view) = explode('@', $view);
            $this->layout = "$layout.view";

        }
        $this->view = "$view.view";

    }
}