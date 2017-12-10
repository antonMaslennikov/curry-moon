<?php
namespace smashEngine\core\validators;

use ReflectionClass;
use smashEngine\core\models\FormModel;

/**
 * Class Validator
 * @package smashEngine\core\validators
 */
abstract class Validator
{
    CONST IGNORE_SYMBOLS = " \t\n\r\0\x0B,";

    /**
     * @var array|null
     */
    protected $attributes;

    /**
     * @var string|null
     */
    protected $message;

    /**
     * @var bool|true
     */
    public $safe=true;


    /**
     * @var array list of built-in validators (name=>class)
     */
    public static $listValidators = array(
        'required' => '\smashEngine\core\validators\RequiredValidator',
        'url' => '\smashEngine\core\validators\UrlValidator',
        'safe' => '\smashEngine\core\validators\SafeValidator',
        'unsafe' => '\smashEngine\core\validators\UnSafeValidator',
        'email'=>'\smashEngine\core\validators\EmailValidator',
        'filter'=>'\smashEngine\core\validators\FilterValidator',
        'length'=>'\smashEngine\core\validators\StringValidator',
	    'in'=>'\smashEngine\core\validators\RangeValidator',
	    'file'=>'\smashEngine\core\validators\FileValidator',
	    'boolean'=>'\smashEngine\core\validators\BooleanValidator',
        'lat'=>'\smashEngine\core\validators\LatValidator',

	    /*
	?'match'=>'RegularExpressionValidator',
	?'compare'=>'CompareValidator',
	?'numerical'=>'NumberValidator',
	?'type'=>'TypeValidator',
	'default'=>'DefaultValueValidator',

	'date'=>'DateValidator',

   */
    );

    /**
     * @param string $name
     * @param FormModel $object
     * @param mixed $attributes
     * @param array $params
     *
     * @return Validator|null
     */
    public final static function createValidator($name, $object, $attributes, $params = array())
    {
        if (is_string($attributes)) {

            $attributes = (array)trim($attributes, self::IGNORE_SYMBOLS);
        }

        if (method_exists($object, $name)) {

            $validator = new InlineValidator();
            $validator->setAttributes($attributes);
            $validator->setMethod($name);

            $validator->setParams($params);
        } else {

            $params['attributes']=$attributes;

            if (!isset(self::$listValidators[$name])) return null;

            $className = self::$listValidators[$name];

            $validator = new $className;

            foreach ($params as $param => $value) {

                $validator->setProperty($param, $value);
            }
        }

        return $validator;
    }


    /**
     * @param string $param
     * @param mixed $value
     */
    public function setProperty($param, $value) {

        $method = 'set'.ucfirst($param);

        if (method_exists($this, $method)) {

            $this->$method($value);
        } else {

            $class = new ReflectionClass(get_called_class());

            if ($class->hasProperty($param)) {

                $property = $class->getProperty($param);

                if (!$property->isPrivate() && !$property->isStatic()) {

                    printr(sprintf('В классе "%s" не определен метод "%s"', get_class($this), $method));
                }

            }
        }
    }


    /**
     * @param bool $safe
     */
    public function setSafe($safe) {

        $this->safe = (bool) $safe;
    }


    /**
     * @return bool|true
     */
    public function getSafe() {

        return $this->safe;
    }


    /**
     * @param string $message
     */
    public function setMessage($message) {

        $this->message = $this->trim($message);
    }


    /**
     * @return null|string
     */
    public function getMessage() {

        return $this->message;
    }


    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes = []) {

        foreach ($attributes as $attr) {
            $this->attributes[$attr] = $attr;
        }
    }


    /**
     * @return array|null
     */
    public function getAttributes() {

        return $this->attributes;
    }


    /**
     * @param FormModel $object
     * @param array $attributes
     */
    public function validate($object,$attributes=null)
    {
        if(is_array($attributes))
            $attributes=array_intersect($this->getAttributes(),$attributes);
        else
            $attributes=$this->attributes;

        foreach($attributes as $attribute)
        {
            if(!$object->hasErrors($attribute))
                $this->validateAttribute($object,$attribute);
        }
    }

    /**
     * @param FormModel $object
     * @param string $attribute
     */
    abstract protected function validateAttribute($object,$attribute);


    /**
     * @param mixed $value
     * @param bool $trim
     *
     * @return bool
     */
    protected function isEmpty($value,$trim=false)
    {
        return $value===null || $value===array() || $value==='' || $trim && is_scalar($value) && trim($value)==='';
    }


    /**
     * @param FormModel $object
     * @param mixed $attribute
     * @param string $message
     * @param array $params
     */
    protected function addError($object,$attribute,$message,$params=array())
    {
        $params['{attribute}']=$object->getAttributeLabel($attribute);
        $object->addError($attribute,strtr($message,$params));
    }


    /**
     * @param string $value
     * @return string
     */
    protected function trim($value) {

        return trim($value, self::IGNORE_SYMBOLS);
    }
}