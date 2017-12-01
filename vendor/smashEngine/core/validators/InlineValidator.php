<?php
namespace smashEngine\core\validators;

use smashEngine\core\models\FormModel;

/**
 * Class InlineValidator
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
class InlineValidator extends Validator {

    /**
     * @var string
     */
    protected $method;
    /**
     * @var array
     */
    protected $params;
    /**
     * @var string the name of the method that returns the client validation code (See {@link clientValidateAttribute}).
     */
    protected $clientValidate;


    /**
     * @param FormModel $object
     * @param string $attribute
     */
    protected function validateAttribute($object,$attribute) {

        $method=$this->method;
        $object->$method($attribute,$this->params);
    }


    public function setMethod($method) {

        $this->method = $this->trim($method);
    }

    /**
     * @param mixed $param
     */
    public function setParams($param) {

        $this->params = (array) $param;
    }
}