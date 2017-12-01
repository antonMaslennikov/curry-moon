<?php
namespace smashEngine\core\validators;

use smashEngine\core\models\FormModel;

/**
 * Class RequiredValidator
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
class RequiredValidator extends Validator {

    /**
     * @var string
     */
    protected $requiredValue;

    /**
     * @var bool
     */
    protected $strict=false;

    /**
     * @var bool
     */
    protected $trim=true;

	/**
	 * @var boolean
	 */
	protected $allowEmpty=false;


	/**
     * @param FormModel $object
     * @param string $attribute
     */
    protected function validateAttribute($object,$attribute) {

        $value=$object->$attribute;

	    if($this->allowEmpty && $this->isEmpty($value)) return;

        if($this->requiredValue!==null)
        {
            if(!$this->strict && $value!=$this->requiredValue || $this->strict && $value!==$this->requiredValue)
            {
                $message=$this->message!==null
	                ?$this->message
	                :sprintf(
		                'В Параметр "{attribute}" должен иметь значение "%s"!',
		                $this->requiredValue
	                );
                $this->addError($object,$attribute,$message);
            }
        }
        elseif($this->isEmpty($value,$this->trim))
        {
            $message=$this->message!==null?$this->message:'Необходимо заполнить поле «{attribute}».';
            $this->addError($object,$attribute,$message);
        }
    }


	/**
	 * @param bool $allowEmpty
	 */
	public function setAllowEmpty($allowEmpty) {

		$this->allowEmpty = (bool) $allowEmpty;
	}

    /**
     * @param string $value
     */
    public function setRequiredValue($value) {

        $this->requiredValue = $this->trim($value);
    }

    /**
     * @param bool $isStrict
     */
    public function setStrict($isStrict) {

        $this->strict = (bool) $isStrict ;
    }


    /**
     * @param bool $isTrim
     */
    public function setTrim($isTrim) {

        $this->trim = (bool) $isTrim;
    }
}