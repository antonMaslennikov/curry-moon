<?php
namespace smashEngine\core\validators;

use smashEngine\core\helpers\UploadedFile;
use smashEngine\core\helpers\Html;
use smashEngine\core\models\FormModel;

/**
 * Class FileValidator
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
class FileValidator extends Validator {

    /**
     * @var boolean
     */
    protected $allowEmpty=false;


    /**
     * @var mixed a list of file name extensions that are allowed to be uploaded.
     * This can be either an array or a string consisting of file extension names
     * separated by space or comma (e.g. "gif, jpg").
     * Extension names are case-insensitive. Defaults to null, meaning all file name
     * extensions are allowed.
     */
    protected $types;


    /**
     * @var mixed a list of MIME-types of the file that are allowed to be uploaded.
     * This can be either an array or a string consisting of MIME-types separated
     * by space or comma (e.g. "image/gif, image/jpeg"). MIME-types are
     * case-insensitive. Defaults to null, meaning all MIME-types are allowed.
     * In order to use this property fileinfo PECL extension should be installed.
     * @since 1.1.11
     */
    protected $mimeTypes;


    /**
     * @var integer the minimum number of bytes required for the uploaded file.
     * Defaults to null, meaning no limit.
     * @see tooSmall
     */
    protected $minSize;


    /**
     * @var integer the maximum number of bytes required for the uploaded file.
     * Defaults to null, meaning no limit.
     * Note, the size limit is also affected by 'upload_max_filesize' INI setting
     * and the 'MAX_FILE_SIZE' hidden field value.
     * @see tooLarge
     */
    protected $maxSize;


    /**
     * @var string the error message used when the uploaded file is too large.
     * @see maxSize
     */
    protected $tooLarge;


    /**
     * @var string the error message used when the uploaded file is too small.
     * @see minSize
     */
    protected $tooSmall;


    /**
     * @var string the error message used when the uploaded file has an extension name
     * that is not listed among {@link types}.
     */
    protected $wrongType;


    /**
     * @var string the error message used when the uploaded file has a MIME-type
     * that is not listed among {@link mimeTypes}. In order to use this property
     * fileinfo PECL extension should be installed.
     * @since 1.1.11
     */
    protected $wrongMimeType;


    /**
     * @var integer the maximum file count the given attribute can hold.
     * It defaults to 1, meaning single file upload. By defining a higher number,
     * multiple uploads become possible.
     */
    protected $maxFiles=1;


    /**
     * @var string the error message used if the count of multiple uploads exceeds
     * limit.
     */
    protected $tooMany;


    /**
     * @param FormModel $object
     * @param string $attribute
     */
    protected function validateAttribute($object,$attribute) {

        $files=$object->$attribute;

        if($this->maxFiles > 1)
        {
            if(!is_array($files) || !isset($files[0]) || !$files[0] instanceof UploadedFile)
                $files = UploadedFile::getInstances($object, $attribute);

            if(array()===$files) return $this->emptyAttribute($object, $attribute);

            if(count($files) > $this->maxFiles)
            {
                $message=$this->tooMany!==null
                    ?$this->tooMany
                    : sprintf(
                        '{attribute} не может принять более %s файлов.',
                        $this->maxFiles
                    );

                $this->addError($object, $attribute, $message);
            }
            else
                foreach($files as $file)

                    $this->validateFile($object, $attribute, $file);
        }
        else
        {
            if (is_array($files))
            {
                if (count($files) > 1)
                {
                    $message=$this->tooMany!==null
                        ?$this->tooMany
                        : sprintf(
                            '{attribute} не может принять более %s файлов.',
                            $this->maxFiles
                        );

                    $this->addError($object, $attribute, $message);

                    return;
                }
                else

                    $file = empty($files) ? null : reset($files);
            }
            else {

                $file = $files;
            }

            if(!$file instanceof UploadedFile)
            {
                $file = UploadedFile::getInstance($object, $attribute);

                if(null===$file)
                    return $this->emptyAttribute($object, $attribute);
            }

            $this->validateFile($object, $attribute, $file);
        }
    }


    /**
     * @param bool $allowEmpty
     */
    public function setAllowEmpty($allowEmpty) {

        $this->allowEmpty = (bool) $allowEmpty;
    }


    /**
     * @param mixed $types
     */
    public function setTypes($types) {

        $this->types = $types;
    }


    /**
     * @param mixed $mimeTypes
     */
    public function setMimeTypes($mimeTypes) {

        $this->mimeTypes = $mimeTypes;
    }


    /**
     * @param integer $minSize
     */
    public function setMinSize($minSize) {

        $this->minSize = (int) $minSize;
    }


    /**
     * @param integer $maxSize
     */
    public function setMaxSize($maxSize) {

        $this->maxSize = (int) $maxSize;
    }


    /**
     * @param integer $maxFiles
     */
    public function setMaxFiles($maxFiles) {

        $this->maxFiles = (int) $maxFiles;
    }


    /**
     * @param string $tooLarge
     */
    public function setTooLarge($tooLarge) {

        $this->tooLarge = $this->trim($tooLarge);
    }


    /**
     * @param string $tooSmall
     */
    public function setTooSmall($tooSmall) {

        $this->tooSmall = $this->trim($tooSmall);
    }


    /**
     * @param string $wrongType
     */
    public function setWrongType($wrongType) {

        $this->wrongType = $this->trim($wrongType);
    }


    /**
     * @param string $tooMany
     */
    public function setTooMany($tooMany) {

        $this->tooMany = $this->trim($tooMany);
    }


    /**
     * @param FormModel $object
     * @param string $attribute
     */
    protected function emptyAttribute($object, $attribute)
    {
        if($this->safe)
            $object->$attribute=null;

        if(!$this->allowEmpty)
        {
            $message=$this->message!==null?$this->message : 'Необходимо загрузить файл «{attribute}».';

            $this->addError($object,$attribute,$message);
        }
    }


    /**
     * @param FormModel $object
     * @param string $attribute
     * @param UploadedFile $file
     *
     * @throws \Exception
     */
    protected function validateFile($object, $attribute, $file)
    {
        $error=(null===$file ? null : $file->getError());

        if(  $error==UPLOAD_ERR_INI_SIZE
          || $error==UPLOAD_ERR_FORM_SIZE
          || $this->maxSize!==null
          && $file->getSize()>$this->maxSize)
        {
            $message=$this->tooLarge!==null
                ?$this->tooLarge
                : sprintf(
                    'Размер файла "%s" слишком велик, он не должен превышать %s байт.',
                    Html::encode($file->getName()),
                    $this->getSizeLimit()
                );

            $this->addError($object,$attribute,$message);

            if($error!==UPLOAD_ERR_OK) return;
        }
        elseif($error!==UPLOAD_ERR_OK)
        {
            if($error==UPLOAD_ERR_NO_FILE) return $this->emptyAttribute($object, $attribute);

            elseif($error==UPLOAD_ERR_PARTIAL)

                throw new \Exception(
                    sprintf(
                        'Файл "%s" был загружен не полностью.',
                        Html::encode($file->getName())
                    ));

            elseif($error==UPLOAD_ERR_NO_TMP_DIR)

                throw new \Exception(
                    sprintf(
                        'Не найдена временная директория для хранения загруженного файла "%s".',
                        Html::encode($file->getName())
                    ));

            elseif($error==UPLOAD_ERR_CANT_WRITE)

                throw new \Exception(
                    sprintf(
                        'Не удалось записать загруженный файл "%s" на диск.',
                        Html::encode($file->getName())
                    ));

            elseif(defined('UPLOAD_ERR_EXTENSION') && $error==UPLOAD_ERR_EXTENSION)  // available for PHP 5.2.0 or above

                throw new \Exception('PHP расширение прервало загрузку файла.');

            else
                throw new \Exception(
                    sprintf(
                        'Не удалось загрузить файл «%s» из-за неизвестной ошибки.',
                        Html::encode($file->getName())
                    ));
        }

        if($this->minSize!==null && $file->getSize()<$this->minSize)
        {
            $message = $this->tooSmall!==null
                ?$this->tooSmall
                :sprintf(
                    'Размер файла "%s" слишком мал, он не должен быть менее %s байт.',
                    Html::encode($file->getName()),
                    $this->minSize
                );

            $this->addError($object,$attribute,$message);
        }

        if($this->types!==null)
        {
            if(is_string($this->types))

                $types=preg_split('/[\s,]+/',strtolower($this->types),-1,PREG_SPLIT_NO_EMPTY);
            else

                $types=$this->types;

            if(!in_array(strtolower($file->getExtensionName()),$types))
            {
                $message=$this->wrongType!==null
                    ?$this->wrongType
                    :sprintf(
                        'Файл "%s" не может быть загружен. Разрешена загрузка файлов только со следующими расширениями: %s.',
                        Html::encode($file->getName()),
                        implode(', ',$types)
                    );
                $this->addError($object,$attribute,$message);
            }
        }

        if($this->mimeTypes!==null && !empty($file->tempName))
        {
            if(function_exists('finfo_open'))
            {
                $mimeType=false;

                if($info=finfo_open(defined('FILEINFO_MIME_TYPE') ? FILEINFO_MIME_TYPE : FILEINFO_MIME))

                    $mimeType=finfo_file($info,$file->getTempName());
            }
            elseif(function_exists('mime_content_type'))

                $mimeType=mime_content_type($file->getTempName());
            else

                throw new \Exception('Для того, чтобы использовать валидацию FileValidator по MIME-типу, установите PECL-расширение fileinfo.');

            if(is_string($this->mimeTypes))

                $mimeTypes=preg_split('/[\s,]+/',strtolower($this->mimeTypes),-1,PREG_SPLIT_NO_EMPTY);

            else

                $mimeTypes=$this->mimeTypes;

            if($mimeType===false || !in_array(strtolower($mimeType),$mimeTypes))
            {
                $message=$this->wrongMimeType!==null
                    ?$this->wrongMimeType
                    :sprintf(
                        'Файл "%s" не может быть загружен. Можно загружать только файлы со следующими MIME-типами: %s.',
                        Html::encode($file->getName()),
                        implode(', ',$mimeTypes)
                    );

                $this->addError($object,$attribute,$message);
            }
        }
    }


    protected function getSizeLimit()
    {
        $limit=ini_get('upload_max_filesize');

        $limit=$this->sizeToBytes($limit);

        if($this->maxSize!==null && $limit>0 && $this->maxSize<$limit)

            $limit=$this->maxSize;

        if(isset($_POST['MAX_FILE_SIZE']) && $_POST['MAX_FILE_SIZE']>0 && $_POST['MAX_FILE_SIZE']<$limit)

            $limit=$_POST['MAX_FILE_SIZE'];

        return $limit;
    }


    protected function sizeToBytes($sizeStr)
    {
        // get the latest character
        switch (strtolower(substr($sizeStr, -1)))
        {
            case 'm': return (int)$sizeStr * 1048576; // 1024 * 1024
            case 'k': return (int)$sizeStr * 1024; // 1024
            case 'g': return (int)$sizeStr * 1073741824; // 1024 * 1024 * 1024
            default: return (int)$sizeStr; // do nothing
        }
    }
}