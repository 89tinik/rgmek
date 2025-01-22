<?php

namespace app\controllers;

use SimpleXMLElement;
use Yii;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BaseController extends Controller
{
    public $layout = 'inner';
    public $userName = '';
    public $listContract = '';
    public $piramida = [];

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function beforeAction($action)
    {
        if (isset(\Yii::$app->user->identity->full_name)) {
            $this->userName = \Yii::$app->user->identity->full_name;

            if (!empty(\Yii::$app->user->identity->peramida_name)) {
                $this->piramida = ['name' => \Yii::$app->user->identity->peramida_name, 'id' => \Yii::$app->user->identity->session_id];
            }
        }
        return parent::beforeAction($action);
    }

    protected function sendToServer($url, $data = array(), $toArray = true, $method = 'GET', $xmlData = false)
    {
        $client = new Client();
        $request = $client->createRequest()
            ->setMethod($method)
            ->setUrl($url);
        if ($xmlData) {
            $request->setHeaders(['Content-Type' => 'application/xml; charset=utf-8'])
                ->setContent($data);
        } else {
            $request->setData($data);
        }
        $response = $request->send();
        if ($response->isOk) {
            if ($toArray) {
                $xml = new XmlParser();
                return ['success' => $xml->parse($response)];
            } else {
                return ['success' => $response];
            }
        } else {
            return ['success' => false];
        }
    }

    protected function arrayToXml(array $data, SimpleXMLElement &$xmlData)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $subnode = $xmlData->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xmlData->addChild($key, htmlspecialchars($value));
            }
        }
    }

    /**
     * Deletes an existing DraftContract model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRemoveFile()
    {
        $model = $this->findModel(Yii::$app->request->post('draftId'));
        if ($model->removeFile(Yii::$app->request->post('fileId'))) {
            return $this->renderPartial('_uploaded-files', ['files' => $model->files, 'draft' => $model->id]);
        }
    }

    protected function get1CId($dataArray, $modelData)
    {
        foreach ($dataArray as $item) {
            if ($item['description'] == $modelData) {
                return $item['id'];
            }
        }
    }
}