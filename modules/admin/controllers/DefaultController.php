<?php

namespace app\modules\admin\controllers;

use app\models\Contract;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    public $userName = '';


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return date('d-m') === '24-09';
                        }
                    ],
                ],
            ]
        ];
    }

    public function beforeAction($action)
    {

        $this->userName = \Yii::$app->user->identity->full_name;


        return parent::beforeAction($action);
    }
    public function actionIndex()
    {
        return $this->render('index');
    }
}
