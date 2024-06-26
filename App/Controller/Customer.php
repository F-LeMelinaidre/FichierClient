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
        $data_paginate = $this->data_handler->getPaginateData('customer',1, 10);
        $customers = $data_paginate['data'];
        $current_page = $data_paginate['current_page'];
        $nb_page = $data_paginate['nb_page'];

        foreach ($customers as &$customer) {
            $customer = new CustomerEntity($customer);
        }

        return $this->render('dashboard@list', compact('customers', 'current_page', 'nb_page'));
    }

    public function edit($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST)) {

            $customer = new CustomerEntity($_POST);

            $data = [
                'name' => 'customer',
                'data' => $customer->toArray()
            ];

            if($customer->validate() && $this->data_handler->save(...$data)) {
                header('Location: /liste-clients');
            }
        }

        return $this->render('dashboard@edit');
    }
}