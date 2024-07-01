<?php

namespace App\Controller;

use App\App;
use App\Entity\Customer as CustomerEntity;
use Core\Controller\AbstractController;
use Core\Data\DataFileHandler;
use Core\Util\MessageFlash;
use Core\Util\Security;

class Customer extends AbstractController
{

    protected DataFileHandler $data_handler;

    public function __construct()
    {
        parent::__construct();

        $this->data_handler = App::$_Data;
    }

    public function index()
    {
        return $this->render('dashboard@index');
    }

    public function list()
    {

        $this->addJavascript('js/pagination.js');
        return $this->render('dashboard@list');
    }

    public function search(string $slug = null)
    {
        $params = (is_null($slug)) ?
            ['title' => 'Fiche client','action' => 'search'] :
            ['title' => 'Supprimer une fiche client', 'action' => 'delete'];

        $this->addJavascript('js/Form/InputSearch.js', ['module' => true]);
        $this->addJavascript('js/CardCustomer.js', ['module' => true]);
        return $this->render('dashboard@card', $params);
    }

    public function add()
    {
        $params = ['title' => 'Ajouter un client', 'url' => '/nouveau/client'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST)) {

            $customer = new CustomerEntity($_POST);

            $data = [
                'name' => 'customer',
                'json' => $customer->toJson()
            ];
            if($this->data_handler->save(...$data)) {
                echo 'enregistré';
                MessageFlash::create('Client enregistré !',$type = 'success');
                header('Location: /liste-clients');
            }
        }

        $this->addJavascript('js/Form/FormValidation.js', ['module' => true]);
        return $this->render('dashboard@edit', $params);
    }

    public function edit()
    {
        $params = ['action' => 'editer', 'title' => 'Editer un client', 'url' => '/modifier/client'];
        $this->addJavascript('js/Form/FormCustomer.js', ['module' => true]);
        $this->addJavascript('js/Form/InputSearch.js', ['module' => true]);
        return $this->render('dashboard@edit', $params);
    }


    public function getAll(): void
    {
        header('Content-Type: application/json');
        $data = json_decode($this->data_handler->getAll('customer'), true);


        foreach ($data as $k => $customer) {
            $customer = new CustomerEntity($customer);
            $data[$k] = $customer->toArray();
            $data[$k]['full_address'] = $customer->address->getFullAddress();
            $data[$k]['zip'] = $customer->address->zip;
            $data[$k]['city'] = $customer->address->city;
        }

        echo json_encode($data);
    }

    public function delete()
    {
        $id = Security::sanitize($_POST['id']);

        $delete = $this->data_handler->delete('customer', $id);

        if($delete === false) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Erreur : enregistrement non supprimé !']);
        } else {
            MessageFlash::create('Client supprimé !',$type = 'danger');
        }


    }

    public function paginate(int $page, int $per_page): void
    {
        header('Content-Type: application/json');
        $data = json_decode($this->data_handler->getAll('customer'), true);

        $nb_page = ceil(count($data) / $per_page);
        $start = ($page - 1) * $per_page;
        $data = array_slice($data, $start, $per_page);

        foreach ($data as $k => $customer) {
            $customer = new CustomerEntity($customer);
            $data[$k] = $customer->toArray();
            $data[$k]['address'] = $customer->address->getFullAddress();
            $data[$k]['zip'] = $customer->address->zip;
            $data[$k]['city'] = $customer->address->city;
        }

        echo json_encode([
            'data' => $data,
            'nb_page' => $nb_page
            ]);
    }
}