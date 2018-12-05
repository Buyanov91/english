<?php
/**
 * Created by PhpStorm.
 * User: default
 * Date: 01.11.18
 * Time: 14:18
 */

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'txt'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $path = Yii::$app->params['pathUploads'];
            $this->file->saveAs($path . $this->file->baseName . '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }
    }
}