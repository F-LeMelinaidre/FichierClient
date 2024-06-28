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
        if (isset($data['modified']) && !empty($data['created'])) $data['modified'] = $date;
        if (isset($data['created']) && empty($data['created'])) $data['created'] = $date;

        $stored_data = $this->getAll($name);

        try {

            $stored_data = substr($stored_data, 0, -1) . ',' . $json . ']';


            if (!file_put_contents($this->files[$name], $stored_data)) throw new RuntimeException('Erreur lors de l\'enregistrement du fichier.');

            $result = true;

        } catch (Exception $e) {
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

}