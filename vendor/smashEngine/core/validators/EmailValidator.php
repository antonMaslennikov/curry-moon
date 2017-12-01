<?php
namespace smashEngine\core\validators;

use smashEngine\core\models\FormModel;

/**
 * Class EmailValidator
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
class EmailValidator extends Validator {

    /**
     * @var string
     */
    private $pattern='/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';


    /**
     * @var string
     */
    private $fullPattern='/^[^@]*<[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?>$/';


    /**
     * @var boolean
     */
    protected $allowName=false;


    /**
     * @var boole
     */
    protected $checkMX=false;


    /**
     * @var boolean
     */
    protected $checkPort=false

    ;
    /**
     * @var boolean
     */
    public $allowEmpty=false;


    /**
     * @param bool $isAllowName
     */
    public function setAllowName($isAllowName) {

        $this->allowName = (bool) $isAllowName;
    }


    /**
     * @param bool $isAllowEmpty
     */
    public function setAllowEmpty($isAllowEmpty) {

        $this->allowName = (bool) $isAllowEmpty;
    }


    /**
     * @param bool $isCheckMX
     */
    public function setCheckMX($isCheckMX) {

        $this->checkMX = (bool) $isCheckMX;
    }


    /**
     * @param bool $isCheckPorts
     */
    public function setCheckPort($isCheckPorts) {

        $this->checkMxPorts = (bool) $isCheckPorts;
    }


    /**
     * @param FormModel $object
     * @param string $attribute
     */
    protected function validateAttribute($object,$attribute) {

        $value=$object->$attribute;

        if($this->allowEmpty && $this->isEmpty($value)) return;

        if(!$this->validateValue($value))
        {
            $message=$this->message!==null?$this->message:'{attribute} не является правильным E-Mail адресом.';
            $this->addError($object,$attribute,$message);
        }
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function validateValue($value)
    {
        // make sure string length is limited to avoid DOS attacks
        $valid=is_string($value)
            && strlen($value)<=254
            && (preg_match($this->pattern,$value) || $this->allowName && preg_match($this->fullPattern,$value));

        if($valid)

            $domain=rtrim(substr($value,strpos($value,'@')+1),'>');

        if($valid && $this->checkMX && function_exists('checkdnsrr'))

            $valid=checkdnsrr($domain,'MX');

        if($valid && $this->checkPort && function_exists('fsockopen') && function_exists('dns_get_record'))

            $valid=$this->checkMxPorts($domain);

        return $valid;
    }

    /**
     * Retrieves the list of MX records for $domain and checks if port 25
     * is opened on any of these.
     *
     * @param $domain
     * @return bool
     */
    protected function checkMxPorts($domain)
    {
        $records=dns_get_record($domain, DNS_MX);

        if($records===false || empty($records)) return false;

        usort($records,array($this,'mxSort'));

        foreach($records as $record)
        {
            $handle=@fsockopen($record['target'],25);
            if($handle!==false)
            {
                fclose($handle);
                return true;
            }
        }
        return false;
    }


    /**
     * Determines if one MX record has higher priority as another
     * (i.e. 'pri' is lower).
     * @since 1.1.11
     * @param mixed $a first item for comparison
     * @param mixed $b second item for comparison
     *
     * @return boolean
     */
    protected function mxSort($a, $b)
    {
        return $a['pri']-$b['pri'];
    }
}