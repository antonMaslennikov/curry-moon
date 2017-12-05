<?php
namespace smashEngine\core\helpers;

use Exception;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

/**
 * Class Thumbnailer
 * @package smashEngine\core\helpers
 */
class Thumbnailer
{
    /**
     * @var array
     */
    public $options = [];

    /**
     * @var
     */
    private $_basePath;
    /**
     * @var
     */
    private $_baseUrl;

    /**
     * @param string $file Полный путь к исходному файлу в файловой системе
     * @param string $uploadDir Подпапка в папке с миниатюрами куда надо поместить изображение
     * @param int $width Ширина изображения. Если не указана - будет вычислена из высоты
     * @param int $height Высота изображения. Если не указана - будет вычислена из ширины
     * @param boolean $crop Обрезка миниатюры по размеру
     * @return string
     * @throws Exception
     */
    public function thumbnail(
        $file,
        $uploadDir,
        $width = 0,
        $height = 0,
        $crop = true
    ) {
        if (!$width && !$height) {
            throw new Exception("Incorrect width/height");
        }

	    $prefix = $crop ? 'cropped_' : '';
        $name = $width . 'x' . $height . '_' . $prefix . basename($file);

        $thumbFile = $uploadDir . DIRECTORY_SEPARATOR . $name;
        $thumbMode = $crop ? ImageInterface::THUMBNAIL_OUTBOUND : ImageInterface::THUMBNAIL_INSET;

        if (!file_exists($thumbFile)) {

            if (false === File::checkPath($uploadDir)) {

                throw new Exception(sprintf('Directory "%s" is not acceptable for write!',$uploadDir));
            }

            $img = Imagine::getImagine()->open($file);

            $originalWidth = $img->getSize()->getWidth();
            $originalHeight = $img->getSize()->getHeight();

            if (!$width) {
                $width = $height / $originalHeight * $originalWidth;
            }

            if (!$height) {
                $height = $width / $originalWidth * $originalHeight;
            }

            $img->thumbnail(new Box($width, $height), $thumbMode)->save($thumbFile, $this->options);
        }

        return File::getUrlForAbsolutePath($thumbFile);
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        if ($this->_basePath === null) {

            $this->_basePath = Yii::app()->uploadManager->getBasePath() . DIRECTORY_SEPARATOR . $this->thumbDir;
        }

        return $this->_basePath;
    }

    /**
     * @param $value
     */
    public function setBasePath($value)
    {
        $this->_basePath = rtrim($value, DIRECTORY_SEPARATOR);
    }
}
