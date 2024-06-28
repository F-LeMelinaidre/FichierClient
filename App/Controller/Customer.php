<?php

namespace App\Controller;

use App\App;
use App\Entity\Customer as CustomerEntity;
use Core\Controller\AbstractController;
use Core\Data\DataFileHandler;
use Core\Util\IdGenerator;

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

    public function edit($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST)) {

            $customer = new CustomerEntity($_POST);

            $data = [
                'name' => 'customer',
                'data' => $customer->toJson()
            ];

            if($customer->validate() && $this->data_handler->save(...$data)) {
                header('Location: /liste-clients');
            }
        }

        return $this->render('dashboard@edit');
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