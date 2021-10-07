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

    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}