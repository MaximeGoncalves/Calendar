<?php

namespace Softease\Calendar;


use Softease\App\Validator;

class EventValidator extends Validator
{
    /**
     * @param array $data
     * return array|bool
     * @return array
     */
    public function validates(array $data)
    {
        parent::Validates($data);
        $this->validate('name', 'minLength', 3);
        $this->validate('date', 'date');
        $this->validate('start', 'time');
        $this->validate('start', 'beforeEnd', 'end');
        return $this->errors;
    }


}