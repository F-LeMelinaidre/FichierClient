<?php

namespace Core\Data;

use Exception;
use InvalidArgumentException;
use RuntimeException;

/**
 * Gestionnaire de fichier JSON
 */
class DataFileHandler
{

    private static $instance;

    /**
     * @var array Stock les paires nom => fichier.ext
     */
    private array $files = [];

    private string $path;


    private function __construct()
    {
        $this->path = str_replace('\\', '/', __DIR__);
    }

    /**
     * @return DataFileHandler
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new DataFileHandler();

        }

        return self::$instance;
    }


    /**
     * Ajout de fichier
     *
     * @param string      $file_name
     * @param string|null $name Si null le nom du fichier sans extension lui sera attribué
     *
     * @return void
     */
    public function addFile(string $file_name, ?string $name = null)
    {
        if (!$name) {
            $name = pathinfo($file_name, PATHINFO_FILENAME);

            if (strpos($name, '.') === 0) {
                $name = substr($name, 1);
            }
        }

        try {
            $file_path = $this->path . '/' . $file_name;

            if (!file_exists($file_path)) throw new RuntimeException("Erreur: Le fichier $file_name n'existe pas.");

            $this->files[$name] = $file_path;

        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }

    }


    /**
     * @param string $name
     *
     * @return string
     */
    private function loadFileData(string $name): string
    {
        $json = '';
        if (isset($this->files[$name])) {
            try {

                $json = file_get_contents($this->files[$name]);
                if ($json === false) throw new RuntimeException("Erreur lors de la lecture du fichier $name.");


            } catch (RuntimeException $e) {
                echo $e->getMessage();
            }
        }
        return $json;
    }


    /**
     * @param string $name
     * @param        $data
     *
     * @return bool
     */
    public function save(string $name, array $data)
    {
        $result = false;
        $date = date('d-m-Y H:i');
        $data['modified'] = $date;



        $stored_data = json_decode($this->getAll($name), true);
        $stored_data[] = $data;

        try {

            if (!file_put_contents($this->files[$name], json_encode($stored_data,JSON_PRETTY_PRINT))) throw new RuntimeException('Erreur lors de l\'enregistrement du fichier.');

            $result = true;

        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }

        return $result;
    }

    public function update(string $name, array $data) {
        $result = false;

        $id = $data['id'];

        $date = date('d-m-Y H:i');
        $data['modified'] = $date;

        $items = json_decode($this->getAll($name), true);

        $i = 0;
        $find = false;
        $nb = count($items)-1;
        while( $i < $nb && !$find) {
            if($id === $items[$i]['id']) {
                $items[$i] = $data;
                $find = true;
            }
            echo $i.' - '.$nb.'<br>';
            $i++;
        }


        try {

            if (!file_put_contents($this->files[$name], json_encode($items,JSON_PRETTY_PRINT))) throw new RuntimeException('Erreur lors de l\'enregistrement du fichier.');

            $result = true;

        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }

        return $result;

    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getAll(string $name): string
    {
        return $this->loadFileData($name);
    }

    public function delete(string $name, int $id)
    {
        $result = false;
        $data = json_decode($this->getAll($name), true);
        try {
            $id = array_search($id, array_column($data, 'id'));
            if($id === false) throw new RuntimeException('L\'enregistrement n pas ete trouvé.');

            unset($data[$id]);

            if (!file_put_contents($this->files[$name], json_encode($data, JSON_PRETTY_PRINT))) throw new RuntimeException('Erreur lors de l\'enregistrement du fichier.');

            $result = true;

        } catch (RuntimeException) {
            echo $e->getMessage();
        }

        return $result;
    }

}