<?php

namespace App\Entity;

use Core\Entity\AbstractEntity;
use Core\Util\Security;

class User extends AbstractEntity
{
    private string $username;
    protected string $password_hash;
    protected string $password;


    public function hydrate(string $username, string $password): void
    {
        $this->setUser($username);
        $this->setPassword($password);
    }
    public function __get($item): mixed
    {
        switch($item) {
            case 'username':
            case 'password_hash':
                $item = $this->$item;
                break;
            default:
                $item = parent::__get($item);
                break;
        }
        return $item;
    }

    public function setUser(string $username): void
    {
        $this->username = Security::sanitize($username);
    }

    /**
     * @param string $password
     *
     * @return void
     */
    public function setPassword(string $password): void
    {
        if(!empty($password)) {
            $this->password = Security::sanitize($password);
            $this->password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        }

    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    protected function rule(): array
    {
        // TODO: Implement rule() method.
    }
}