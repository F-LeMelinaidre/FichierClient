<?php

namespace App\Entity;

use Core\Entity\AbstractEntity;
use Core\Util\Security;
use Core\Util\Validation;

class Customer extends AbstractEntity
{
    private ?string $last_name;
    private string $first_name;
    private string $phone;
    private Address $address;

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
     * @param array $address
     *
     * @return void
     */
    private function setAddress(array $address): void
    {
        $this->address = new Address($address);
    }


    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'created'    => $this->created,
            'modified'   => $this->modified,
            'last_name'  => $this->last_name,
            'first_name' => $this->first_name,
            'phone'      => $this->phone,
            'address'    => $this->address->toArray()
        ];
    }

    public function toJson(): string
    {
        return json_encode([
            'id'         => $this->id,
            'created'    => $this->created,
            'modified'   => $this->modified,
            'last_name'  => $this->last_name,
            'first_name' => $this->first_name,
            'phone'      => $this->phone,
            'address'    => $this->address->toArray()
        ]);
    }

    protected function rule(): array
    {
        return [
            'id'            => [Validation::REQUIRED],
            'last_name'     => [Validation::REQUIRED, Validation::ALPHA],
            'first_name'    => [Validation::REQUIRED, Validation::ALPHA],
            'phone'         => [Validation::REQUIRED, Validation::NUMERIC]
        ];
    }
}