<?php

namespace Core\Entity;

use Core\Util\IdGenerator;
use Core\Util\Validation;
use Core\Util\Security;

abstract class AbstractEntity
{

    public array $errors = [];

    protected ?int $id = null;
    protected string $created = '';
    protected string $modified = '';

    abstract protected function rule(): array;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $id = (isset($data['id'])) ? $data['id'] : IdGenerator::getId('int', ['length' => 11]);
        $this->setId($id);

        if (isset($data['created'])) $this->setCreated($data['created']);
        if (isset($data['modified'])) $this->setModified($data['modified']);
    }


    /**
     * @param int $id
     *
     * @return void
     */
    public function setId(int $id)
    {
        $this->id = Security::sanitize($id);
    }


    /**
     * @param mixed $created
     *
     * @return void
     */
    private function setCreated(mixed $created)
    {
        $this->created = Security::sanitize($created);
    }


    /**
     * @param mixed $modified
     *
     * @return void
     */
    private function setModified(mixed $modified)
    {
        $this->modified = Security::sanitize($modified);
    }


    /**
     * @param mixed $item
     *
     * @return mixed
     */
    public function __get(mixed $item): mixed
    {
        switch ($item) {
            case 'id':
            case 'created':
            case 'modified':
                $item = $this->$item;
                break;
            default:
                $item = 'undentified';
                break;
        }

        return $item;
    }


    public function validate(): bool
    {
        foreach ($this->rule() as $field => $rules) {
            $value = $this->$field;

            echo $field . ' - ' . $value . '<br>';

            foreach ($rules as $rule) {
                $rule_name = $rule;

                if (!is_string($rule_name)) $rule_name = $rule[0];

                if ($rule_name === Validation::REQUIRED && !$value) {
                    $this->addErrors($field, Validation::REQUIRED);

                } elseif ($rule_name !== Validation::REQUIRED && $value) {

                    $pattern = Validation::getPattern($rule_name);
                    if(!preg_match($pattern, $value)) $this->addErrors($field, $rule);

                }
            }
        }
        return false;
    }


    private function addErrors(string $field, string $rule)
    {
        $this->errors[$field][] = $this->errorsMessages($rule);
    }

    private function errorsMessages(string $rule)
    {
        return Validation::getMessage($rule);
    }
}