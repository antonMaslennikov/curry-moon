<?php
namespace smashEngine\core\validators;

use smashEngine\core\models\FormModel;

class BooleanValidator extends Validator {

	/**
	 * @var mixed
	 */
	protected $trueValue='1';

	/**
	 * @var mixed the value representing false status. Defaults to '0'.
	 */
	protected $falseValue='0';

	/**
	 * @var boolean
	 */
	protected $strict=false;

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

		if(!$this->validateValue($value))
		{
			$message=$this->message!==null
				?$this->message
				:sprintf(
					'{attribute} должно быть %s или %s',
					$this->trueValue,
					$this->falseValue
				);
			$this->addError($object,$attribute,$message);
		}
	}


	/**
	 * @param mixed $value
	 * @return bool
	 */
	protected function validateValue($value)
	{
		if ($this->strict)

			return $value===$this->trueValue || $value===$this->falseValue;
		else
			return $value==$this->trueValue || $value==$this->falseValue;
	}


	/**
	 * @param mixed $value
	 */
	public function setTrueValue($value) {

		$this->trueValue = $value;
	}


	/**
	 * @param mixed $value
	 */
	public function setFalseValue($value) {

		$this->falseValue = $value;
	}


	/**
	 * @param bool $strict
	 */
	public function setStrict($strict) {

		$this->strict = (bool) $strict;
	}


	/**
	 * @param bool $allowEmpty
	 */
	public function setAllowEmpty($allowEmpty) {

		$this->allowEmpty = (bool) $allowEmpty;
	}
}