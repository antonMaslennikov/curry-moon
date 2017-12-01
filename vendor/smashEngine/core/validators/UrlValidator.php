<?php
namespace smashEngine\core\validators;

use smashEngine\core\models\FormModel;

/**
 * Class UrlValidator
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
 *
 *
 * @property array|null $attributes
 * @property string|null $message
 * @property bool|true $safe
 */
class UrlValidator extends Validator{

    /**
     * @var string
     */
    private $pattern='/^{schemes}:\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)/i';

    /**
     * @var array
     */
    protected $validSchemes=['http','https'];

    /**
     * @var bool
     */
    protected $allowEmpty=false;

    /**
     * @var string
     */
    protected $defaultScheme;


    /**
     * @param FormModel $object
     * @param string $attribute
     */
    protected function validateAttribute($object,$attribute)
    {
        $value=$object->$attribute;

        if($this->allowEmpty && $this->isEmpty($value)) return;

        if(($value=$this->validateValue($value))!==false)

            $object->$attribute=$value;

        else
        {
            $message=$this->message!==null?$this->message:'{attribute} не является правильным URL.';
            $this->addError($object,$attribute,$message);
        }
    }


    /**
     * @param $string value
     *
     * @return bool|string
     */
    public function validateValue($value)
    {
        if(is_string($value) && strlen($value)<2000)  // make sure the length is limited to avoid DOS attacks
        {
            if($this->defaultScheme!==null && strpos($value,'://')===false)

                $value=$this->defaultScheme.'://'.$value;

            if(strpos($this->pattern,'{schemes}')!==false)
                $pattern=str_replace('{schemes}','('.implode('|',$this->validSchemes).')',$this->pattern);
            else
                $pattern=$this->pattern;

            if(preg_match($pattern,$value)) return $value;
        }
        return false;
    }


    /**
     * @param bool $is_empty
     */
    public function setAllowEmpty($is_empty) {

        $this->allowEmpty = (bool) $is_empty;
    }


    /**
     * @param string $scheme
     */
    public function setDefaultScheme($scheme) {

        $this->defaultScheme = $this->trim($scheme);
    }


    /**
     * @param array|string $scheme
     */
    public function setValidSchemes($scheme) {

        if (is_array($scheme))
            $this->validSchemes = $scheme;
        else
            $this->validSchemes = (array) $this->trim($scheme);
    }
}