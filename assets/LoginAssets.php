<?php


namespace app\assets;


use yii\web\AssetBundle;

class LoginAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '//fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap',
        'css/form-styler.css',
        'css/fancybox.css',
        'css/login.css?v=0.1',
    ];
    public $js = [
        'js/jquery.formstyler.min.js',
        'js/jquery.fancybox.min.js',
        'js/jquery.maskedinput.js',
        'js/jquery.validate.min.js',
        'js/jquery-ui.js',
        'js/login.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];

}