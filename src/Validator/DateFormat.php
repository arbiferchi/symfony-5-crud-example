<?php

namespace App\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DateFormat extends Constraint
{
    public $message = 'The date - "{{ date }}" is not in the correct format - "{{ format }}".';
    public $optionExceptMessage = 'Not valid option - format';
    public $format;

    /**
     * DateFormat constructor.
     * @param null $options
     */
    public function __construct($options = null)
    {
        $this->format = array_shift($options);

        parent::__construct($options);
    }
}
