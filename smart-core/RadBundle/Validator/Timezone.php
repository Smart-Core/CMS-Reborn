<?php

namespace SmartCore\RadBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class Timezone extends Constraint
{
    public $message = 'This value is not a valid timezone.';
}
