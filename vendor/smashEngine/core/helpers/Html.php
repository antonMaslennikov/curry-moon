<?php
namespace smashEngine\core\helpers;

class Html {

    /**
     * Generates HTML name for given model.
     * @see Html::setModelNameConverter()
     * @param FormModel|string $model the data model or the model class name
     * @return string the generated HTML name value
     * @since 1.1.14
     */
    public static function modelName($model)
    {
        $className=is_object($model) ? get_class($model) : (string)$model;

        return trim(str_replace('\\','_',$className),'_');
    }


    /**
     * Generates input name for a model attribute.
     * Note, the attribute name may be modified after calling this method if the name
     * contains square brackets (mainly used in tabular input) before the real attribute name.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @return string the input name
     */
    public static function resolveName($model,&$attribute)
    {
        $modelName=self::modelName($model);

        if(($pos=strpos($attribute,'['))!==false)
        {
            if($pos!==0)  // e.g. name[a][b]
                return $modelName.'['.substr($attribute,0,$pos).']'.substr($attribute,$pos);

            if(($pos=strrpos($attribute,']'))!==false && $pos!==strlen($attribute)-1)  // e.g. [a][b]name
            {
                $sub=substr($attribute,0,$pos+1);
                $attribute=substr($attribute,$pos+1);
                return $modelName.$sub.'['.$attribute.']';
            }
            if(preg_match('/\](\w+\[.*)$/',$attribute,$matches))
            {
                $name=$modelName.'['.str_replace(']','][',trim(strtr($attribute,array(']['=>']','['=>']')),']')).']';
                $attribute=$matches[1];
                return $name;
            }
        }
        return $modelName.'['.$attribute.']';
    }


	public static function getIdByResolveName($model,&$attribute)
	{
		$name = self::resolveName($model, $attribute);

		return str_replace(array('[]','][','[',']',' '),array('','_','_','','_'),$name);
	}


    /**
     * Encodes special characters into HTML entities.
     * The 'UTF-8' will be used for encoding.
     *
     * @param string $text data to be encoded
     * @return string the encoded data
     * @see http://www.php.net/manual/en/function.htmlspecialchars.php
     */
    public static function encode($text)
    {
        return htmlspecialchars($text,ENT_QUOTES,'UTF-8');
    }
}