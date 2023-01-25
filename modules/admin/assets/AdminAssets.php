<?php

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AdminAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/admin.css?v=0.0'
    ];
    public $js = [
        '//cdn.tiny.cloud/1/eu1na6v7c1x7bw9ufnghv4tpz8jwds9r5j8a1rbee8mu0736/tinymce/6/tinymce.min.js',
        'js/admin.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}