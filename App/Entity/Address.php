<?php
/*
 * {$user_name}
 * {$class}
 * Copyright (c) 2024.
 */

namespace App\Entity;

use Core\Entity\AbstractEntity;
use Core\Util\Security;
use Core\Util\Validation;

class Address extends AbstractEntity
{

    private string $city;

    private string $street_name;
    private string $street_number;
    private string $street_type;
    private string $zip;

    public string $full_address;


    public function __construct(array $data = [])
    {
        parent::__construct($data);

        if (isset($data['street_number'])) $this->setStreetNumber($data['street_number']);
        if (isset($data['street_type'])) $this->setStreetType($data['street_type']);
        if (isset($data['street_name'])) $this->setStreetName($data['street_name']);
        if (isset($data['zip'])) $this->setZip($data['zip']);
        if (isset($data['city'])) $this->setCity($data['city']);
    }

    public function __get(mixed $item): mixed
    {
        switch ($item) {
            case 'street_number':
            case 'street_type':
            case 'street_name':
            case 'zip':
            case 'city':
                $item = $this->$item;
                break;
            case 'full_address':
                $item = $this->getFullAddress();
                break;
            default:
                $item = parent::__get($item);
                break;
        }

        return $item;
    }

    /**
     * @param string $street_number
     *
     * @return void
     */
    public function setStreetNumber(string $street_number): void
    {
        $street_number = mb_strtoupper($street_number, 'UTF-8');
        $this->street_number = Security::sanitize($street_number);
    }

    /**
     * @param string $street_type
     *
     * @return void
     */
    public function setStreetType(string $street_type): void
    {
        $street_type = ucwords($street_type);
        $this->street_type = Security::sanitize($street_type);
    }

    /**
     * @param string $street_name
     *
     * @return void
     */
    public function setStreetName(string $street_name): void
    {
        $street_name = ucwords($street_name);
        $this->street_name = Security::sanitize($street_name);
    }

    /**
     * @param string $zip
     *
     * @return void
     */
    public function setZip(string $zip): void
    {
        $this->zip = Security::sanitize($zip);
    }

    /**
     * @param string $city
     *
     * @return void
     */
    public function setCity(string $city): void
    {
        $city = ucwords($city);
        $this->city = Security::sanitize($city);
    }

    /**
     * @return string
     */
    public function getFullAddress(): string
    {
        return "$this->street_number $this->street_type $this->street_name";
    }

    public function toArray(): array
    {
        return [
            'street_number' => $this->street_number,
            'street_type'   => $this->street_type,
            'street_name'   => $this->street_name,
            'zip'           => $this->zip,
            'city'          => $this->city
        ];
    }

    public function toJson(): string
    {
        $data = [
            'street_number' => $this->street_number,
            'street_type'   => $this->street_type,
            'street_name'   => $this->street_name,
            'zip'           => $this->zip,
            'city'          => $this->city
        ];

        return json_encode($data);
    }

    /**
     * Tableau des règles de validation des propriétes de class
     * utilisées par la methode validation dans AbstractEntity
     *
     * @return array[]
     * @see \Core\Util\Validation Class contenant Les expressions regulières et messages de validation associés
     */
    protected function rule(): array
    {
        return [
            'street_number' => [Validation::REQUIRED, Validation::STREET_NUMBER],
            'street_type'   => [Validation::REQUIRED, Validation::ALPHA],
            'street_name'   => [Validation::REQUIRED, Validation::ALPHA_NUMERIC],
            'zip'           => [Validation::REQUIRED, Validation::NUMERIC],
            'city'          => [Validation::REQUIRED, Validation::ALPHA]
        ];
    }
}