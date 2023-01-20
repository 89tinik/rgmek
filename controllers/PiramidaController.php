<?php


namespace app\controllers;


use yii\base\Controller;
use Yii;

class PiramidaController extends Controller
{

    public function actionTest()
	{
		 $post = print_r(Yii::$app->request->getRawBody(), true);
       // $postArr = print_r(simplexml_load_string($post), true);
        Yii::error('starting \n');
        Yii::error('Это тест пост-' . $post . '\n');
        Yii::error('Это тест постpostArr1-' . $_POST . '\n');
        
        Yii::error('finishing \n');
		return false;
	}
    public function actionValidateSessionQ()
    {
        $post = Yii::$app->request->getRawBody();
		$array ='<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/"><s:Body><ValidateSession xmlns="PyramidExternalAuth"><sessionId>123</sessionId></ValidateSession></s:Body></s:Envelope>';
//$xml = simplexml_load_string($array, "SimpleXMLElement", LIBXML_NOCDATA);
//$json = json_encode($xml);
//$array = json_decode($json,TRUE);
  //      $postArr = print_r(simplexml_load_string($array), true);
        Yii::error('Это пост-' . $post . '\n');
        Yii::error('Это postArr-' . $array . '\n');
        $bod = file_get_contents('php://input');

        Yii::error('Это post' . $bod . '\n');
        return \Yii::$app->response->sendContentAsFile(
            '<?xml version="1.0" encoding="UTF-8"?>
            <s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
                            <s:Body>
                                <ValidateSessionResponse xmlns="PyramidExternalAuth">
                                    <ValidateSessionResult>Shlykov</ValidateSessionResult>
                                </ValidateSessionResponse>
                            </s:Body>
                          </s:Envelope>',
            'test.xml',
            ['mimeType' => 'text/xml', 'inline' => true]);

        $test = ['sdds' => 123];
        $vt = print_r($test, true);
        $vs = print_r($_SERVER, true);
        Yii::error('test-' . $vt . '\n');
        Yii::error('запрос от-' . $vs . '\n');
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
                        'ValidateSessionResult' => 'Shlykov'
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
