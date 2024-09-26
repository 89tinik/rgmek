<?php

namespace app\modules\ticket\assets;

use yii\web\AssetBundle;

class TicketAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css',
        'css/ticket.css?v=0.1'
    ];
    public $js = [
        '//cdn.tiny.cloud/1/eu1na6v7c1x7bw9ufnghv4tpz8jwds9r5j8a1rbee8mu0736/tinymce/6/tinymce.min.js',
        'js/jquery-ui.js',
        'js/datepicker-ru.js',
        '//cdn.jsdelivr.net/momentjs/latest/moment.min.js',
        '//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js',
        'js/ticket.js?v=2',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}