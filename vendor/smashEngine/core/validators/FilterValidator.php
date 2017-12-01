<?php
namespace smashEngine\core\validators;

use Exception;
use smashEngine\core\models\FormModel;

/**
 * Class FilterValidator
 * @package smashEngine\core\validators
 *
 * @method void setProperty(string $param, mixed $value)
 * @method void setSafe(bool $safe)
 * @method bool getSafe()
 * @method void setMessage(string $message)
 * @method string getMessage()
 * @method void setAttributes(array $attributes)
 * @method array getAttributes()
 * @method void validate(FormModel $object, array $attributes = null)
 * @method bool isEmpty(mixed $value, bool $trim = false)
 * @method void addError(FormModel $object, mixed $attribute, string $message, array $params = [])
 * @method string trim(string $value)
 *
 * @property array|null $attributes
 * @property string|null $message
 * @property bool|true $safe
 */
class FilterValidator extends Validator {

    /**
     * @var mixed
     */
    protected $filter;

    /**
     * @param FormModel $object
     * @param string $attribute
     */
    protected function validateAttribute($object,$attribute) {

        if($this->filter===null || !is_callable($this->filter))
            throw new Exception('Свойство "filter" должно быть определено правильным обратным вызовом (callback).');

        $object->$attribute=call_user_func_array($this->filter,array($object->$attribute));
    }


    /**
     * @param mixed $filter
     */
    public function setFilter($filter) {

        $this->filter = $filter;
    }
}