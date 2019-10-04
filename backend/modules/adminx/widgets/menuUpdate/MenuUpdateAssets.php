<?php

namespace backend\modules\adminx\widgets\menuUpdate;

use yii\web\AssetBundle;

class MenuUpdateAssets extends AssetBundle
{
    public $sourcePath = '@app/modules/adminx/widgets/menuUpdate/assets';
    public $publishOptions = ['forceCopy' => true];
    public $css = [
        'css/menuUpdate.css',
    ];
    public $js = [
        'js/xtree.js',
        'js/init.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
