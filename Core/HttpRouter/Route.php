<?php

namespace Core\HttpRouter;

class Route
{

    private array $callback;
    private string $name;
    private string $path;
    private array $params;


    /**
     * @param string $name
     * @param string $path
     * @param array  $callback
     * @param array  $params
     */
    public function __construct(string $name, string $path, array $callback, array $params = [])
    {
        $this->name = $name;
        $this->path = trim($path, '/');
        $this->callback = $callback;
        $this->params = $params;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @return string
     */
    public function getPath(array $params = []): string
    {
        $path = $this->path;
        if(!empty($params)) {
            foreach ($params as $key => $val) {
                $path = str_replace('{'.$key.'}', $val, $path);
            }
        }
        return '/'.$path;
    }


    /**
     * @return callable|array
     */
    public function getCallback(): callable|array
    {
        return $this->callback;
    }


    /**
     * @param string $path
     *
     * @return false|int
     */
    public function match(string $path)
    {
        $url = trim($path, '/');
        $path = preg_replace_callback('#{([\w]+)}#', [$this, 'paramsMatch'], $this->path);

        $reg = "#^$path$#i";
        $result = preg_match($reg, $url, $matches);

        if (!empty($matches)) $this->setParams($matches);

        return $result;
    }


    /**
     * @param array $matches
     *
     * @return void
     */
    private function setParams(array $params): void
    {
        array_shift($params);
        $params_keys = array_keys($this->params);

        $this->params = array_combine($params_keys, $params);
    }


    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }


    /**
     * @param $match
     *
     * @return string
     */
    private function paramsMatch($match): string
    {
        return (isset($this->params[$match[1]])) ? '(' . $this->params[$match[1]] . ')' : '([^\]+)';
    }
}