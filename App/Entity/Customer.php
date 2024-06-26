<?php

namespace App\Entity;

use Core\Entity\AbstractEntity;
use Core\Util\Security;
use Core\Util\Validation;

class Customer extends AbstractEntity
{
    private ?string $last_name = null;
    private ?string $first_name = null;
    private ?string $phone = null;
    private ?string $address = null;
    private ?string $street_number = null;
    private ?string $street_type = null;
    private ?string $street_name = null;
    private ?string $zip = null;
    private ?string $city = null;

    /**
     * @param string $last_name
     * @param string $first_name
     * @param string $phone
     * @param array  $address
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        if (isset($data['last_name'])) $this->setLastName($data['last_name']);
        if (isset($data['first_name'])) $this->setFirstName($data['first_name']);
        if (isset($data['phone'])) $this->setPhone($data['phone']);
        if (isset($data['address'])) $this->setAddress($data['address']);
    }

    /**
     * @param $item
     *
     * @return string
     */
    public function __get(mixed $item): mixed
    {
        switch ($item) {
            case 'last_name':
            case 'first_name':
            case 'phone':
            case 'address':
            case 'street_number':
            case 'street_type':
            case 'street_name':
            case 'zip':
            case 'city':
                $item = $this->$item;
                break;
            default:
                $item = parent::__get($item);
                break;
        }
        return $item;
    }

    /**
     * @param string $last_name
     *
     * @return void
     */
    public function setLastName(string $last_name): void
    {
        $last_name = ucwords($last_name);
        $this->last_name = Security::sanitize($last_name);
    }

    /**
     * @param string $first_name
     *
     * @return void
     */
    public function setFirstName(string $first_name): void
    {
        $first_name = ucwords($first_name);
        $this->first_name = Security::sanitize($first_name);
    }

    /**
     * @param string $phone
     *
     * @return void
     */
    public function setPhone(string $phone): void
    {
        $this->phone = Security::sanitize($phone);
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
     * @param array $address
     *
     * @return void
     */
    private function setAddress(array $address)
    {
        if (!empty($address['street_number'])) $this->setStreetNumber($address['street_number']);
        if (!empty($address['street_type'])) $this->setStreetType($address['street_type']);
        if (!empty($address['street_name'])) $this->setStreetName($address['street_name']);
        if (!empty($address['zip'])) $this->setZip($address['zip']);
        if (!empty($address['city'])) $this->setCity($address['city']);

        $this->address = $this->street_number . " " . $this->street_type . " " . $this->street_name;
    }

    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'last_name'  => $this->last_name,
            'first_name' => $this->first_name,
            'phone'      => $this->phone,
            'address'    => [
                'street_number' => $this->street_number,
                'street_type'   => $this->street_type,
                'street_name'   => $this->street_name,
                'zip'           => $this->zip,
                'city'          => $this->city
            ],
            'created'    => $this->created,
            'modified'   => $this->modified
        ];
    }

    protected function rule(): array
    {
        return [
            'id'            => [Validation::REQUIRED],
            'last_name'     => [Validation::REQUIRED, Validation::ALPHA],
            'first_name'    => [Validation::REQUIRED, Validation::ALPHA],
            'phone'         => [Validation::REQUIRED, Validation::NUMERIC],
            'street_number' => [Validation::REQUIRED, Validation::STREET_NUMBER],
            'street_type'   => [Validation::REQUIRED, Validation::ALPHA],
            'street_name'   => [Validation::REQUIRED, Validation::ALPHA_NUMERIC],
            'zip'           => [Validation::REQUIRED, Validation::NUMERIC],
            'city'          => [Validation::REQUIRED, Validation::ALPHA]
        ];
    }
}