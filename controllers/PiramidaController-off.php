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
        Yii::error('Это тест пост-' . $post . '\n');
        Yii::error('Это тест постpostArr-' . $postArr . '\n');
		return false;
	}
    public function actionValidateSession()
    {
        $post = Yii::$app->request->getRawBody();
		$array ='<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/"><s:Body><ValidateSession xmlns="PyramidExternalAuth"><sessionId>123</sessionId></ValidateSession></s:Body></s:Envelope>';
//$xml = simplexml_load_string($array, "SimpleXMLElement", LIBXML_NOCDATA);
//$json = json_encode($xml);
//$array = json_decode($json,TRUE);
  //      $postArr = print_r(simplexml_load_string($array), true);
        Yii::error('Это пост-' . $post . '\n');
        Yii::error('Это постpostArr-' . $array . '\n');
        return \Yii::$app->response->sendContentAsFile(
            '<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
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