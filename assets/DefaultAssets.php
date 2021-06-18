<?php


namespace app\assets;


use yii\web\AssetBundle;

class DefaultAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '//fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap',
        '//fonts.googleapis.com/css2?family=PT+Mono&display=swap',
       'css/fancybox.css',
	'css/animate.css',
	'css/form-styler.css',
	'css/swiper.css',
	'css/jquery-ui.css',
	'css/main.css'
    ];
    public $js = [
        'js/jquery.formstyler.min.js',
        'js/jquery.fancybox.min.js',
        'js/jquery.maskedinput.js',
        'js/jquery.validate.min.js',
        'js/jquery-ui.js',
        'js/datepicker-ru.js',
        'js/swiper.min.js',
        'js/script.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}