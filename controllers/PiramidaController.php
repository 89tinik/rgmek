<?php


namespace app\controllers;


use yii\base\Controller;
use Yii;

class PiramidaController extends Controller
{

    public function actionTest()
    {
        $post = print_r(Yii::$app->request->post(), true);
        Yii::error('Это пост-' . $post . '\n');
        if (Yii::$app->request->post('sessionId')) {
            $s = Yii::$app->request->post('sessionId');
        } else {
            $s = Yii::$app->request->get('sessionId');
        }
        if ($s == 'A2C199D6-1E72-4CBD-9142-56CCC84DE570') {
            return Yii::createObject([
                'class' => 'yii\web\Response',
                'format' => \yii\web\Response::FORMAT_XML,
                'data' => [
                    'ValidateSessionResponse' => [
                        'ValidateSessionResult' => 'c22314056'
                    ],
                ],
            ]);
        } else {
            return Yii::createObject([
                'class' => 'yii\web\Response',
                'format' => \yii\web\Response::FORMAT_XML,
                'data' => [
                    'ValidateSessionResponse' => [
                        'ValidateSessionResult' => 'пусто либо что-то другое могу сюда передать'
                    ],
                ],
            ]);
        }
    }
}