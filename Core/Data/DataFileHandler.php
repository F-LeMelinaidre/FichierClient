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
     * @return array
     */
    private function loadFileData(string $name): array
    {
        $data = [];
        try {
            if (!isset($this->files[$name])) throw new InvalidArgumentException("Erreur : le fichier $name n'existe pas.");

            $json = file_get_contents($this->files[$name]);
            if ($json === false) throw new RuntimeException("Erreur lors de la lecture du fichier $name.");

            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) throw new InvalidArgumentException("Erreur de décodage JSON du fichier $name.");



        } catch (InvalidArgumentException|RuntimeException|Exception $e) {
            echo $e->getMessage();
        }
        return $data;
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
            if (!is_array($data)) throw new InvalidArgumentException('Les données doivent être un tableau PHP.');

            $stored_data[] = $data;

            $jsonData = json_encode($stored_data, JSON_PRETTY_PRINT);
            if ($jsonData === false) throw new RuntimeException('Erreur lors de l\'encodage.');

            if (!file_put_contents($this->files[$name], $jsonData)) throw new RuntimeException('Erreur lors de l\'enregistrement du fichier.');

            $result = true;

        } catch (InvalidArgumentException|RuntimeException $e) {
            echo $e->getMessage();
        }

        return $result;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getAll(string $name): array
    {
        return $this->loadFileData($name);
    }

    public function getPaginateData(string $name, int $current_page, int $nb_data_page): array
    {
        $data = $this->loadFileData($name);

        $start = ($current_page - 1) * $nb_data_page;

        $page_data = array_slice($data, $start, $nb_data_page);

        $nb_page = ceil(count($data) / $nb_data_page);

        return [
            'current_page' => $current_page,
            'nb_page' => $nb_page,
            'data' => $page_data,
        ];

    }

}