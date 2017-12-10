<?php
namespace smashEngine\core\validators;

use smashEngine\core\models\FormModel;


/**
 * Class latValidator
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
class LatValidator extends Validator {

    /**
     * @var boolean строгое сравнение (оба типа и значения должны быть одинаковыми)
     */
    protected $strict=false;

    /**
     * @var boolean
     */
    protected $allowEmpty=false;

    /**
     * @var boolean следует ли инвертировать логику проверки. По умолчанию false. Если установлено значение true,
     * значение атрибута НЕ должно быть среди списка range
     **/
    protected $not=false;

    /**
     * @param FormModel $object
     * @param string $attribute
     */
    protected function validateAttribute($object,$attribute) {

        $value=$object->$attribute;

        if($this->allowEmpty && $this->isEmpty($value)) return;

        $result = false;

        $result=preg_match("/^[a-zA-Z0-9-_]+$/", $value);

        if(!$this->not && !$result)
        {
            $message=$this->message!==null?$this->message:'{attribute} должно содержать только английские символы.';
            $this->addError($object,$attribute,$message);
        }
        elseif($this->not && $result)
        {
            $message=$this->message!==null?$this->message:'{attribute} содержит только английские символы';
            $this->addError($object,$attribute,$message);
        }
    }


    /**
     * @param bool $strict
     */
    public function setStrict($strict) {

        $this->strict = (bool) $strict;
    }

    /**
     * @param bool $not
     */
    public function setNot($not) {

        $this->not = (bool) $not;
    }

    /**
     * @param bool $allowEmpty
     */
    public function setAllowEmpty($allowEmpty) {

        $this->allowEmpty = (bool) $allowEmpty;
    }
}