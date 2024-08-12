<?php

namespace app\modules\ticket;

/**
 * ticket module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ticket\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        \Yii::$app->setComponents(
            [
                'user' => [
                    'class' => 'yii\web\User',
                    'identityClass' => 'app\modules\ticket\models\Admin',
                    'loginUrl' => ['ticket/default/login'],
                    'authTimeout' => 3600,
                ],
            ]
        );
    }
}
