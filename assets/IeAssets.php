<?php


namespace app\assets;


use yii\web\AssetBundle;

class IeAssets extends AssetBundle
{
    public $js = [
        '//css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js',
        '//html5shim.googlecode.com/svn/trunk/html5.js',
    ];
    public $jsOptions = ['condition' => 'lte IE9',
        'position'=> \yii\web\View::POS_HEAD
    ];
}