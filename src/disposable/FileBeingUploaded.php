<?php


namespace vr\core\disposable;

use Yii;
use yii\base\BaseObject;

/**
 * Class FileBeingUploaded
 * @package vr\core\disposable
 * @property string filename
 */
class FileBeingUploaded extends BaseObject implements IDisposable
{
    /**
     * @var
     */
    private $_filename;

    /**
     * @var
     */
    private $_handle;

    /**
     * @param string $content
     * @param string|null $extension
     * @return self
     */
    public static function fromContent(string $content, string $extension = null)
    {
        $instance = new self();
        $instance->generateFileName($extension);

        file_put_contents($instance->_filename, $content);
        return $instance;
    }

    /**
     * @param $extension
     */
    protected function generateFileName($extension)
    {
        $filename        = uniqid(time());
        $this->_filename = Yii::getAlias("@runtime/{$filename}.{$extension}");
    }

    /**
     * @param string $mode
     * @return resource
     */
    public function open($mode = 'r')
    {
        $this->_handle = fopen($this->_filename, $mode);
        return $this->_handle;
    }

    /**
     * @inheritDoc
     */
    public function dispose()
    {
        if ($this->_handle) {
            fclose($this->_handle);
        }

        unlink($this->_filename);
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->_filename;
    }
}