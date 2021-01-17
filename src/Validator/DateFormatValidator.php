<?php


namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DateFormatValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof DateFormat) {
            throw new UnexpectedTypeException($constraint, DateFormat::class);
        }
        /* if wrong option date format */
        if ( false === strtotime( date($constraint->format) ) ) {
            throw new MissingOptionsException( $constraint->optionExceptMessage, $constraint->format );
        }
        /* if format and value is not correct */
        if ( false === \DateTime::createFromFormat($constraint->format, $value) ) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ date }}', $value)
                ->setParameter('{{ format }}', $constraint->format)
                ->addViolation();
        }
    }
}
