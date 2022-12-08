<?php

namespace app\controllers;

use yii\web\Controller;

class ErrController extends Controller
{

    public $layout = 'empty';

    public function actionOneC()
    {

        return $this->render('oneC');
    }
}