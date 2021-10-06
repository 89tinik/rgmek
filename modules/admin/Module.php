<?php

namespace app\modules\admin;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here

        \Yii::$app->setComponents(
            [
                'user' => [
                    'class' => 'yii\web\User',
                    'identityClass' => 'app\modules\admin\models\Admin',
                    'loginUrl' => ['admin/default/login'],
                    'authTimeout' => 3600,
                ],
            ]
        );


    }
}
