<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 10.12.18
 * Time: 10:43
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@bower/font-awesome';
    public $css = [
        'css/all.css',
    ];
}