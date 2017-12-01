<?php
namespace smashEngine\core\validators;

use smashEngine\core\models\FormModel;

/**
 * Class StringValidator
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
class StringValidator  extends Validator {

    /**
     * @var integer|null Defaults to null, meaning no maximum limit.
     */
    protected $max = null;


    /**
     * @var integer|null Defaults to null, meaning no minimum limit.
     */
    protected $min = null;


    /**
     * @var integer exact length. Defaults to null, meaning no exact length limit.
     */
    protected $is;

    /**
     * @var string user-defined error message used when the value is too short.
     */
    protected $tooShort;


    /**
     * @var string user-defined error message used when the value is too long.
     */
    protected $tooLong;


    /**
     * @var boolean
     */
    protected $allowEmpty=false;

    /**
     * @var string the encoding of the string value to be validated (e.g. 'UTF-8').
     */
    protected $encoding;

    /**
     * @param FormModel $object
     * @param string $attribute
     */
    protected function validateAttribute($object,$attribute) {

        $value=$object->$attribute;

        if($this->allowEmpty && $this->isEmpty($value)) return;

        if(is_array($value))
        {
            // https://github.com/yiisoft/yii/issues/1955
            $this->addError($object,$attribute,'{attribute} не верен!');
            return;
        }

        if(function_exists('mb_strlen') && $this->encoding!==false)
            $length=mb_strlen($value, $this->encoding ? $this->encoding : 'UTF-8');
        else
            $length=strlen($value);

        if($this->min!==null && $length<$this->min)
        {
            $message=$this->tooShort!==null?$this->tooShort:sprintf('{attribute} слишком короткий (Минимум: %s симв.).', $this->min);
            $this->addError($object,$attribute,$message);
        }
        if($this->max!==null && $length>$this->max)
        {
            $message=$this->tooLong!==null?$this->tooLong:sprintf('{attribute} слишком длинный (Максимум: %s симв.).', $this->max);
            $this->addError($object,$attribute,$message);
        }
        if($this->is!==null && $length!==$this->is)
        {
            $message=$this->message!==null?$this->message:sprintf('{attribute} неверной длины (Должен быть %s симв.).', $this->is);
            $this->addError($object,$attribute,$message);
        }
    }

    /**
     * @param int|null $min
     */
    public function setMin($min = null) {

        if ($min !== null) $this->min = (int) $min;
    }

    /**
     * @param int|null $max
     */
    public function setMax($max = null) {

        if ($max !== null) $this->max = (int) $max;
    }

    /**
     * @param int|null $is
     */
    public function setIs($is = null) {

        if ($is !== null) $this->is = (int) $is;
    }

    /**
     * @param string $tooShort
     */
    public function setTooShort($tooShort) {

        $this->tooShort = $this->trim($tooShort);
    }


    /**
     * @param string $tooLong
     */
    public function setTooLong($tooLong) {

        $this->tooLong = $this->trim($tooLong);
    }


    /**
     * @param bool $allowEmpty
     */
    public function setAllowEmpty($allowEmpty) {

        $this->allowEmpty = (bool) $allowEmpty;
    }


    /**
     * @param string $encoding
     */
    public function setEncoding($encoding) {

        $this->encoding = $this->trim($encoding);
    }

}