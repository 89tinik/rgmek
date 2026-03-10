<?php

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AdminAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/admin.css?v=1.0'
    ];
    public $js = [
        'js/jquery-ui.js',
        'js/tinymce.min.js',
        'js/datepicker-ru.js',
        'js/admin.js?v=2.1',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
