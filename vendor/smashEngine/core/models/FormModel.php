<?php
namespace smashEngine\core\models;
use ReflectionClass;
use smashEngine\core\exception\appException;
use smashEngine\core\helpers\Html;
use smashEngine\core\validators\Validator;

/**
 * Class FormModel
 */
class FormModel {

    private $_validators = null;
    private $_errors = [];
    private static $_attributes = [];


    public function __construct() {

        $this->init();
    }


    protected function init() {


    }

    public function rules() {

        return [];
    }

    /**
     * @param mixed|null $names array or string
     *
     * @return array
     */
    public final function getAttributes($names = null) {

        $values = [];

        foreach ($this->attributeNames() as $name) {

            $values[$name] = $this->$name;
        }

        if ($names === null) return $values;

        if (is_array($names)) {

            $data = [];

            foreach ($names as $name) {

                $data[$name] = isset($values[$name])?$values[$name]:null;
            }

            return $data;
        } else {

            return isset($values[$name])?$values[$name]:null;
        }
	}


	/**
	 * @param mixed|null $names array or string
	 *
	 * @return array
	 */
	public function attributesHtmlName($names = null) {

		$values = [];

		foreach ($this->attributeNames() as $name) {

			$values[$name] = Html::resolveName(get_called_class(), $name);
		}

		if ($names === null) return $values;

		if (is_array($names)) {

			$data = [];

			foreach ($names as $name) {

				$data[$name] = isset($values[$name])?$values[$name]:null;
			}

			return $data;
		} else {

			return isset($values[$name])?$values[$name]:null;
		}
	}


	/**
	 * @param mixed|null $names array or string
	 *
	 * @return array
	 */
	public function attributesHtmlId($names = null) {

		$values = [];

		foreach ($this->attributeNames() as $name) {

			$values[$name] = Html::getIdByResolveName(get_called_class(), $name);
		}

		if ($names === null) return $values;

		if (is_array($names)) {

			$data = [];

			foreach ($names as $name) {

				$data[$name] = isset($values[$name])?$values[$name]:null;
			}

			return $data;
		} else {

			return isset($values[$name])?$values[$name]:null;
		}
	}


    protected function afterConstruct() {}


	public final function attributeNames() {

        $className=get_called_class();

        if (!isset(self::$_attributes[$className])) {

            $class = new ReflectionClass($className);
            $names = [];

            foreach ($class->getProperties() as $property) {

                $name = $property->getName();
                if ($property->isPublic() && !$property->isStatic()) {

                    $names[$name] = $name;
                }
            }
            self::$_attributes[$className] = $names;

            return self::$_attributes[$className];
        } else {

            return self::$_attributes[$className];
        }
    }


	public function setAttributes($values, $safeOnly=true)
    {
        if(!is_array($values)) return;

        $attributes=array_flip($safeOnly ? $this->getSafeAttributeNames() : $this->attributeNames());

        foreach($values as $name=>$value)
        {
            if(isset($attributes[$name]))
                $this->$name=$value;
        }
    }


    public final function getValidators($attribute=null)
    {
        if($this->_validators===null) $this->_validators=$this->createValidators();

        $validators= [];

        foreach($this->_validators as $validator)
        {
            if($attribute===null || in_array($attribute,$validator->getAttributes(),true))
                $validators[]=$validator;
        }
        return $validators;
    }


    public function createValidators()
    {
        $validators = [];

        foreach($this->rules() as $rule)
        {
            if(isset($rule[0],$rule[1]))  // attribute, validator name
                $validators[] = Validator::createValidator($rule[1],$this,$rule[0],array_slice($rule,2));
            else
                throw new AppException(sprintf('{%s} has an invalid validation rule. The rule must specify attributes to be validated and the validator name.',get_class($this)));
        }
        return $validators;
    }


    protected function getSafeAttributeNames() {

        $attributes=array();
        $unsafe=array();

        foreach ($this->getValidators() as $validator) {

            if(!$validator->safe)
            {
                foreach($validator->getAttributes() as $name)
                    $unsafe[]=$name;
            } else {

                foreach($validator->getAttributes() as $name)
                    $attributes[$name]=true;
            }
        }

        foreach($unsafe as $name) unset($attributes[$name]);

        return array_keys($attributes);
    }


    public final function validate($attributes = null, $clearErrors=true) {

        if($clearErrors) $this->clearErrors();

        foreach ($this->getValidators() as $validator) {

            $validator->validate($this, $attributes);
        }

        return !$this->hasErrors();
    }


    public function clearErrors($attribute=null) {

        if($attribute===null) $this->_errors=array();
        else
            unset($this->_errors[$attribute]);
    }

    public function attributeLabels()
    {
        return [];
    }


    public function hasErrors($attribute=null) {

        if($attribute===null)
            return $this->_errors!==array();
        else
            return isset($this->_errors[$attribute]);
    }


    public function getAttributeLabel($attribute)
    {
        $labels=$this->attributeLabels();
        if(isset($labels[$attribute]))
            return $labels[$attribute];
        else
            return $this->generateAttributeLabel($attribute);
    }

    public function generateAttributeLabel($name)
    {
        return ucwords(trim(strtolower(str_replace(array('-','_','.'),' ',preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $name)))));
    }


    public function addError($attribute,$error)
    {
        $this->_errors[$attribute][]=$error;
    }


    public function addErrors($errors)
    {
        foreach($errors as $attribute=>$error)
        {
            if(is_array($error))
            {
                foreach($error as $e) $this->addError($attribute, $e);
            }
            else
                $this->addError($attribute, $error);
        }
    }


	/**
	 * @param string|null $attribute
	 * @return array
	 */
	public function getErrors($attribute=null)
	{
		if($attribute===null) return $this->_errors;
		else
			return isset($this->_errors[$attribute])
						? $this->_errors[$attribute]
						: array();
	}


	public function getErrorSummary() {

		$errorsModel = $this->getErrors();

		$content='';

		foreach($errorsModel as $errors)
		{
			foreach($errors as $error)
			{
				if($error!='') $content.="<li>$error</li>".PHP_EOL;
			}
		}

		if($content!=='')
		{
			return implode(PHP_EOL, [
				'<ul>',
				$content,
				'</ul>'
			]);
		}
		else
			return '';
	}


	/**
	 * @return array
	 */
	public function getDataForTemplate() {

		return [
			'label'=>$this->attributeLabels(),
			'name'=>$this->attributesHtmlName(),
			'id'=>$this->attributesHtmlId(),
			'value'=>$this->getAttributes(),
			'error'=>$this->getErrors(),
			'errorSummary'=>$this->getErrorSummary(),
		];
	}
}