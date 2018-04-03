<?php

namespace Softease\App;

class Validator
{
    /**
     * @array
     */
    private $data;
    protected $errors = [];

    /**
     * @param array $data
     * return array|bool
     */
    public function validates(array $data)
    {
        $this->data = $data;
    }

    public function validate(string $field, string $method, ...$parameters)
    {
        if (!isset($this->data[$field])) {
            $this->errors[$field] = "Le champs $field n'est pas rempli";
        } else {
            call_user_func([$this, $method], $field, ...$parameters);
        }
    }

    public function minLength(string $field, int $length)
    {
        if (mb_strlen($field) < $length) {
            $this->errors[$field] = "La taille minimum est de $length caractères";
            return false;
        }
        return true;
    }

    public function date(string $field)
    {
        if (!\DateTime::createFromFormat('Y-m-d', $this->data[$field])) {
            $this->errors[$field] = 'La Date n\'est pas valide.';
            return false;
        }
        return true;
    }

    public function time(string $field)
    {
        if (!\DateTime::createFromFormat('H:i', $this->data[$field])){
        $this->errors[$field] = 'Le temps ne semble pas valide.';
        return false;
        }
        return true;
    }

    public function beforeEnd(string $start, string $end)
    {
        if($this->time($start) && $this->time($end)){
            $startTime = \DateTime::createFromFormat('H:i', $this->data[$start]);
            $endTime = \DateTime::createFromFormat('H:i', $this->data[$end]);
            if($startTime->getTimestamp() > $endTime->getTimestamp()){
                $this->errors[$start] = "La valeur de start ne peut pas etre plus petit que $end";
                return false;
            }
            return true;
        }
        return false;
    }


}